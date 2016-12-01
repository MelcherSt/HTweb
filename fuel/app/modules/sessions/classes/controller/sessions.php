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
	 * Check if given string can be date formatted Y-m-d
	 * @param type $date
	 * @return boolean
	 */
	function valid_date($date) {
		$d = \DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
}
}

