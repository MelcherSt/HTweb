<?php

namespace Privileges;

class Controller_Receipts extends \Controller_Secure {
	
	public function action_nav() {
		return \View::forge('receipts/nav');
	}
}
