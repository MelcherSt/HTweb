<?php

namespace Dashboard;

class Controller_Dashboard extends \Controller_Gate {
	
	public function before() {
		$this->public_access = true;
		parent::before();
	}
	
	function action_index() {	
		if($this->public_request) {
			$data['featured_post'] = \Content\Model_Post::get_first_public_featured();
			$this->template->title = __('dashboard.title');
			$this->template->content = \View::forge('public/index', $data);
		} else {
			$widgets = new \Data();
			\Event::trigger('gather_widgets', $widgets);

			$data = array();
			$data['widgets'] = $widgets->get_items();

			$this->template->title = __('dashboard.title');
			$this->template->content = \View::forge('index', $data);
		}
	}
}
