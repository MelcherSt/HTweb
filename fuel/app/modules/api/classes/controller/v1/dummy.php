<?php

namespace Api;

class Controller_V1_Dummy extends Controller_Auth {
	
	function action_index() {	
		return \Response::forge('dummy endpoint'. \Input::get('name'));
	}
}
