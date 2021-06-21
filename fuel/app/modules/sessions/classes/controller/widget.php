<?php

namespace Sessions;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$session = Model_Session::get_by_date(date('Y-m-d'));
		
		$style = 'panel-green';
		$icon = 'fa-cutlery';
		$message = '';
		$link_text = __('session.widget.link.enroll_many');
		$link = '/sessions/today';
		
		if(isset($session)) {
			$context = Context_Sessions::forge($session);
			$enrollment = $session->current_enrollment();
			$count = $session->count_total_participants();	
			
			if(isset($enrollment)) {
				$icon = 'fa-check-circle';
				$message = $count == 1 ? __('session.widget.msg.enrolled_single') : __('session.widget.msg.enrolled_many');	
				$link_text = __('session.widget.link.today');
			} 
			
			if($count == 0) {
				$link_text = __('session.widget.link.enroll_first');
			}
			
			if($session->count_cooks() == 0 && ((int)date('Hi') > 1300)) {
				$style = 'panel-yellow';
				$message = __('session.widget.msg.no_cook');
				$link_text = __('session.widget.link.no_cook');
			}	

			 if(!$context->create_enroll() && !$session->should_postpone()) {
				$style = 'panel-grey';
				$message = __('session.widget.msg.deadline_passed');
				$link_text = __('session.widget.link.deadline_passed');
				$link = '/sessions/today';
			} 
			
		} else {
			// Surely, if there was no session there are no enrollments
			$count = 0;
			$link_text = __('session.widget.link.enroll_first');
		}
		
		if(date('w') == 2) {
			$icon = 'fa-users';
		}
		
		$this->template->style = $style;
		$this->template->count = $count;
		$this->template->kind = strtolower($count == 1 ? __('session.role.participant') : __('session.role.participant_plural'));
		$this->template->icon = $icon;
		$this->template->message =  $message;
		$this->template->detail = $link_text;
		$this->template->link = $link;
	}
}

