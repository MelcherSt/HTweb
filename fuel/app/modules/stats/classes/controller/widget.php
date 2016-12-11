<?php

namespace Stats;

class Controller_Widget extends \Controller_Widget_Base {
	
	public function action_points() {	
		$unsettled_points_delta = 0;
		$enrollments = \Sessions\Model_Enrollment_Session::get_unsettled();
		foreach($enrollments as $enrollment) {
			$unsettled_points_delta += $enrollment->get_point_prediction();
		}
		
		$this->template->style = '';
		$this->template->icon = 'fa-balance-scale';
		$this->template->notice = '';
		$this->template->message = '~' . $unsettled_points_delta . ' points to be settled';
		$this->template->kind = 'points';
		$this->template->count = \Model_User::find(\Auth::get_user_id()[1])->points;
		$this->template->details = false;
	}
}