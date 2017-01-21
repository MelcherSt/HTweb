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
			
			$cur_user = \Auth::get_user();
			$context = Auth_Context_Session::forge($session, $cur_user);
			$redirect = '/sessions/view/'.$date;				
			
			// Run validation to validate amount of guests
			$val = Model_Enrollment_Session::validate('create');
			if($val->run()) {
				/* Input variables */
				$user_id = \Input::post('user_id', null);
				$guests = \Input::post('guests', 0);
				$cook = \Input::post('cook') == 'on' ? true : false;
				$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
				$later = \Input::post('later') == 'on' ? true : false;
				
				if(isset($user_id)) {
					// Trying to create other user. Check rights
					if(!$context->has_access(['enroll.other'], true)) {
						\Utils::handle_recoverable_error($context->get_message(), $redirect);
					}	

					if (empty($user = \Model_User::find($user_id))) {
						\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), $redirect);
					}
					
					$dishwasher = $context->has_access(['enroll.other[dishwasher]']) && $dishwasher;
					$cook = $context->has_access(['enroll.other[cook]']) && $cook;	

				} else {
					// Create a new enrollment for current user.
					if(!$context->has_access(['enroll.create'])) {
						\Utils::handle_recoverable_error($context->get_message(), $redirect);
					}

					$user = \Model_User::find($cur_user->id);		
					$dishwasher = false;
					$cook = $context->has_access(['enroll.create[cook]']) && $cook;	
				} 

				// Create from model
				$enrollment = Model_Enrollment_Session::forge([
					'user_id' => $user->id,
					'session_id' => $session->id,
					'later' => $later,
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
			\Response::redirect($redirect);
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
			
			$context = Auth_Context_Session::forge($session);
			$redirect = '/sessions/view/'.$date;				
			
			// Run validation to validate amount of guests
			$val = Model_Enrollment_Session::validate('update');
			if($val->run()) {	
				/* Input variables */
				$user_id = \Input::post('user_id', null);
				$guests = \Input::post('guests', 0);
				$cook = \Input::post('cook') == 'on' ? true : false;
				$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
				$later = \Input::post('later') == 'on' ? true : false;

				if(isset($user_id)) {
					// Trying to update other user. Check rights
					if(!$context->has_access(['enroll.other'], true)) {
						\Utils::handle_recoverable_error($context->get_message(), $redirect);
					}	

					if (empty($user = \Model_User::find($user_id))) {
						\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), $redirect);
					}

					$enrollment = $session->get_enrollment($user_id);	
					$dishwasher = $context->has_access(['enroll.other[' . ($enrollment->dishwasher ? 'set-dishwasher,' : '') . 'dishwasher]']) ? $dishwasher : $enrollment->dishwasher;
					$cook = $context->has_access(['enroll.other[' . ($enrollment->cook ? 'set-cook,' : '') . 'cook]']) ? $cook : $enrollment->cook;	
				} else {
					// Trying to update our enrollment.			
					if(!$context->has_access(['enroll.update'], true)) {
						\Utils::handle_recoverable_error($context->get_message(), $redirect);
					}

					$enrollment = $session->current_enrollment();		
					$dishwasher = $context->has_access(['enroll.update[dishwasher]'], true) ? $dishwasher : $enrollment->dishwasher;
					$cook = $context->has_access(['enroll.update[cook]'], true) ? $cook : $enrollment->cook;	
				} 

				if(empty($enrollment)) {
					\Utils::handle_recoverable_error(__('sesion.alert.error.no_enrollment', ['name' => $user_id=0]));
				}

				$enrollment->dishwasher = $dishwasher;
				// Check dishwasher flag. When set, we're only going to update dishwasher.
				if(!\Input::post('method') == 'dishwasher') {
					$enrollment->dishwasher = $dishwasher;
					$enrollment->cook = $cook;
					$enrollment->guests = $guests;
					$enrollment->later = $later;	
				}

				try {
					$enrollment->save();
					\Session::set_flash('success', __('session.alert.success.update_enroll', ['name' => $enrollment->user->name]));
				} catch (\Database_Exception $ex) {
					\Session::set_flash('error', __('session.alert.error.update_enroll', ['name' => $enrollment->user->name]));	
				}
			} else {	
				\Session::set_flash('error', $val->error('guests')->get_message());
			}		
			\Response::redirect($redirect);
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
			
			$context = Auth_Context_Session::forge($session);
			$redirect = '/sessions/view/'.$date;	
			
			/* Input variable */
			$user_id = \Input::post('user_id', null);	
			
			if(isset($user_id)) {
				// Request rights to delete other user
				if(!$context->has_access(['enroll.other'], true)) {
					// Report error
					\Utils::handle_recoverable_error($context->get_message(), $redirect);	
				}	
				
				if (empty($user = \Model_User::find($user_id))) {
					\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]), $redirect);
				}
				
				$enrollment = $session->get_enrollment($user_id);			
			} else {
				if(!$context->has_access(['enroll.delete'], true)) {
					// Drop out
					\Utils::handle_recoverable_error($context->get_message(), $redirect);
				}
				
				// Trying to delete our enrollment.
				$enrollment = $session->current_enrollment();
			} 
			
			if(empty($enrollment)) {
				\Utils::handle_recoverable_error(__('sesion.alert.error.no_enrollment', ['name' => $user_id=0]));
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

