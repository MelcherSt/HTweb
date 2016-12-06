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
			if (\Utils::valid_date($date)) {
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
				\Utils::handle_irrecoverable_error('Date not set or invalid date format.');
			}	
		} else {
			// TODO: Show a list of sessions
			$this->template->content = \View::forge('index');
		}	
	}
}