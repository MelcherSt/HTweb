<?php

namespace Privileges;

class Controller_Receipts extends \Controller_Gate {
	
	public function action_nav() {
		return \View::forge('receipts/nav');
	}
}
