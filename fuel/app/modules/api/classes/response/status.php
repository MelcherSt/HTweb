<?php

namespace Api;

class Response_Status extends Response_Base {
	
	public $status;
	public $message;
	
	public function __construct($status, $message='') {
		$this->status = $status;
		$this->message = $message;
	}
	
	public static function _400() {
		return new Response_Status(400, "Bad Request");
	}
	
	/**
	 * When no or invalid authentication details are provided.
	 * @return \Api\Response_Status
	 */
	public static function _401() {
		return new Response_Status(401, "Unauthorized");
	}
	
	/**
	 * When authenticated user doesn't have access to the resource.
	 * @return \Api\Response_Status
	 */
	public static function _403() {
		return new Response_Status(403, "Forbidden");
	}
	
	/**
	 * When a non-existent resource is requested
	 * @return \Api\Response_Status
	 */
	public static function _404() {
		return new Response_Status(404, "Not Found");
	}
	
	/**
	 * When an HTTP method is being requested that isn't allowed for the authenticated user
	 * @return \Api\Response_Status
	 */
	public static function _405() {
		return new \Api\Response_Status(405, "Method Not Allowed");
	}
	
	
}
