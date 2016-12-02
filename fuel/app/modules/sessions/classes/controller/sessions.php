<?php

namespace Sessions;

class Controller_Sessions extends \Controller_Gate {
	
	
	public function action_index() {
		\Response::redirect('sessions/view/');
	}
	
	/* Some shortcuts */
	public function action_yesterday() {
		\Response::redirect('sessions/view/'.date('Y-m-d', strtotime('-1 day')));
	}
	
	public function action_today() {
		\Response::redirect('sessions/view/'.date('Y-m-d'));
	}
	
	public function action_tomorrow() {
		\Response::redirect('sessions/view/'.date('Y-m-d', strtotime('+1 day')));
	}
	
	/**
	 * Show a list of session or a single one
	 * @param type $date
	 */
	public function action_view($date=null) {
		$this->template->title = 'Sessions';
		
		if(isset($date)) {
			if ($this->valid_date($date)) {
				// Get model, if exists.
				$session = Model_Session::find('first', array(
					'where' => array(
						array('date', $date))
				));

				// Or create it
				if(!$session) {
					$session = Model_Session::forge();
					$session->notes = '';
					$session->cost = '0.0';
					$session->deadline = date($date. ' ' . Model_Session::DEADLINE_TIME);
					$session->date = date($date);
					$session->settled = false;
					$session->save();
				}

				// Assign sub-views
				$data['left_content'] = \View::forge('sessionstate', ["session"=>$session]);
				$data['right_content'] = \View::forge('sessionparticipants', ["session"=>$session]);
				
				$this->template->subtitle = date('l j F Y', strtotime($date));
				$this->template->content = \View::forge('layout/splitview', $data);
			} else {
				$this->handle_error('Date not set or invalid date format.');
			}	
		} else {
			// TODO: Show a list of sessions
			$this->template->content = 'All sessions you participated in.';
		}	
	}
	
	/**
	 * Responsible for creation and updating session and enrollments
	 * @param type $date
	 * @throws \HttpNotFoundException
	 */
	public function post_enroll($date=null) {
		// Delegate deletes because DELETE is not well supported
		if(\Input::post('method') == 'delete') {
			$this->delete_enroll($date);
		}
		
		if(isset($date)) {
			if ($this->valid_date($date)) {
				// Get model, if exists.
				$session = Model_Session::get_by_date($date);
				
				if(!$session) {
					// There is no session
					$this->handle_error('Unable to join non-existant session.');
				}
								
				$enrollment = $session->current_enrollment();
				
				if(!isset($enrollment)) {
					// Create enrollment with defaults
					$enrollment = Model_Enrollment_Session::forge();
					$enrollment->user_id = \Auth::get_user()->id;
					$enrollment->session_id = $session->id;	
					$enrollment->dishwasher = false;
				}
								
				if ($session->can_enroll()) {
					if($enrollment->cook) {
						$notes = \Input::post('notes', '');
						$deadline = \Input::posts('deadline', Model_Session::DEADLINE_TIME);
						
						$session->notes = $notes;
						$session->save();
					}

					$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	
					$enrollment->paid = \Input::post('paid', false);
					$enrollment->guests = \Input::post('guests', 0);
					
				} else {
					\Session::set_flash('error', e('Cannot change enrollment of session past its deadline.'));			
				}
						
				if ($session->can_enroll_dishwashers()) {
					$dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
					$enrollment->dishwasher = $dishwasher;					
					\Session::delete_flash('error'); // Remove any errors
				} 
				
				// Save changes and redirect
				$enrollment->save();
				\Response::redirect('/sessions/view/'.$date);
			} 
		}
		
		$this->handle_error('Date not set or invalid date format.');
	}
	
	/**
	 * Deletes the user's enrollment for given session
	 * @param type $date
	 * @throws \HttpNotFoundException
	 */
	public function delete_enroll($date=null) {
		if(isset($date)) {
			if ($this->valid_date($date)) {
				// Get model, if exists.
				$session = Model_Session::find('first', array(
					'where' => array(
						array('date', $date))
				));
				
				if(!$session) {
					handle_error('Unable to leave non-existant session.');
				} 
				
				if(!$session->can_enroll()) {
					// User should not be able to enroll.
					\Session::set_flash('error', e('You cannot leave a session past its deadline.'));
					\Response::redirect('/sessions/view/'.$date);
				}
				
				$enrollment = $session->current_enrollment();
				
				if($enrollment) {
					$enrollment->delete();
				}
				
				\Session::set_flash('success', e('Successfully left session'));
				\Response::redirect('/sessions/view/'.$date);
			}
		}
		
		$this->handle_error('Date not set or invalid date format.');
	}
	
	/**
	 * Redirect to 404 with given message
	 * @param type $message
	 * @throws \HttpNotFoundException
	 */
	function handle_error($message=null) {
		if(isset($message)) {
			\Session::set_flash('error', e($message));
		}		
		throw new \HttpNotFoundException();
	} 
	
	
	/**
	 * Check if given string can be date formatted Y-m-d
	 * @param type $date
	 * @return boolean
	 */
	function valid_date($date) {
		$d = \DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}
}
