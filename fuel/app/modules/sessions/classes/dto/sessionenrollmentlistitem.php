<?php

namespace Sessions;

class Dto_SessionEnrollmentListItem {
	
	public $user_id;
	public $session_id;
	public $user;
	public $points;
	public $guests;
	public $cook;
	public $dishwasher;
	public $later;
	
	public function __construct(\Sessions\Model_Enrollment_Session $enrollment) {
		$this->user_id = (int)$enrollment->user_id;
		$this->session_id = (int)$enrollment->session_id;
		$this->user = new \Dto_UserListItem(\Model_User::find($enrollment->user_id));
		$this->points = $enrollment->get_point_prediction();
		$this->guests = (int)$enrollment->guests;
		$this->cook = (int)$enrollment->cook;
		$this->dishwasher = (int)$enrollment->dishwasher;
		$this->later = (int)$enrollment->later;
	}
}