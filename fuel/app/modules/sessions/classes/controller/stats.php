<?php

namespace Sessions;

class Controller_Stats extends \Controller_Core_Theme {
	
	public function action_index() {	
		$this->push_js('sessions-stats');
		$this->title = 'Statistics';
		$this->content = \View::forge('stats');
	}
}