<?php

namespace Dashboard;

class Controller_Dashboard extends \Controller_Gate {
	
	
	function action_index() {
		$this->public_access = true; // Content is publicly accessible
		
		if($this->public_request) {
			$data['featured_post'] = \Content\Model_Post::get_first_public_featured();
			$this->template->title = '';
			$this->template->content = \View::forge('public/index', $data);
		} else {
			$widgets = new \Data();
			\Event::trigger('gather_widgets', $widgets);

			$data = array();
			$data['widgets'] = $widgets->get_items();
			$data['featured_post'] = \Content\Model_Post::get_first_featured();

			$this->template->title = '';
			$this->template->content = \View::forge('index', $data);
		}
	}
}
