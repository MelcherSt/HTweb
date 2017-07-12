<?php

namespace Stats;

class Controller_Stats extends \Controller_Core_Secure {
	
	public function action_index() {	
		$this->template->title = 'Statistics';
		$this->template->content = \View::forge('index');
	}
}