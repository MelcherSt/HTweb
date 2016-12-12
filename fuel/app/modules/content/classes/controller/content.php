<?php

namespace Content;

class Controller_Content extends Controller_Gate {
	
	public function action_index() {
		$this->template->title = 'Posts';
		$this->template->content = '123';
	}
	
	public function action_view($content_id=0) {
		$post = Model_Post::find($content_id);
		
		$this->template->title = $post->title;
		$this->template->post = $post;
	}
}