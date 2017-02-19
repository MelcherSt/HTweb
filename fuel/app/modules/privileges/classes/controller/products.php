<?php

namespace Privileges;

class Controller_Products extends \Controller_Secure {
	
	public function action_nav() {
		return \View::forge('products/nav');
	}
}
