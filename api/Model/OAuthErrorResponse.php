<?php
class OAuthErrorResponse
{
	public $error_code;
	public $error_description;
	
	function __construct($error_code, $error_description){
		$this->error_code = $error_code;
		$this->error_description = $error_description;
	}
}
?>