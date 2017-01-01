<?php

namespace Content;

class Controller_Content extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = 'Posts';
		$this->template->content = '123';
	}
	
	
}