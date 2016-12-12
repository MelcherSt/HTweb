<?php

namespace Content;

class Controller_Posts extends \Controller_Gate {
	
	public function action_index() {
		$this->public_access = true;
		$this->template->title = 'Posts';
		if($this->public_request) {
			$this->template->content = \View::forge('public/index');
		} else {
			$this->template->content = \View::forge('index');
		}
	}	
}

