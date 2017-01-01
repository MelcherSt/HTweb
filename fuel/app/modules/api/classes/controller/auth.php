<?php

namespace Api;

class Controller_Auth extends \Controller_Rest {
	
	
	public function before() {
		parent::before();
		
		// Set our authentication method as authenticator for REST controller
		$this->auth = '_authenticate';
		
		// Set default return format
		$this->rest_format = 'json';
	}
	
	/**
	 * Authentication function
	 * @return boolean
	 */
	public function _authenticate() {
		return \Auth::check();
	}
}

