<?php

class Controller_Notifications extends Controller_Core_Theme {
	
	public function action_index() {
		$this->content = '';
		$this->title = __('notifications.name_plural');
	}
}
