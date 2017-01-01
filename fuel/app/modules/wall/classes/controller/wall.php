<?php

namespace Wall;

class Controller_wall extends \Controller_Gate {
	
	public function action_index() {
		$data['users'] = \Model_User::get_by_state();
		
		$this->template->title = 'The Wall';
		$this->template->subtitle = 'of fame';
		$this->template->content = \View::forge('index', $data);
	}
}
