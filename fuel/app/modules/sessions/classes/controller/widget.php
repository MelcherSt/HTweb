<?php

namespace Sessions;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$session = Model_Session::get_by_date(date('Y-m-d'));		
		$style = 'panel-green';
		$icon = 'fa-cutlery';
		$notice = '';
		$detail = 'Why don\'t you join them?';
		$link = '/sessions/today';
		
		if(isset($session)) {
			$enrollment = $session->current_enrollment();
			$count = $session->count_total_participants();	
			
			if(isset($enrollment)) {
				$icon = 'fa-check-circle';
				$notice = $count == 1 ? 'And that\'s you!': 'And you are one of them!';	
				$detail = 'View today\'s session';
			} 
			
			if($count == 0) {
				$detail = 'Be the first!';
			}
			
			if($session->count_cooks() == 0 && ((int)date('Hi') > 1300)) {
				$style = 'panel-yellow';
				$notice = 'Uh, there is no cook yet.';
				$detail = 'Save the day, be a cook!';
			}	

			 if(!$session->can_enroll()) {
				$style = 'panel-grey';
				$notice = 'Today\'s session is now closed.';
				$detail = 'Enroll tomorrow?';
				$link = '/sessions/tomorrow';
			} 
			
		} else {
			// Surely, if there was no session there are no enrollments
			$count = 0;
			$detail = 'Be the first!';
		}
		
		if(date('w') == 2) {
			$icon = 'fa-users';
			$notice = 'It\'s kring time today.';
		}
		
		$this->template->style = $style;
		$this->template->count = $count;
		$this->template->kind = 'participant' . ($count == 1 ? '' : 's');
		$this->template->icon = $icon;
		$this->template->message =  $notice;
		$this->template->detail = $detail;
		$this->template->link = $link;
	}
}

