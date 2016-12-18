<?php

namespace Api;

class Controller_Auth extends \Controller_Rest {
	
	public function _authenticate() {
		return \Auth::check();
	}
}

