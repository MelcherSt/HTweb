<?php

namespace Sessions;

class Controller_Admin extends \Controller_Core_Theme {
	
	function before() {
		$this->permission = Context_Sessions::MGMT_PERM;
		parent::before();
	}
	
	public function action_index() {
		Model_Session::scrub();
		
		$this->push_js('admin/sessions-delete');
		
		$this->title = __('session.title_admin');
		$this->page_title = __('session.title_admin');
		$this->sub_title = __('privileges.perm.manage');		
		$this->content = \View::forge('admin/index');
	}
	
	
	public function action_view($date=null) {
		$this->push_css('jquery.timepicker-1.3.5.min');
		$this->push_js(['jquery.timepicker-1.3.5.min', 'sessions-timepicker', 'sessions-modals']);
		
		$session = \Utils::valid_session($date);	
		
		$formatted_date = strftime('%A %d %B %Y', strtotime($date));
		$this->page_title = __('session.name');
		$this->title = $formatted_date . ' - ' . __('session.title_admin');
		$this->sub_title = $formatted_date;		
		$this->content = \View::forge('admin/view', ['session' => $session]);	
	}
}