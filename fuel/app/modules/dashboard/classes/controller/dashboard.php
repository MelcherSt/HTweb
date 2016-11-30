<?php

namespace Dashboard;

class Controller_Dashboard extends \Controller_Gate {
	
	function action_index() {
		
		
		
		$this->template->title = 'Dashboard';
		$this->template->content = \View::forge('dashboard/index');
	}
}
