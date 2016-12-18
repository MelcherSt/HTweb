<?php

namespace Api;

class Controller_Users extends Controller_Auth {
	
	public function before() {
		parent::before();
		
		$this->auth = '_authenticate';
		$this->rest_format = 'json';
	}
	
	public function get_index() {
		$this->rest_format = 'xml';
		
		return $this->response(Model_User::find('all'));
	}
	
	function _authenticate() {
		return true;
	}
}

