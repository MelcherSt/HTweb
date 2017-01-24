<?php

namespace Privileges;

class Controller_Products extends \Controller_Gate {
	
	public function action_nav() {
		return \View::forge('products/nav');
	}
}
