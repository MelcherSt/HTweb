<?php

namespace Wall;

class Controller_wall extends \Controller_Core_Theme {
	
	public function action_index() {
		$data['users'] = \Model_User::get_by_state();
		
		$this->title_page = __('user.wall.title');
		$this->title_sub = __('user.wall.sub');
		$this->content = \View::forge('index', $data);
	}
}
