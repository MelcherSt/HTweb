<?php

namespace DevTool;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {
		$this->template->message = "current branch";
		$this->template->count = '';
		$this->template->icon = 'fa-code-fork';
		$this->template->kind = \Request::forge('devtool/branch')->execute();
		$this->template->details = false;
	}
}

