<?php

namespace Api;

class Controller_V1_Dummy extends \Fuel\Core\Controller_Rest {
	
	function action_index() {	
		$this->http_status = 404;
		return ['dummy endpoint'];
		return \Response::forge('hoi');
	}
}
