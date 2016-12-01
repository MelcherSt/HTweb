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
	
	/* View a session or all sessions */
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


				$data['left_content'] = \View::forge('sessionstate', ["session"=>$session]);
				$data['right_content'] = 'right';
				
				//TODO: use presenters for views
				$this->template->subtitle = date('l j F Y', strtotime($date));
				$this->template->content = \View::forge('layout/splitview', $data);
			} else {
				//throw new \HttpNotFoundException();
			}	
		} else {
			// Show a list of sessions
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
				$session = Model_Session::find('first', array(
					'where' => array(
						array('date', $date))
				));
				
				if(!$session) {
					\Session::set_flash('error', e('Unable to join non-existant session.'));
					throw new \HttpNotFoundException();
				} 
								
				$enrollment = $session->current_enrollment();
				
				if(!$enrollment) {
					// Create one
					$enrollment = Model_Enrollment_Session::forge();
					$enrollment->user_id = \Auth::get_user()->id;
					$enrollment->session_id = $session->id;	
				}
				
				if(!$session->can_enroll()) {
					// Dishwashers can only enroll untill the end of the day
					if ($session->count_dishwashers() < Model_Session::MAX_DISHWASHER && (strtotime(date('Y-m-d H:i:s')) < strtotime($session->date . ' +1 day'))) {
						$dishwasher = \Input::post('dishwasher', false) == 'on' ? true : false;
						$enrollment->dishwasher = $dishwasher;
						$enrollment->save();
					} else {
						\Session::set_flash('error', e('Cannot add dishwasher.'));
					}
					
					\Response::redirect('/sessions/view/'.$date);
				}
					
				$cook = \Input::post('cook', false) == 'on' ? true : false;	
				$notes = \Input::post('notes', null);
				
				if($enrollment->cook) {
					$session->notes = $notes;
					$session->save();
				}
				
				$enrollment->dishwasher = false;
				$enrollment->cook = $cook;
				$enrollment->paid = \Input::post('paid', false);
				$enrollment->guests = \Input::post('guests', 0);
				$enrollment->save();
 				
				//\Session::set_flash('success', e('Successfully joined session'));
				\Response::redirect('/sessions/view/'.$date);
			}
		}
	}
	
	/**
	 * Responsible for deletion of enrollments
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
					\Session::set_flash('error', e('Unable to leave non-existant session.'));
					throw new \HttpNotFoundException();
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

