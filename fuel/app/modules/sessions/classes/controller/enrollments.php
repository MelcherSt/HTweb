<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Gate {
	
	/**
	 * Handle quick enrollment creation. Only supports basic enrollments.
	 */
	public function post_quick() {
		$dates = \Input::post('dates', []);
		foreach($dates as $date) {
			if(!\Utils::valid_date($date)) { continue;	}
			
			$session = \Utils::valid_session($date, false);
			if(empty($session)) {
				$session = Model_Session::forge([
					'deadline' => $date. ' ' . Model_Session::DEADLINE_TIME,
					'date' => $date,
				]);
				$session->save();
			}
			
			$enrollment = $session->current_enrollment();
			$context = Context_Sessions::forge($session);
			
			if(empty($enrollment) && $context->create_enroll()) {
				$enrollment = Model_Enrollment_Session::forge([
					'user_id' => \Auth::get_user()->id,
					'session_id' => $session->id,
				]);
				$enrollment->save();
			}
		}
		\Response::redirect_back();
	}
	
	/**
	 * Handle enrollment creation
	 * @param type $date
	 */
	public function post_create($date=null) {		
		//TODO: switch to new context
		$session = \Utils::valid_session($date);
					
		$cur_user = \Auth::get_user();
		$context = Auth_Context_Session::forge($session, $cur_user);	

		// Run validation to validate amount of guests
		$val = Model_Enrollment_Session::validate('create');
		if($val->run()) {
			/* Input variables */
			$user_id = \Input::post('user-id', null);
			$guests = \Input::post('guests', 0);
			$cook = \Input::post('cook') == 'on' ? true : false;
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			$later = \Input::post('later') == 'on' ? true : false;

			if(isset($user_id)) {
				// Trying to create other user. Check rights
				if(!$context->has_access(['enroll.other'], true)) {
					\Utils::handle_recoverable_error($context->get_message());
				}	

				if (empty($user = \Model_User::find($user_id))) {
					\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]));
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
		\Response::redirect_back();
	
	}
	
	/**
	 * Handle enrollment updates
	 * @param type $date
	 */
	public function post_update($date=null) {	
		//TODO: switch to new context
		$session = \Utils::valid_session($date);
			
		$old_context = Auth_Context_Session::forge($session);
		
		// Run validation to validate amount of guests
		$val = Model_Enrollment_Session::validate('update');
		if($val->run()) {	
			/* Input variables */
			$user_id = \Input::post('user-id', null);
			$guests = \Input::post('guests', 0);
			$cook = \Input::post('cook') == 'on' ? true : false;
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			$later = \Input::post('later') == 'on' ? true : false;

			if(isset($user_id)) {
				// Trying to update other user. Check rights
				if(!$old_context->has_access(['enroll.other'], true)) {
					\Utils::handle_recoverable_error($old_context->get_message());
				}	

				if (empty($user = \Model_User::find($user_id))) {
					\Utils::handle_recoverable_error(__('user.alert.error.no_id', ['id' => $user_id]));
				}

				$enrollment = $session->get_enrollment($user_id);	
				$dishwasher = $old_context->has_access(['enroll.other[' . ($enrollment->dishwasher ? 'set-dishwasher,' : '') . 'dishwasher]']) ? $dishwasher : $enrollment->dishwasher;
				$cook = $old_context->has_access(['enroll.other[' . ($enrollment->cook ? 'set-cook,' : '') . 'cook]']) ? $cook : $enrollment->cook;	
			} else {
				// Trying to update our enrollment.			
				if(!$old_context->has_access(['enroll.update'], true)) {
					\Utils::handle_recoverable_error($old_context->get_message());
				}

				$enrollment = $session->current_enrollment();		
				$dishwasher = $old_context->has_access(['enroll.update[dishwasher]'], true) ? $dishwasher : $enrollment->dishwasher;
				$cook = $old_context->has_access(['enroll.update[cook]'], true) ? $cook : $enrollment->cook;	
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
		\Response::redirect_back();
	}
	
	/**
	 * Handle enrollment deletion
	 * @param string $date
	 */
	public function post_delete($date=null) {		
		$session = \Utils::valid_session($date);
		$context = Context_Sessions::forge($session);	
		$user_id = \Input::post('user-id', null);	
		
		if ($context->delete_enroll($user_id)) {
			$enrollment = $session->get_enrollment($user_id);
			
			if(isset($enrollment)) {
				$name = $enrollment->user->name;
				$enrollment->delete();	
				\Session::set_flash('success', __('session.alert.success.remove_enroll', ['name' => $name]));
			} else {
				\Utils::handle_recoverable_error(__('sesion.alert.error.no_enrollment', ['name' => '']));
			}	
		} else {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
		\Response::redirect_back();
	}
}

