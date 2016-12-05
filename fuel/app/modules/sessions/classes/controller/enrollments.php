<?php

namespace Sessions;

class Controller_Enrollments extends \Controller_Gate {
	
	public function action_index() {
		return \Response::forge('hoihoi');
	}
	
	public function post_update() {
		
	}
	
	/**
	 * Handle session un-enrolling
	 * @param type $date
	 */
	public function post_delete($date=null) {
		if(isset($date) && \Utils::valid_date($date)) {
			// Get model, if exists.
			$session = Model_Session::get_by_date($date);
			if(!$session) {
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
		\Utils::handle_irrecoverable_error('Date not set or invalid date format.');
	}
}

