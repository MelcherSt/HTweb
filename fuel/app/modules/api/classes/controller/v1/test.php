<?php

namespace Api;

class Controller_V1_Test extends \Controller {
	
	function action_index() {
		return \Response::forge('hey there'. \Input::get('name'));
	}
}
