<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Gate {
		
	/**
	 * Handle enrollment creation
	 * @param type $date
	 */
	public function post_create($date=null) {
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$user_id = \Input::post('user_id', null);
			
			// Is the current user enrolled and creating an enrollment for someone else?
			$cur_enrollment = $session->current_enrollment();
			$cur_cook = false;
			
			if(empty($cur_enrollment)) {
				if (!$session->can_enroll()) {
					\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
				}
				$user_id = \Auth::get_user()->id;
				$user = \Model_User::find($user_id);	
			} else {
				$user = \Model_User::find($user_id);
				$cur_cook = true;
				// user_id was set, but we're in a special situation now
				if (!$cur_enrollment->cook && $session->can_change_enrollments()) {
					\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
				} else if (!isset($user)) {
					\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), '/sessions/view/'.$date);
				}
			} 
			
			$guests = \Input::post('guests', 0);
			if ($guests > Model_Session::MAX_GUESTS || $guests < 0) {
				$guests = 0;
				\Session::set_flash('error', __('session.alert.error.guests', ['max_guests' => Model_Session::MAX_GUESTS]));	
			} 
			
			$cook = \Input::post('cook') == 'on' ? true : false;
			if (!$session->can_enroll_cooks($cur_cook)) {
				$cook = false;
			}
			
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			if (!$session->can_enroll_dishwashers($cur_cook)) {
				$dishwasher = false;
			}
			
			// Create from model
			$enrollment = Model_Enrollment_Session::forge([
				'user_id' => $user->id,
				'session_id' => $session->id,
				'later' => \Input::post('later') == 'on' ? true : false,
				'dishwasher' => $dishwasher,
				'cook' => $cook,
				'guests' => $guests,
			]);
			
			// Save
			try {
				$enrollment->save();
				\Session::set_flash('success', __('session.alert.success.create_enroll', ['name' => $user->name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.create_enroll', ['name' => $user->name]));	
			}
			
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
	
	/**
	 * Handle enrollment updates
	 * @param type $date
	 */
	public function post_update($date=null) {	
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			
			if(isset($user_id) && $cur_enrollment->cook) {	// Cook is enrolling another user
				if (empty($enrollment = $session->get_enrollment($user_id))) {
					\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => \Model_User::find($user_id)->name]), '/sessions/view/'.$date);
				}
			} else { // Updating current user's enrollment
				$enrollment = $cur_enrollment;
			}
			
			// Dishwasher is alwasy updated alone
			$dishwasher_only = \Input::post('method') == 'dishwasher';
			if(($session->can_enroll_dishwashers() || $enrollment->dishwasher) && $dishwasher_only) {
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;	
			} 
			
			// All other enrollment details. Skip over this block if the request was dishwasher_only
			if(($session->can_enroll() || ($cur_enrollment->cook && $session->can_change_enrollments())) && !$dishwasher_only) {
				$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	
				
				$guests = \Input::post('guests', 0);
				if ($guests > Model_Session::MAX_GUESTS || $guests < 0) {
					$guests = 0;
					\Session::set_flash('error', __('session.alert.error.guests', ['max_guests' => Model_Session::MAX_GUESTS]));	
				} 
				$enrollment->guests = $guests;		
				$enrollment->later = \Input::post('later') == 'on' ? true : false;		
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
			} else if (!$dishwasher_only) {
				// User may not edit enrollment
				\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);	
			}

			try {
				$enrollment->save();
				\Session::set_flash('success', __('session.alert.success.update_enroll', ['name' => $enrollment->user->name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.update_enroll', ['name' => $enrollment->user->name]));	
			}
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
	
	/**
	 * Handle enrollment deletion
	 * @param type $date
	 */
	public function post_delete($date=null) {		
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			
			if(isset($user_id) && $cur_enrollment->cook) {
				if (!$session->can_change_enrollments()) {
					\Utils::handle_recoverable_error(__('session.alert.error.remove_enroll', ['name' => \Model_User::find($user_id)->name]), '/sessions/view/'.$date);
				}
				
				// Cook is unenrolling another user
				$enrollment = $session->get_enrollment($user_id);	
				if (empty($enrollment)) {
					\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => \Model_User::find($user_id)->name]), '/sessions/view/'.$date);
				}
			} else {
				// Unenrolling ourselves
				if(!$session->can_enroll()) {
					\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
				}
				$enrollment = $cur_enrollment;
			}
			
			// Remember the name so we can report it back later.
			$name = $enrollment->user->name;
			
			try {
				$enrollment->delete();	
				\Session::set_flash('success', __('session.alert.success.remove_enroll', ['name' => $name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.remove_enroll', ['name' => $name]));	
			}
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
}

