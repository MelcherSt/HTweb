<?php

namespace Api;

/**
 * Controller dealing with API authentication.  * Yes, this 'rest' API is not 
 * stateless and relies on the session for authentication by default.
 * Deal with it.
 */
class Controller_Base extends \Controller_Rest {
	
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
	 * Override this function to change individual controller behavior.
	 * @return boolean
	 */
	protected function _authenticate() {
		return \Auth::check();
	}
	
	public function after($response) {
		if($response instanceof Response_Status) {
			// Set http status and return response body
			$this->http_status($response->status);
		} 
		
		if ($response instanceof Response_Base || empty($response)) {
			return parent::after((array)$response);
		} else {
			// Any other objects 
			return $this->after(Response_Status::_500(': Illegal object type returned by endpoint.'));
		}
	}
}
