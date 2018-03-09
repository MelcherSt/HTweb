<?php

class Controller_Notifications_Dropdown extends Controller_Core_View {
	
	public function before() {
		if (!\Request::is_hmvc()) {
			return;
		}
		parent::before();
	}
	
	public function get_index() {
		$user_id = $this->current_user->id;
		$count = \Model_Notification::get_unread_count($user_id) > 0 ? : '';
		$notifications = \Model_Notification::get_unread($user_id) ? : [];
		
		return \View::forge('notifications/dropdown', 
				['unread_count' => $count, 'notifications' => $notifications]);
	}
	
	
}
