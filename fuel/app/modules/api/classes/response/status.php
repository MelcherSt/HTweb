<?php

namespace Api;

/**
 * A response status object for use with API endpoints. When returned from 
 * an API endpoint, http_status is automatically set to status value.
 */
class Response_Status extends Response_Base {
	
	public $status;
	public $message;
	
	public function __construct($status, $message='') {
		$this->status = $status;
		$this->message = $message;
	}
	
	/**
	 * The request is malformed, such as if the body does not parse.
	 * @return \Api\Response_Status
	 */
	public static function _400($message='') {
		return new Response_Status(400, "Bad Request" . $message);
	}
	
	/**
	 * When no or invalid authentication details are provided.
	 * @return \Api\Response_Status
	 */
	public static function _401($message='') {
		return new Response_Status(401, "Unauthorized" . $message);
	}
	
	/**
	 * When authenticated user doesn't have access to the resource.
	 * @return \Api\Response_Status
	 */
	public static function _403($message='') {
		return new Response_Status(403, "Forbidden" . $message);
	}
	
	/**
	 * When a non-existent resource is requested
	 * @return \Api\Response_Status
	 */
	public static function _404($message='') {
		return new Response_Status(404, "Not Found" . $message);
	}
	
	/**
	 * When an HTTP method is being requested that isn't allowed for the authenticated user
	 * @return \Api\Response_Status
	 */
	public static function _405($message='') {
		return new \Api\Response_Status(405, "Method Not Allowed" . $message);
	}
	
	/**
	 * When the request could not be understood by the server due to malformed syntax. 
	 * @return \Api\Response_Status
	 */
	public static function _422($message='') {
		return new \Api\Response_Status(422, "Unprocessable Entity" . $message);
	}
	
	/**
	 * The server encountered an unexpected condition which prevented it from fulfilling the request.
	 * @param type $message
	 * @return \Api\Response_Status
	 */
	public static function _500($message='') {
		return new \Api\Response_Status(500, "Internal Error" . $message);
	}
	
	
}