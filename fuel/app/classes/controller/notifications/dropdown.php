<?php

class Controller_Notifications_Dropdown extends Controller_Core_Lang {
	
	public function before() {
		if (!\Request::is_hmvc()) {
			return;
		}
		parent::before();
	}
	
	public function get_index() {
		$user_id = $this->current_user->id;
		$count = \Model_Notification::get_unread_count($user_id);
		$notifications = \Model_Notification::get_unread($user_id) ? : [];
		
		\Event::trigger('gather_notifications');		
			
		return \View::forge('notifications/dropdown', 
				['unread_count' => $count > 0 ? $count : '', 'notifications' => $notifications]);
	}
	
	
}
