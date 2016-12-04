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
				$session = Model_Session::get_by_date($date);

				if(!$session) {
					$session = Model_Session::forge();
					$session->notes = '';
					$session->cost = '0.0';
					$session->paid_by = 0;
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
				$this->handle_irrecoverable_error('Date not set or invalid date format.');
			}	
		} else {
			// TODO: Show a list of sessions
			$this->template->content = \View::forge('index');
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
					$this->handle_irrecoverable_error('Session does not exist.');
				}
								
				$enrollment = $session->current_enrollment();
				
				if(!isset($enrollment)) {
					// Create enrollment with defaults
					$enrollment = Model_Enrollment_Session::forge();
					$enrollment->user_id = \Auth::get_user()->id;
					$enrollment->session_id = $session->id;	
					$enrollment->dishwasher = false;
				}
				
				if ($enrollment->cook && !(\Input::post('method') == 'dishwasher')) {
					// Don't update unposted values when enrolling for dishwasher
					// Update session		
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
								
				if($session->can_enroll()) {
					$enrollment->cook = \Input::post('cook', false) == 'on' ? true : false;	
					$enrollment->guests = \Input::post('guests', 0);			
				} else if(!$enrollment->cook) {
					\Session::set_flash('error', e('Cannot change enrollment of session past its deadline.'));			
				}
						
				if($session->can_enroll_dishwashers()) {
					$dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
					$enrollment->dishwasher = $dishwasher;					
					\Session::delete_flash('error'); // Remove any errors
				}
							
				// Save changes and redirect
				$enrollment->save();
				\Response::redirect('/sessions/view/'.$date);
			} 
		}
		
		$this->handle_irrecoverable_error('Date not set or invalid date format.');
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
		
		$this->handle_irrecoverable_error('Date not set or invalid date format.');
	}
	
	/**
	 * Redirect to 404 with given message
	 * @param type $message
	 * @throws \HttpNotFoundException
	 */
	function handle_irrecoverable_error($message=null) {
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

