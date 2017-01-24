<?php
/**
 * Routed to by the router configuration. Currently only implementing HttpNotFound (404)
 */
class Controller_ErrorHandler extends \Controller_Base {
	
	public function action_404() {	
		$this->response_status = 404;
		$this->template->title = __('not_found');
		$this->template->page_title = __('not_found');
		$this->template->content = View::forge('errorhandler/404');
	}
	
	public function action_403() {	
		$this->response_status = 403;
		$this->template->title = __('no_access');
		$this->template->page_title = __('no_access');
		$this->template->content = View::forge('errorhandler/404');
	}
}