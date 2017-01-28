<?php

namespace Privileges;

class Controller_Admin extends \Controller_Gate {
	
	function before() {
		if(!\Auth::has_access('privileges.management')) {
			throw new \HttpNoAccessException();
		}
		parent::before();
	}	
	
	public function action_index() {
		return \Response::forge('Placeholder for privileges admin');
	
	}
}
