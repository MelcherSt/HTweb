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
			
			if(!isset($cur_enrollment)) {
				if (!$session->can_enroll()) {
					\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
				}
				$user_id = \Auth::get_user()->id;
				$user = \Model_User::find($user_id);	
			} else {
				$user = \Model_User::find($user_id);
				
				// user_id was set, but we're in a special situation now
				if (!$cur_enrollment->cook && $session->can_change_enrollments()) {
					\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
				} else if (!isset($user)) {
					\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), '/sessions/view/'.$date);
				}
			} 
			
			$guests = \Input::post('guests', 0);
			if ($guests > Model_Session::MAX_GUESTS) {
				$guests = 20;
				\Session::set_flash('error', __('session.alert.error.too_many_guest', ['max_guests' => Model_Session::MAX_GUESTS]));	
			} 
			
			// Create from model
			$enrollment = Model_Enrollment_Session::forge(array(
				'user_id' => $user->id,
				'session_id' => $session->id,
				'dishwasher' => \Input::post('dishwasher', false) == 'on' ? true : false,
				'cook' => \Input::post('cook', false) == 'on' ? true : false,
				'guests' => $guests,
			));
			
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
				$enrollment = $session->get_enrollment($user_id);	
				if (!$enrollment) {
					\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => \Model_User::find($user_id)->name]), '/sessions/view/'.$date);
				}
			} else { // Enrolling ourselves
				$enrollment = $cur_enrollment;
			}
			
			// Method is diswasher when using dishwasher button.
			// This case needs to be handled differently than normal update.
			$dishwasher_only = \Input::post('method') == 'dishwasher';
			
			if ($enrollment->cook && !$dishwasher_only && !isset($user_id)) {
				// Actually we're updating the session here 	
				if($session->can_change_cost()) {
						$new_cost = \Input::post('cost', 0.0);
						$cur_cost = $session->cost;
						
					if ($new_cost != $cur_cost) {
						// Cost has been updated by this cook. Set him as payer.
						$session->paid_by = $enrollment->user->id;
						$session->cost = $new_cost;	
					}		
				}		
				if($session->can_change_deadline()) {
					$deadline = date($date. ' ' . \Input::post('deadline', Model_Session::DEADLINE_TIME));
					$session->deadline = $deadline;
				}	
				if($session->can_enroll()) {
					$notes = \Input::post('notes', '');		
					$session->notes = $notes;
				}
				if(!$session->save()) {
					\Utils::handle_recoverable_error(__('session.alert.error.update_session'), '/sessions/view/'.$date);	
				}
			}	

			if(($session->can_enroll() || ($cur_enrollment->cook && $session->can_change_enrollments())) && !$dishwasher_only) {
				$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	
				
				
				$guests = \Input::post('guests', 0);
				if ($guests > Model_Session::MAX_GUESTS) {
					$enrollment->guests = 20;
					\Session::set_flash('error', __('session.alert.error.too_many_guest', ['max_guests' => Model_Session::MAX_GUESTS]));	
				} else {
					$enrollment->guests = \Input::post('guests', 0);
				}
				
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
			} else if(!$enrollment->cook) {
				\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);	
			}

			if($session->can_enroll_dishwashers()) {
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;			
				\Session::delete_flash('error'); // Remove any errors
			}
			
			$user = $enrollment->user;
			
			// Save
			try {
				$enrollment->save();
				\Session::set_flash('success', __('session.alert.success.update_enroll', ['name' => $user->name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.update_enroll', ['name' => $user->name]));	
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
				// Cook is unenrolling another user
				$enrollment = $session->get_enrollment($user_id);	
				if (!$enrollment) {
					\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => \Model_User::find($user_id)->name]), '/sessions/view/'.$date);
				}
			} else {
				// Unenrolling ourselves
				if(!$session->can_enroll()) {
					// User should not be able to enroll.
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

