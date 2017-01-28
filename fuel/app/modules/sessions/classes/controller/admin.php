<?php

namespace Sessions;

class Controller_Admin extends \Controller_Gate {
	
	function before() {
		if(!\Auth::has_access('session.management')) {
			throw new \HttpNoAccessException();
		}
		
		parent::before();
	}
	
	
	public function action_index() {
		$this->push_js(['sessions-lang', 'admin/sessions-delete']);
		
		$this->template->title = __('session.title_admin');
		$this->template->page_title = __('session.title_admin');
		$data['sessions'] = Model_Session::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
	
	public function delete_index() {
		$id = \Input::delete('session_id', null);
		$session = Model_Session::find($id);
		
		if(isset($session)) {
			$session->delete();
		} else {
			throw new \HttpNotFoundException();
		}
		return \Response::forge('', 204);
	}
	
	public function put_index($id=null) {
		$session = Model_Session::find($id);

		if(empty($session)) {
			throw new \HttpNotFoundException();
		} 
		
		
		$session->notes = \Input::put('notes', '');
		$session->deadline = date(date('Y-m-d'). ' ' . \Input::put('deadline', Model_Session::DEADLINE_TIME));
		$session->cost = \Input::put('cost', 0.0);
		$session->paid_by = \Input::put('payer_id', null);
		$session->save();
		
		return \Response::forge('', 200);
	}
	
	public function action_view($date=null) {
		$this->push_css('jquery.timepicker-1.3.5.min');
		$this->push_js(['jquery.timepicker-1.3.5.min',
			'sessions-lang', 'sessions-timepicker', 'admin/sessions-delete']);
		
		
		if (\Utils::valid_date($date)) {
			$session = Model_Session::get_by_date($date);

			if(empty($session)) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$formatted_date = strftime('%A %d %B %Y', strtotime($date));
			$this->template->page_title = __('session.name');
			$this->template->title = $formatted_date . ' - ' . __('session.title_admin');
			$this->template->subtitle = $formatted_date;		
			$this->template->content = \View::forge('admin/view', ['session' => $session]);
		}
	}
}