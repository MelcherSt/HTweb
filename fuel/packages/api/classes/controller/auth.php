<?php

namespace Api;

/**
 * Controller dealing with API authentication and response creation. 
 * Yes, this 'rest' API is not stateless and relies on the PHP session for authentication by default.
 * --Deal with it.
 */
class Controller_Auth extends \Controller_Rest {
	
	public function before() {
		parent::before();
		
		// Set our authentication method as authenticator for REST controller
		$this->auth = '_authenticate';
		
		// Set default return format
		$this->rest_format = 'json';
		$this->format = 'json';
	}
	
	/**
	 * Authenticator function. 
	 * Override this function to change individual controller behavior. <br>
	 * Overriding can be used to create an 'insecure' Api controller if needed.
	 * @return boolean
	 */
	protected function _authenticate() : bool {
		return \Auth::check();
	}
	
	public function after($response) {
		if($response instanceof Response_Status) {
			// Set http status and return response body
			$this->http_status($response->status);
		} 

		if ($response instanceof \Response) {
			// If response was explicitly set, return it
			return parent::after($response);
		} else {
			// Otherwise, return the object as array
			return parent::after((array)$response);
		}
	}
}
