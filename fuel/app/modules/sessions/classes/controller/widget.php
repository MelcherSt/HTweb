<?php

namespace Sessions;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$session = Model_Session::get_by_date(date('Y-m-d'));		
		$style = 'panel-green';
		$icon = 'fa-cutlery';
		$notice = '';
		$detail = 'Are you enrolled yet?';
		$link = '/sessions/today';
		
		if(isset($session)) {
			$count = $session->count_total_participants();	
	
			if(!$session->can_enroll()) {
				$style = 'panel-grey';
				$notice = 'Deadline past due.';
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
		
		if(date('w') == 2) {
			$icon = 'fa-users';
			$notice = 'It\'s kring time today.';
		}
		
		$this->template->style = $style;
		$this->template->count = $count;
		$this->template->kind = $count == 1 ? 'person' : 'people';
		$this->template->icon = $icon;
		$this->template->message =  ($count == 1 ? 'is' : 'are') . ' enrolled. '. $notice;
		$this->template->detail = $detail;
		$this->template->link = $link;
	}
}

