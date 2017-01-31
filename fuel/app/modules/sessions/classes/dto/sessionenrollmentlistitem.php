<?php

namespace Sessions;

class Dto_SessionEnrollmentListItem {
	
	public $id;
	public $session_id;
	public $name;
	public $points;
	public $guests;
	
	public function __construct(\Sessions\Model_Enrollment_Session $enrollment) {
		$this->id = $enrollment->id;
		$this->session_id = $enrollment->session_id;
		$this->name = \Model_User::find($enrollment->user_id)->name;
		$this->points = $enrollment->get_point_prediction();
		$this->guests = $enrollment->guests;
	}
}