<?php
/**
 * Routed to by the router configuration. Currently only implementing HttpNotFound (404)
 */
class Controller_ErrorHandler extends \Controller_Base {
	
	public function action_404() {				
		$content = View::forge('errorhandler/404');
		return \Response::forge(\View::forge('template', ['title' => __('not_found'), 'page_title' => __('not_found'), 'content' => $content]), 404);
	}
}