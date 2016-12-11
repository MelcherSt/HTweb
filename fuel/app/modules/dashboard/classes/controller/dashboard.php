<?php

namespace Dashboard;

class Controller_Dashboard extends \Controller_Gate {
	
	function action_index() {				
		$widgets = new \Data();
		\Event::trigger('gather_widgets', $widgets);
		
		$data = array();
		$data['widgets'] = $widgets->get_items();
		
		$this->template->title = '';
		$this->template->content = \View::forge('index', $data);
	}
}
