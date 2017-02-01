<?php

namespace Sessions;

class Controller_Admin extends \Controller_Gate {
	
	function before() {
		if(!\Auth::has_access('sessions.management')) {
			throw new \HttpNoAccessException();
		}
		parent::before();
	}
	
	public function action_index() {
		$this->push_css('bootstrap-table.min');
		$this->push_js(['admin/sessions-index', 'bootstrap-table.min']);
		
		$this->template->title = __('session.title_admin');
		$this->template->page_title = __('session.title_admin');
		$data['sessions'] = Model_Session::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
	
	public function action_view($date=null) {
		$this->push_css('jquery.timepicker-1.3.5.min', 'bootstrap-table.min');
		$this->push_js(['jquery.timepicker-1.3.5.min',
			'sessions-timepicker', 'admin/sessions-view', 'bootstrap-table.min']);
		
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
		} else {
			\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
		}
		
	}
}