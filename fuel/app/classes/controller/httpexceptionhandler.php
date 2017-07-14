<?php
/**
 * Routed to by the router configuration.
 */
class Controller_HttpExceptionHandler extends \Controller_Core_Theme {
	
	public function before() {
		/* 
		 * After an exception is thrown localization might be messed up.
		 * This forces the localization files to be reloaded to prevent problems.
		 */
		static::load_localization(true);
		
		$this->public_content = true;
		parent::before();
	}
	
	public function action_404(\FuelException $e) {	
		$this->response_status = 404;
		$this->page_title = __('404.title');
		
		if(isset($e) && !empty($e->getMessage())) {
			$message = __('404.msg_exception', ['msg' => $e->getMessage()]);
		} else {
			$message = __('404.msg');
		}	
		$this->content = View::forge('errorpage', ['message' => $message], false);
	}
	
	public function action_403() {	
		$this->response_status = 403;
		$this->page_title = __('403.title');
		$this->content = View::forge('errorpage', ['message' => __('403.msg')], false);
	}
}