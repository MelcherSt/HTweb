<?php

namespace DevTool;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {
		$this->template->message = \Request::forge('devtool/hash')->execute();
		$this->template->count = \Request::forge('devtool/branch')->execute();
		$this->template->icon = 'fa-code-fork';
		$this->template->kind = 'branch';
		$this->template->details = false;
	}
}

