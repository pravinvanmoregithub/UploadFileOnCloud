<?php
function getCategoryByType($app, $type) {  
	$url = Configuration::$get_user_info_url;
	$authrization_header = getAuthorizationHeader($app);
	$request_headers[] = Configuration::$token_key . ': ' . $authrization_header;
	$service_response = executeGETRequest($app, $url, $request_headers);
	
	//deliverResponse($app, $service_response['http_code'], $service_response['response_content']);
	return $service_response['response_content'];
}
?>