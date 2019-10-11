<?php
class OAuthException extends Exception
{
	public $error_status_code;	//401
	public $error_code;			//Not Found
	public $error_description;	//User not found.
	
	function __construct($error_status_code, $error_code, $error_description){
		$this->error_status_code = $error_status_code;
		$this->error_code = $error_code;
		$this->error_description = $error_description;
	}
}
?>