<?php

namespace Content;

class Controller_Posts extends \Controller_Gate {
	
	public function before() {
		$this->public_access = true;
		parent::before();
	}
	
	public function action_index() {
		
		$this->template->title = 'Posts';
		if($this->public_request) {
			$this->template->content = \View::forge('public/index');
		} else {
			$this->template->content = \View::forge('index');
		}
	}

	public function action_view($content_id=0) {
		$post = Model_Post::find($content_id);
		
		$this->template->title = $post->title;
		$this->template->post = $post;
	}	
}

