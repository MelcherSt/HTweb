<?php

namespace Stats;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_points() {	
		$unsettled_points_delta = 0;
		$user_id = \Auth::get_user_id()[1];
		$enrollments = \Sessions\Model_Enrollment_Session::get_ready_for_settlement($user_id);
		foreach($enrollments as $enrollment) {
			$unsettled_points_delta += $enrollment->get_point_prediction();
		}
		
		$count = \Model_User::find($user_id)->points;
		
		$this->template->style = '';
		$this->template->icon = 'fa-balance-scale';
		$this->template->notice = '';
		$this->template->message = '~' . $unsettled_points_delta . ' ' . strtolower(__('session.field.point_plural')) . ' ' . __('stats.widget');
		$this->template->kind = strtolower($count == 1 ? __('session.field.point') : __('session.field.point_plural'));
		$this->template->count = $count;
		$this->template->details = false;
	}
}