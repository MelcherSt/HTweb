<?php

namespace Sessions;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$session = Model_Session::get_by_date(date('Y-m-d'));		
		$style = 'panel-green';
		$notice = '';
		$message = ' Are enrolled. ';
		$detail = 'Click here to view';
		$link = '/sessions/today';
		
		if(isset($session)) {
			$count = $session->count_total_participants();	
	
			if(!$session->can_enroll()) {
				$style = 'panel-grey';
				$message = 'Deadline past due.';
				$detail = 'Enroll tomorrow?';
				$link = '/sessions/tomorrow';
			} else {
				if($session->count_cooks() == 0) {
					//TODO: make this time depedant
					$style = 'panel-yellow';
					$notice = 'There is no cook yet.';
					$detail = 'Save the day, be a cook';
				}
			}		
		} else {
			// Surely, if there was no session there are no enrollments
			$count = 0;
		}
		
		$this->template->style = $style;
		$this->template->count = $count;
		$this->template->kind = $count == 1 ? 'person' : 'people';
		$this->template->icon = 'fa-cutlery';
		$this->template->message =  $message . $notice;
		$this->template->detail = $detail;
		$this->template->link = $link;
	}
}

