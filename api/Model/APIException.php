<?php
class	APIException  extends Exception {
	public $url;
	public $statusCode;
	public $displayMessage;
	public $errorMessage;
	public $errorType;
	public $errorCode;
	public $requestId;
	
	function __construct($url, $statusCode, $displayMessage, $errorMessage, $errorType, $errorCode, $requestId){
		$this->url = $url;
		$this->statusCode = $statusCode;
		$this->displayMessage = $displayMessage;
		$this->errorMessage = $errorMessage;
		$this->errorType = $errorType;
		$this->errorCode = $errorCode;
		$this->requestId = $requestId;
	}
}
?>