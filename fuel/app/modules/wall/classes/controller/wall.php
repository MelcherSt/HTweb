<?php

namespace Wall;

class Controller_wall extends \Controller_Secure {
	
	public function action_index() {
		$data['users'] = \Model_User::get_by_state();
		
		$this->template->page_title = $this->template->title = __('user.wall.title');
		$this->template->subtitle = __('user.wall.sub');
		$this->template->content = \View::forge('index', $data);
	}
}
