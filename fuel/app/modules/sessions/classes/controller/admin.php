<?php

namespace Sessions;

class Controller_Admin extends \Controller_Secure {
	
	function before() {
		$this->permission_required = Context_Sessions::MGMT_PERM;
		parent::before();
	}
	
	public function action_index() {
		Model_Session::scrub();
		
		$this->push_css('bootstrap-table.min');
		$this->push_js(['admin/sessions-delete', 'bootstrap-table.min']);
		
		$this->template->title = __('session.title_admin');
		$this->template->page_title = __('session.title_admin');
		$data['sessions'] = Model_Session::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
	
	
	public function action_view($date=null) {
		$this->push_css('jquery.timepicker-1.3.5.min');
		$this->push_js(['jquery.timepicker-1.3.5.min', 'sessions-timepicker', 'sessions-modals']);
		
		$session = \Utils::valid_session($date);	
		
		$formatted_date = strftime('%A %d %B %Y', strtotime($date));
		$this->template->page_title = __('session.name');
		$this->template->title = $formatted_date . ' - ' . __('session.title_admin');
		$this->template->subtitle = $formatted_date;		
		$this->template->content = \View::forge('admin/view', ['session' => $session]);	
	}
}