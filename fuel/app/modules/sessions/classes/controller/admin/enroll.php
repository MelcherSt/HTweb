<?php

namespace Sessions;

class Controller_Enroll extends \Controller_Gate {
	
	function before() {
		if(!\Auth::has_access('sessions.management')) {
			throw new \HttpNoAccessException();
		}
		
		parent::before();
	}
	
	public function post_index() {
		
	}
	
	public function delete_index() {
		
	}
}
