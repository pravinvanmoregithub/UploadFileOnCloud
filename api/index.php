<?php
/* error_reporting(E_ALL);
ini_set('display_errors', 1); */
/* echo $_SERVER['DOCUMENT_ROOT'];
echo "<br>12";
 */
 require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Slim/Slim.php');
//require_once 'Slim\Slim.php';

\Slim\Slim::registerAutoloader();

require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Model/OAuthException.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Model/OAuthErrorResponse.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Model/APIException.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Model/FileRequest.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Common.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/onlinetest/api/Config/Connection.php');

$app = new \Slim\Slim();



$app->error(function (\Exception $e) use ($app) {
    if ($e instanceof \APIException) {
		$app->response->setStatus($e->statusCode);
		$app->response()->header("Content-Type", "application/json");
		echo json_encode($e);
	} else if ($e instanceof \OAuthException) {
		$oauthErrorResponse = new OAuthErrorResponse($e->error_code, $e->error_description);
        $app->response->setStatus($e->error_status_code);
		$app->response()->header("Content-Type", "application/json");
		echo json_encode($oauthErrorResponse);
	} else {
        echo "Exception";
    }	
});


$app->post("/uploadfile", function () use($app) {

	$connection = new createConnection(); //i created a new object

	$connect = $connection->connectToDatabase(); // connected to the database

	$PostRequest = json_decode($app->request()->getBody());

	$APIHeaders = apache_request_headers();

	if(empty($APIHeaders))
        $app->error(new APIException("", 401, "ERROR", "Header is not found.","missing header",111,"header is missing"));
    elseif(!isset($APIHeaders['Content-Type']) || empty($APIHeaders['Content-Type']))
        $app->error(new APIException("", 401, "ERROR", "Content Type is not found.","Content Type is not found.",111,"header is missing"));
    elseif(!isset($APIHeaders['Authorization']) || empty($APIHeaders['Authorization']))
        $app->error(new APIException("", 401, "ERROR", "Authorization key is not found.","Authorization key is not found.",111,"header is missing"));

	if(!is_object($PostRequest)) {
		$app->error(new APIException("url", 400, "Invalid details", "Invalid details", "NotObject", 123, "Invalid Object"));
	}
	
	if(!property_exists($PostRequest, 'FileUrl')) {
		$app->error(new APIException("url", 400, "file url can not be empty", "file url can not be empty", "NotNull", 111, "file url field missing"));
	}

	if(!property_exists($PostRequest, 'FileWidth')) {
		$app->error(new APIException("url", 400, "file width can not be empty", "file width can not be empty", "NotNull", 111, "file width field missing"));
	}
	
	if(!property_exists($PostRequest, 'FileHeight')) {
		$app->error(new APIException("url", 400, "file height can not be empty", "file height can not be empty", "NotNull", 111, "file height field missing"));
	}

	$AuthorizationKeyFlag = CheckAuthorizationKey($APIHeaders['Authorization']);

	if(!$AuthorizationKeyFlag){
		$app->error(new APIException("", 403, "ERROR", "Authorization key is not valid.","Authorization key is not valid.",111,"Not Authorized to access"));
	}else{

		$FileUploadRequestResp = UploadFile($connect,$PostRequest);

		deliverResponse($app, 200, json_encode($FileUploadRequestResp));
		
	}
	

	$connection->closeConnection();

});

// run the Slim app
$app->run();
?>