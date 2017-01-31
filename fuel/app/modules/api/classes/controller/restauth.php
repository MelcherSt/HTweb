<?php

namespace Api;

/**
 * Controller dealing with API authentication.  * Yes, this 'rest' API is not 
 * stateless and relies on the session for authentication by default.
 * Deal with it.
 */
class Controller_RestAuth extends \Controller_Rest {
	
	public function before() {
		parent::before();
		
		// Set our authentication method as authenticator for REST controller
		$this->auth = '_authenticate';
		
		// Set default return format
		$this->rest_format = 'json';
	}
	
	/**
	 * Authenticator function. 
	 * Override this function to change individual controller behavior.
	 * @return boolean
	 */
	protected function _authenticate() {
		return \Auth::check();
	}
}

