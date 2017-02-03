<?php

namespace Sessions;

class Controller_Sessions extends \Controller_Gate {
	
	public function action_index() {	
		$this->push_css('bootstrap-table.min');
		$this->push_js(['bootstrap-table.min', 'sessions-index']);
		Model_Session::scrub_empty_or_invalid();
		
		$this->template->title = __('session.title');
		$data['sessions'] = Model_Session::get_by_user(\Auth::get_user()->id);			
		$this->template->content = \View::forge('index', $data);
	}
	
	/* Some shortcuts */
	public function action_yesterday() {
		\Response::redirect('sessions/'.date('Y-m-d', strtotime('-1day')));
	}
	
	public function action_today() {
		\Response::redirect('sessions/'.date('Y-m-d'));
	}
	
	public function action_tomorrow() {
		\Response::redirect('sessions/'.date('Y-m-d', strtotime('+1day')));
	}
		
	/**
	 * View a session with given date
	 * @param type $date
	 */
	public function action_view($date=null) {
		$this->push_css(['jquery.timepicker-1.3.5.min', 'bootstrap-table.min']);
		$this->push_js(['jquery.timepicker-1.3.5.min', 'sessions-timepicker',
			'sessions-view', 'bootstrap-table.min', 'sessions']);
		
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
			
			/**
			 * This code automatically delays the session whenever the deadline is past-due
			 * and there is more than 1 enrollment, but no cook.
			 */
			if (Auth_SessionContext::forge($session)->can_delay()) {
				$session->deadline = date('Y-m-d H:i:s', strtotime($session->deadline . '+1hour'));
				$session->save();
			}

			$data['left_content'] = \View::forge('view', ['session'=>$session]);
			$data['right_content'] = \View::forge('participants', ['session'=>$session]);	
			$formatted_date = strftime('%A %d %B %Y', strtotime($date));
			
			$this->template->page_title = __('session.name');
			$this->template->title = $formatted_date . ' - ' . __('session.title');
			$this->template->subtitle = $formatted_date;		
			$this->template->content = \View::forge('layout/splitview', $data);
			return;
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
	
	/**
	 * Handle session updates
	 * @param type $date
	 */
	public function post_update($date=null) {
		if(\Utils::valid_date($date)) {
			if(!($session = Model_Session::get_by_date($date))) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$context = Auth_Context_Session::forge($session);
			$redirect = '/sessions/view/'.$date;
			
			if(!$context->has_access(['session.manage'], true)) {
				// Drop out
				\Utils::handle_recoverable_error($context->get_message(), $redirect);
			}

			if($context->has_access(['session.manage[cost]'])) {
				$new_cost = \Input::post('cost', 0.0);
				$cur_cost = $session->cost;

				$payer_id = \Auth::get_user()->id;	
				$payer_id_alt = \Input::post('payer_id', null);

				if(isset($payer_id_alt) && $context->has_access(['session.manage[payer]'])) {
					$payer_id = \Input::post('payer_id');
					$session->paid_by = $payer_id;
				}
				
				if ($new_cost != $cur_cost && $new_cost >= 0) {
					// Cost has been updated by this cook. Set him as payer.					
					$session->paid_by = $payer_id;
					$session->cost = $new_cost;	
				}		
			}
			
			if($context->has_access(['session.manage[deadline]'])) {
				$deadline = date($date. ' ' . \Input::post('deadline', Model_Session::DEADLINE_TIME));
				$session->deadline = $deadline;
			}
			
			if($context->has_access(['session.manage[notes]'])) {
				$notes = \Input::post('notes', '');		
				$session->notes = $notes;
			}

			try {
				$session->save();
				\Session::set_flash('success', __('session.alert.success.update_session'));
			} catch(\Database_Exception $ex) {
				\Utils::handle_recoverable_error(__('session.alert.error.update_session'), $redirect);	
			}
			\Response::redirect($redirect);
		}
		\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
	}
}