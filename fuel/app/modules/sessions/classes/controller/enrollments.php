<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Secure {
	
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
				
				try {
					$session->save();
				} catch (\Database_Exception $ex) {
					\Session::set_flash('error', __('session.alert.error.update_session') . '<br>' . $ex->getMessage());	
				}
				
			}
			
			$enrollment = $session->current_enrollment();
			$context = Context_Sessions::forge($session);
			
			if(empty($enrollment) && $context->create_enroll()) {
				$user = \Auth::get_user();
				$enrollment = Model_Enrollment_Session::forge([
					'user_id' => $user->id,
					'session_id' => $session->id,
				]);
				
				try {
					$enrollment->save();
				} catch (\Database_Exception $ex) {
					\Session::set_flash('error', __('session.alert.error.create_enroll', ['name' => $user->name]) . '<br>' . $ex->getMessage());	
				}
			}
		}
		\Response::redirect_back();
	}
	
	/**
	 * Handle enrollment creation
	 * @param type $date
	 */
	public function post_create($date=null) {		
		$session = \Utils::valid_session($date);
	
		// Run validation to validate amount of guests
		$val = Model_Enrollment_Session::validate('create');
		if($val->run()) {
			/* Input variables */
			$user_id = \Input::post('user-id', null);
			$guests = \Input::post('guests', null);
			$cook = \Input::post('cook') == 'on' ? true : false;
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			$later = \Input::post('later') == 'on' ? true : false;

			if(empty($user_id)) {
				$user_id = \Auth::get_user()->id;
			}
			
			$user = \Utils::valid_user($user_id);	
			
			$context = Context_Sessions::forge($session);	
			if($context->create_enroll($user_id)) {
				$view_enroll_create = $context->view_enroll_create();

				// Check if able to enroll cook and/or dishwasher
				$cook = $view_enroll_create[1] && $cook;
				$dishwasher = $view_enroll_create[2] && $dishwasher;	
			} else {
				\Utils::handle_recoverable_error(__('actions.no_perm'));
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
				\Session::set_flash('error', __('session.alert.error.create_enroll', ['name' => $user->name]) . '<br>' . $ex->getMessage());	
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
		$session = \Utils::valid_session($date);
		
		// Run validation to validate amount of guests
		$val = Model_Enrollment_Session::validate('update');
		if($val->run()) {	
			/* Input variables */
			$user_id = \Input::post('user-id', null);
			$guests = \Input::post('guests', 0);
			$cook = \Input::post('cook') == 'on' ? true : false;
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			$later = \Input::post('later') == 'on' ? true : false;

			if(empty($user_id)) {
				$user_id = \Auth::get_user()->id;
			}
			
			$user = \Utils::valid_user($user_id);	
			
			$context = Context_Sessions::forge($session);	
			if($context->update_enroll($user_id)) {
				$view_enroll_update = $context->view_enroll_update($user_id);
				
				// Check if able to change cook and/or dishwasher
				$cook = $view_enroll_update[1] && $cook;
				$dishwasher = $view_enroll_update[2] && $dishwasher;
			} else {
				\Utils::handle_recoverable_error(__('actions.no_perm'));
			}
					
			$enrollment = $session->get_enrollment($user_id);	
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
				\Session::set_flash('success', __('session.alert.success.update_enroll', ['name' => $user->name]));
			} catch (\Database_Exception $ex) {
				\Session::set_flash('error', __('session.alert.error.update_enroll', ['name' => $user->name]) . '<br>' . $ex->getMessage());	
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
		$user_id = \Input::post('user-id', \Auth::get_user()->id);	
		
		if ($context->delete_enroll($user_id)) {
			$enrollment = $session->get_enrollment($user_id);
			
			if(isset($enrollment)) {
				$name = $enrollment->user->name;
				
				try {
					$enrollment->delete();	
					\Session::set_flash('success', __('session.alert.success.remove_enroll', ['name' => $name]));
				} catch (\Database_Exception $ex) {
					\Session::set_flash('error', __('session.alert.error.remove_enroll', ['name' => $name]) . '<br>' . $ex->getMessage());	
				}	
			} else {
				\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => '']));
			}	
		} else {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
		\Response::redirect_back();
	}
}

