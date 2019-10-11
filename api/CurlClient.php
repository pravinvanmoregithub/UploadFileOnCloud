<?php
function executeGETRequest($app, $url, $request_headers) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	
	$service_response = executeRequest($ch, $request_headers);
	if($service_response['http_code'] == 200) {
		return $service_response;
	} else if($service_response['http_code'] == 401 || $service_response['http_code'] == 403) {
		$oauthAPIError = json_decode($service_response['response_content']);
		if(is_object($oauthAPIError)){
			$app->error(new OAuthException($service_response['http_code'], $oauthAPIError->error, $oauthAPIError->error_description));
		}
		$app->error(new OAuthException($service_response['http_code'], "displayMessage", "errorMessage"));	
	} else {
		$apiAPIError = json_decode($service_response['response_content']);
		if(is_object($apiAPIError)){
			$app->error(new APIException($apiAPIError->url, $apiAPIError->statusCode, $apiAPIError->displayMessage, $apiAPIError->errorMessage, $apiAPIError->errorType, $apiAPIError->errorCode, $apiAPIError->requestId));
		}
		$app->error(new APIException("url", 500, "displayMessage", "errorMessage", "errorType", 999, "requestId"));		
	}		
}

function executePOSTRequest($app, $url, $data_string, $request_headers){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	
	return executeRequest($ch, $request_headers);
}

function executeDELETERequest($app, $url, $request_headers){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	
	return executeRequest($ch, $request_headers);
}

function executePUTRequest($app, $url, $data_string, $request_headers){
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	
	return executeRequest($ch, $request_headers);
}

function executeRequest($ch, $request_headers){
	curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	
	$response = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	if($httpcode != 0) {
		list($headers, $content) = explode("\r\n\r\n",$response, 2);
	
		$response = array(
			"http_code" => $httpcode,
			"http_response" => $response,
			"response_content" => $content,
			"response_headers" => $headers
		);
	} else {
		$response = array(
			"http_code" => $httpcode,
			"http_response" => "",
			"response_content" => "",
			"response_headers" => ""
		);
	}
	
	curl_close($ch);
	return $response;
}
?>