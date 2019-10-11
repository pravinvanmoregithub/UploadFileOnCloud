<?php

$authorizationToken = base64_encode("PRAVIN:VANMORE");

function deliverResponse($app, $httpStatus, $apiResponse) {
	$app->response->setStatus($httpStatus);
	$app->response()->header("Content-Type", "application/json");
	echo $apiResponse;
}

function getAuthorizationHeader($app) {
	$headers = $app->request->headers;
	$authrization_header = "";
	
	foreach($headers as $k=>$v){
		if($k == Configuration::$token_key)
		{
			$authrization_header = $v;
			break;
		}
	}
	
	if($authrization_header == "") {
		$app->error(new OAuthException(401, "Unauthorized", "Token not found"));		
	}
	
	return $authrization_header;
}

if (!function_exists('apache_request_headers')) {
    function apache_request_headers()
    {
        $headers = [];
        foreach ($_SERVER as $name => $value)
        {
             if(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))=='Nt-Type')
                 $headers['Content-Type']=$value;
             elseif(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))=='Authorization')
                 $headers['Authorization']=$value;
        }
        return $headers;
    }
}

function CheckAuthorizationKey($Token){
	global $authorizationToken;

	if($Token == $authorizationToken){
		return true;
	}else{
		return false;
	}

}
?>


