<?php

class Controller_Notifications extends Controller_Core_Theme {
	
	public function action_index() {
		
		$this->title = __('notifications.name_plural');
		
		$user_id = $this->current_user->id;
		\Model_Notification::set_all_read($user_id);
		$notifications = \Model_Notification::get_all($user_id) ? : [];
		$this->content = \View::forge('notifications', 
				['notifications' => $notifications]);
	}
}
