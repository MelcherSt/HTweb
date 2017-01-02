<?php

namespace Api;

class Controller_v1_Users extends Controller_Auth {
	
	public function action_index() {
		// Explicitly return all (in)active users
		$active = \Input::get('active', null);
		
		if(isset($active)) {
			return array_values(Model_User::find('all', array(
			'where' => array(
				array('active', $active)))));
		} else {
			return array_values(Model_User::find('all'));
		}
	}
}
