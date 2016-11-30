<?php

namespace Session;

class Controller_Session extends \Controller_Gate {
	
	public function action_today() {
		$this->template->title = 'Sessions';
		$this->template->content = 'hoi';
	}
	
	public function action_items($id) {
		$this->template->title = 'Sessions';
		$this->template->content = $id;
	}
}

