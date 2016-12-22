<?php

namespace Sessions;

class Controller_Sessions extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = __('session.title');
		$data['sessions'] = Model_Session::get_by_user(\Auth::get_user()->id);			
		$this->template->content = \View::forge('index', $data);
	}
	
	/* Some shortcuts */
	public function action_yesterday() {
		\Response::redirect('sessions/view/'.date('Y-m-d', strtotime('-1day')));
	}
	
	public function action_today() {
		\Response::redirect('sessions/view/'.date('Y-m-d'));
	}
	
	public function action_tomorrow() {
		\Response::redirect('sessions/view/'.date('Y-m-d', strtotime('+1day')));
	}
		
	/**
	 * View a session with given date
	 * @param type $date
	 */
	public function action_view($date=null) {
		$this->template->title = __('session.title');
		
		if (\Utils::valid_date($date)) {
			$session = Model_Session::get_by_date($date);

			if(empty($session)) {
				$session = Model_Session::forge([
					'deadline' => $date. ' ' . Model_Session::DEADLINE_TIME,
					'date' => $date,
				]);
				$session->save();
			}

			$context = Auth_Context_Session::forge($session, \Auth::get_user());
			echo $context->has_access(['enroll']);

			$enrollment = $session->current_enrollment();		
			if(!empty($enrollment)) {
				$data['left_content'] = \View::forge('state/enrolled', ['session'=>$session, 'enrollment' => $enrollment]);
			} else {
				$data['left_content'] = \View::forge('state/notenrolled', ['session'=>$session]);
			}

			$data['right_content'] = \View::forge('sessionparticipants', ['session'=>$session]);	

			$this->template->subtitle = strftime('%A %e %B %Y', strtotime($date));			
			$this->template->content = \View::forge('layout/splitview', $data);
			return;
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
	
	public function post_update($date=null) {
		if(\Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$context = Auth_Context_Session::forge($session, \Auth::get_user());
			$redirect = '/sessions/view/'.$date;
			
			if(!$context->has_access(['update_session'])) {
				// Drop out
				\Utils::handle_recoverable_error(__('session.alert.error.deadline_passed'), $redirect);
			}
			
			$enrollment = $session->current_enrollment();	
			

			if($context->has_access(['update_session[cost]'])) {
					$new_cost = \Input::post('cost', 0.0);
					$cur_cost = $session->cost;

				if ($new_cost != $cur_cost) {
					// Cost has been updated by this cook. Set him as payer.
					$session->paid_by = $enrollment->user->id;
					$session->cost = $new_cost;	
				}		
			}		
			if($context->has_access(['update_session[deadline]'])) {
				$deadline = date($date. ' ' . \Input::post('deadline', Model_Session::DEADLINE_TIME));
				$session->deadline = $deadline;
			}	
			if($context->has_access(['update_session[notes]'])) {
				$notes = \Input::post('notes', '');		
				$session->notes = $notes;
			}

			try {
				$session->save();
				\Session::set_flash('success', __('session.alert.success.update_session'));
			} catch(\Database_Exception $ex) {
				\Utils::handle_recoverable_error(__('session.alert.error.update_session'), $redirect);	
			}
			\Response::redirect('/sessions/view/'.$date);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
}