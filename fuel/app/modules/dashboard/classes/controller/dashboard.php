<?php

namespace Dashboard;

class Controller_Dashboard extends \Controller_Core_Theme {
	
	public function before() {
		$this->public_content = true;
		parent::before();
	}
	
	function action_index() {	
		if($this->public_request) {	
			\Response::redirect('gate/login');
			//$this->template->title = __('dashboard.title');
			//$this->template->content = \View::forge('public/index');
		} else {
			$widgets = new \Data();
			\Event::trigger('gather_widgets', $widgets);
			$data = array();
			$data['widgets'] = $widgets->get_items();
			$this->title = __('dashboard.title');
			$this->content = \View::forge('index', $data);
		}
	}
}
