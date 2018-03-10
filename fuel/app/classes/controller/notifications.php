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
	
	/**
	 * Redirect to the link of the notification and set notification as read.
	 * @param int $id
	 */
	public function get_redirect($id) {
		\Utils::check_non_null($notification = \Model_Notification::find($id));
		
		if (!$notification->user_id == $this->current_user->id) {
			throw new HttpNoAccessException();
		}
		
		$notification->set('read', true)->save();
		\Response::redirect($notification->href);
	}
}
