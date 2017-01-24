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
		$this->template->title = __('session.title_admin');
		$this->template->page_title = __('session.title_admin');
		$data['sessions'] = Model_Session::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
	
	public function action_view($date=null) {
		
		if (\Utils::valid_date($date)) {
			$session = Model_Session::get_by_date($date);

			if(empty($session)) {
				\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
			}
			
			$formatted_date = strftime('%A %e %B %Y', strtotime($date));
			$this->template->page_title = __('session.name');
			$this->template->title = $formatted_date . ' - ' . __('session.title_admin');
			$this->template->subtitle = $formatted_date;		
			$this->template->content = \View::forge('admin/view', ['session' => $session]);
		}
	}
}