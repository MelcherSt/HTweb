<?php
/**
 * Base controller for widgets controlling the look 'n feel of a widget.
 */
class Controller_Widget_Base extends \Controller {
	
	private $base_template = 'dashboard/widget';
	
	function __construct() {
		$this->template = new \stdClass();
	}
	
	public function before() {
		// Widgets should be requested via HMVC only
		if (!\Request::is_hmvc()) {
			throw new HttpNotFoundException();
		}
	
		// Default values
		$this->template->style = 'panel-primary';
		$this->template->icon = 'fa fa-bank';
		$this->template->count = 0;
		$this->template->kind = 'n/a';
		$this->template->message = 'Placeholder message';
		$this->template->detail = 'View details';
		$this->template->details = true;
		$this->template->link = '#';		
	}
	
	public function after($response) {
		return \Response::forge(\View::forge($this->base_template, get_object_vars($this->template))->render());
	}
}