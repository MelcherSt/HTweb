<?php

namespace Session;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {
		$this->template->style = 'panel-green';
		$this->template->count = '6';
		$this->template->kind = 'people';
		$this->template->icon = 'fa-cutlery';
		$this->template->message = 'Are already eating today';
		$this->template->detail = 'Care to joy them?';
		$this->template->link = 'session/today';
	}
}

