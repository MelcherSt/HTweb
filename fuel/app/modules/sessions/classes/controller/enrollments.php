<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Gate {
	
	public function action_index() {
		return \Response::forge('Nothing to see here');
	}
	
	/**
	 * Handle enrollment creation
	 * @param type $date
	 */
	public function post_create($date=null) {
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error('Unable to update enrollment of non-existant session.');
			}
			
			$user_id = \Input::post('user_id', null);
			
			// Is the current user enrolled and creating an enrollment for someone else?
			$cur_enrollment = $session->current_enrollment();
			
			if(!isset($cur_enrollment)) {
				if (!$session->can_enroll()) {
					\Utils::handle_recoverable_error('Cannot enroll user outside enrollment boundaries', '/sessions/view/'.$date);
				}
				
				$user_id = \Auth::get_user()->id;
				$user = \Model_User::find($user_id);	
			} else {
				$user = \Model_User::find($user_id);
				
				// user_id was set, but we're in a special situation now
				if (!$cur_enrollment->cook && $session->can_change_enrollments()) {
					\Utils::handle_recoverable_error('Cannot enroll user outside enrollment boundaries', '/sessions/view/'.$date);
				} else if (!isset($user)) {
					\Utils::handle_recoverable_error('No user is associated with the given user id', '/sessions/view/'.$date);
				}
			} 
			
			// Create from model
			$enrollment = Model_Enrollment_Session::forge(array(
				'user_id' => $user->id,
				'session_id' => $session->id,
				'dishwasher' => \Input::post('dishwasher', false) == 'on' ? true : false,
				'cook' => \Input::post('cook', false) == 'on' ? true : false,
				'guests' => \Input::post('guests', 0),
			));
			
			// Save
			if($enrollment->save()) {
				\Session::set_flash('success', ('<strong>'. $user->name . '</strong> has been enrolled.'));
			} else {
				\Session::set_flash('error', ('Could not enroll <strong>' . $username . '</strong>'));	
			}
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error('Invalid date format or no date parameter.');
	}
	
	/**
	 * Handle enrollment updates
	 * @param type $date
	 */
	public function post_update($date=null) {
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error('Unable to update enrollment of non-existant session.');
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			
			if(isset($user_id) && $cur_enrollment->cook) {
				// Cook is enrolling another user
				$enrollment = $session->get_enrollment($user_id);	
				if (!$enrollment) {
					\Session::set_flash('error', ('There is no known enrollment for <strong>' . \Model_User::find($user_id)->name . '</strong> to be deleted.'));
					\Response::redirect('/sessions/view/'.$date);
				}
			} else {
				// Enrolling ourselves
				$enrollment = $cur_enrollment;
			}
			
			// Method is diswasher when using dishwasher button.
			// This case needs to be handled differently than normal update.
			$dishwasher_only = \Input::post('method') == 'dishwasher';
			
			if ($enrollment->cook && !$dishwasher_only && !isset($user_id)) {
				// Actually we're updating the session here 	
				if($session->can_change_cost()) {
						$cost = \Input::post('cost', 0.0);
						$session->cost = $cost;		
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
						\Session::set_flash('error', e('An error ocurred while updating the session. Please check your input.'));
						\Response::redirect('/sessions/view/'.$date);	
					}
			}	

			if(($session->can_enroll() || ($cur_enrollment->cook && $session->can_change_enrollments())) && !$dishwasher_only) {
				$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	
				$enrollment->guests = \Input::post('guests', 0);
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
			} else if(!$enrollment->cook) {
				\Session::set_flash('error', e('Cannot change enrollment of session past its deadline.'));			
			}

			if($session->can_enroll_dishwashers()) {
				$enrollment->dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;			
				\Session::delete_flash('error'); // Remove any errors
			}
			
			if($enrollment->save()) {
				//\Session::set_flash('success', ('<strong>'. $name . '</strong> has been enrolled.'));
			} else {
				\Session::set_flash('error', ('Could not enroll <strong>' . $name . '</strong>'));	
			}
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error('Invalid date format or no date parameter.');
	}
	
	/**
	 * Handle enrollment deletion
	 * @param type $date
	 */
	public function post_delete($date=null) {
		if(isset($date) && \Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error('Unable to unenroll out of non-existant session.');
			}
			
			$user_id = \Input::post('user_id', null);
			$cur_enrollment = $session->current_enrollment();
			
			if(isset($user_id) && $cur_enrollment->cook) {
				// Cook is unenrolling another user
				$enrollment = $session->get_enrollment($user_id);	
				if (!$enrollment) {
					\Session::set_flash('error', ('There is no known enrollment for <strong>' . \Model_User::find($user_id)->name . '</strong> to be deleted.'));
					\Response::redirect('/sessions/view/'.$date);
				}
			} else {
				// Unenrolling ourselves
				if(!$session->can_enroll()) {
					// User should not be able to enroll.
					\Session::set_flash('error', e('Cannot leave a session past its deadline.'));
					\Response::redirect('/sessions/view/'.$date);
				}
				$enrollment = $cur_enrollment;
			}
			
			// Remember the name
			$name = $enrollment->user->name;
			
			if($enrollment->delete()) {
				\Session::set_flash('success', ('<strong>'. $name . '</strong> has been unenrolled.'));
			} else {
				\Session::set_flash('error', ('Could not unenroll <strong>' . $name . '</strong>'));	
			}

			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error('Invalid date format or no date parameter.');
	}
}

