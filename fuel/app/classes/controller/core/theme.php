<?php

class Controller_Core_Theme extends Controller_Core_Secure {
	
	public function before() {
		$this->theme = \Theme::instance();
		$this->theme->set_template('template/default');
	}
	
	public function after($response) {
		if(empty($response) || !$response instanceof \Fuel\Core\Response) {
			$response = \Fuel\Core\Response::forge(\Theme::instance()->render());
		}
		return parent::after($response);
	}
}