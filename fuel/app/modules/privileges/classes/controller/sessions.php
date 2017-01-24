<?php

namespace Privileges;

class Controller_Sessions extends \Controller_Gate {
	
	public function action_nav() {
		return \View::forge('sessions/nav');
	}
}
