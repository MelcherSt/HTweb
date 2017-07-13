<?php

namespace Stats;

class Controller_Stats extends \Controller_Core_Theme {
	
	public function action_index() {	
		$this->title = 'Statistics';
		$this->content = \View::forge('index');
	}
}