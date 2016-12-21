<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Gate {
		
	/**
	 * Handle enrollment creation
	 * @param type $date
	 */
	public function post_create($date=null) {
		if(\Utils::valid_date($date)) {
			if(empty($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			
			
			// Run validation to validate amount of guests
			$val = Model_Enrollment_Session::validate('create');
			if($val->run()) {
				$user_id = \Input::post('user_id', null);
				$cur_enrollment = $session->current_enrollment();

				if(empty($cur_enrollment)) {
					// Create an enrollment for current user		
					if (!$session->can_enroll()) {
						\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
					}

					$user_id = \Auth::get_user()->id;
					$user = \Model_User::find($user_id);	
				} else {
					$user = \Model_User::find($user_id);

					if (empty($user)) {
						\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), '/sessions/view/'.$date);
					}

					if(!$cur_enrollment->cook) {
						// Not a cook, may not enroll other users
						\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
					} else if (!$session->can_change_enrollments()) {
						// A cook, but not the correct timespan
						\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), '/sessions/view/'.$date);
					} 
				} 
				
				// We're through the whole timespan check now, everything's possible
				$guests = \Input::post('guests', 0);
				$cook = \Input::post('cook') == 'on' ? true : false;
				$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;

				if($cook && !$session->can_cook()) {
					$cook = false;
				}				

				if ($dishwasher && !$session->can_dishwasher()) {
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
			} else {	
				\Session::set_flash('error', $val->error('guests')->get_message());
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
		if(\Utils::valid_date($date)) {
			if(empty($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$context = Context::forge($session, \Auth::get_user());
			$redirect = '/sessions/view/'.$date;	
			
			if(!$context->can_perform(['enroll'])) {
				\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), $redirect);
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			$enrollment = null;		
			
			if(isset($user_id)) {
				// Trying to update other user. Check rights
				if($context->can_perform(['enroll_other'])) {
					$enrollment = $session->get_enrollment($user_id);
				} else {
					// Report error
					\Utils::handle_recoverable_error(__('session.alert.error.no_perm'), $redirect);
				}	
			} else {
				// Trying to update our enrollment.
				$enrollment = $cur_enrollment;
			} 
			
			if(empty($enrollment)) {
				\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]));
			}
			
			// Still rolling? Let's get on with updating the enrollment.
			$dishwasher_only = \Input::post('method') == 'dishwasher';
			
			if(!$dishwasher_only) {
				// All other enrollment details. Skip over this block if the request was dishwasher_only
				
				$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	

				$guests = \Input::post('guests', 0);
				if ($guests > Model_Session::MAX_GUESTS || $guests < 0) {
					$guests = 0;
					\Session::set_flash('error', __('session.alert.error.guests', ['max_guests' => Model_Session::MAX_GUESTS]));	
				} 
				$enrollment->guests = $guests;		
				$enrollment->later = \Input::post('later') == 'on' ? true : false;		
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
			
				
			} else if($context->can_perform(['update_dishwasher'])) {
				// Dishwasher only updated
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;	
			} else {
				// No rights for updating dishwasher. Report error.
				
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
		if(\Utils::valid_date($date)) {
			if(empty($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$context = Context::forge($session, \Auth::get_user());
			$redirect = '/sessions/view/'.$date;	
			
			if(!$context->can_perform(['enroll'])) {
				\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), $redirect);
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			$enrollment = null;		
			
			if(isset($user_id)) {
				// Trying to delete other user. Check rights
				if($context->can_perform(['enroll_other'])) {
					$enrollment = $session->get_enrollment($user_id);
				} else {
					// Report error
					\Utils::handle_recoverable_error(__('session.alert.error.no_perm'), $redirect);
				}	
			} else {
				// Trying to delete our enrollment.
				$enrollment = $cur_enrollment;
			} 
			
			if(empty($enrollment)) {
				\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]));
			}
			
			// Store name so we can report back.
			$name = $enrollment->user->name;
			
			// Still in the game? Let's delete that enrollment.
			try {
				$enrollment->delete();	
				\Session::set_flash('success', __('session.alert.success.remove_enroll', ['name' => $name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.remove_enroll', ['name' => $name]));	
			}
			\Response::redirect($redirect);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
}

