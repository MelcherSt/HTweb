<?php

namespace Sessions;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$session = Model_Session::get_by_date(date('Y-m-d'));		
		
		$style = 'panel-green';
		$notice = '';
		
		if(isset($session)) {
			$count = $session->count_total_participants();	

			if($session->count_cooks() == 0) {
				//TODO: make this time depedant
				$style = 'panel-yellow';
				$notice = 'There is no cook yet.';
			}
		} else {
			$count = 0;
		}
		
		$this->template->style = $style;
		$this->template->count = $count;
		$this->template->kind = $count == 1 ? 'person' : 'people';
		$this->template->icon = 'fa-cutlery';
		$this->template->message = ' Will be eating today. ' . $notice;
		$this->template->detail = 'Click here to view';
		$this->template->link = 'sessions/today';
	}
}

