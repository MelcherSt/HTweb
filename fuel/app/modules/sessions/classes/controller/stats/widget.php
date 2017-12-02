<?php

namespace Sessions;

class Controller_Stats_Widget extends \Controller_Widget_Base {
	
	public function action_index() {	
		$settleable_points = 0;
		$unsettleable_points = 0;
		
		$user_id = \Auth::get_user_id()[1];
		$settleable_enrollments = \Sessions\Model_Enrollment_Session::get_settleable($user_id);
		$unsettleable_enrollments = \Sessions\Model_Enrollment_Session::get_unsettleable($user_id);
		
		foreach($settleable_enrollments as $enrollment) {
			$settleable_points += $enrollment->get_point_prediction(true);
		}
		
		foreach($unsettleable_enrollments as $enrollment) {
			$unsettleable_points += $enrollment->get_point_prediction();
		}
		
		$count = \Model_User::find($user_id)->points;
		$total = $count + $settleable_points + $unsettleable_points;
		
		$total_point_label = strtolower($total == 1 ? __('session.field.point') : __('session.field.point_plural'));
		$tentative_point_label = strtolower($unsettleable_points == 1 ? __('session.field.point') : __('session.field.point_plural'));
		
		
		$this->template->style = 'panel-primary ';
		$this->template->icon = 'fa-balance-scale';
		$this->template->notice = '';
		$this->template->message = __('session.stats.widget.msg.tentative', ['points' => $unsettleable_points.' '.$tentative_point_label]);
		$this->template->kind = $total_point_label;
		$this->template->count = $total;
		$this->template->link = '/sessions/stats';
		$this->template->detail = __('session.stats.widget.link');
	}
}