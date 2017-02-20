<?php

namespace Sessions;

class Controller_Sessions extends \Controller_Secure {
	
	public function action_index() {		
		Model_Session::scrub();
		
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
		$this->push_css('jquery.timepicker-1.3.5.min');
		$this->push_js(['jquery.timepicker-1.3.5.min', 'sessions-timepicker', 'sessions-modals']);
	
		$session = \Utils::valid_session($date, false);
		if(empty($session)){
			$today = (new \DateTime())->format('Y-m-d');
			if($date < $today) {
				// Stop creation of ready-closed sessions
				\Utils::handle_irrecoverable_error();
			}
			
			// Create session if none exists
			$session = Model_Session::forge([
				'deadline' => $date. ' ' . Model_Session::DEADLINE_TIME,
				'date' => $date,
			]);
			$session->save();
		} 
		
		if ($session->can_delay()) {
			// Automatically delay existing session
			$session->deadline = date('Y-m-d H:i:s', strtotime($session->deadline . '+1hour'));
			$session->save();
		}

		$formatted_date = strftime('%A %d %B %Y', strtotime($date));
		$this->template->page_title = __('session.name');
		$this->template->title = $formatted_date . ' - ' . __('session.title');
		$this->template->subtitle = $formatted_date;		
		$this->template->content = \View::forge('view', ['session' => $session]);
	}
	
	/**
	 * Handle session updates
	 * @param type $date
	 */
	public function post_update($date=null) {
		$session = \Utils::valid_session($date);
		$context = Context_Sessions::forge($session);	
		
		if(!$context->update()) {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
		
		$payer_id = \Input::post('payer-id', null);
		if(empty($payer_id)) {				
			$payer_id = \Auth::get_user()->id;
		} 
		
		// Cost has been updated 			
		$session->paid_by = $payer_id;
		$session->cost = \Input::post('cost', 0.0);

		$deadline = date($date. ' ' . \Input::post('deadline', Model_Session::DEADLINE_TIME));
		$session->deadline = $deadline;

		$notes = \Input::post('notes', '');		
		$session->notes = $notes;		

		try {
			\Security::htmlentities($session)->save();
			\Session::set_flash('success', __('session.alert.success.update_session'));
		} catch(\Database_Exception $ex) {
			\Utils::handle_recoverable_error(__('session.alert.error.update_session'));	
		}
		\Response::redirect_back();
	}
	
	/**
	 * Handle session removal
	 * @param string $date
	 */
	public function post_delete() {
		$session_id = \Input::post('session-id');
		$session = Model_Session::find($session_id);
		if(empty($session)) {
			\Utils::handle_recoverable_error(__('session.alert.error.remove_session'));
		}
		
		$context = Context_Sessions::forge($session);	
		
		if(!$context->delete()) {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
		
		try{
			$session->delete();
			\Session::set_flash('success', __('session.alert.success.remove_session'));
		} catch (Exception $ex) {
			\Utils::handle_recoverable_error(__('session.alert.error.remove_session'));	
		}
		\Response::redirect_back();
	}
}