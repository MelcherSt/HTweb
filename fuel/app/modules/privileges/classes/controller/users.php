<?php

namespace Privileges;

class Controller_Users extends \Controller_Gate {
	
	public function action_nav() {
		return \View::forge('users/nav');
	}
}
