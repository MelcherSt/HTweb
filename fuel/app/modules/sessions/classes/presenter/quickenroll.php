<?php
namespace Sessions;

class Presenter_QuickEnroll extends \Presenter {
	
	public function view() {
		$now = new \DateTime();
		$date = new \DateTime();
		$day_of_week = $date->format('w');
		$week_start = $date->modify('-' . ($day_of_week + 1) . 'days');

		$quick_enrolls = [];
		
		// Print 7 days
		for ($i = 0; $i < (7 + $day_of_week); $i++) { 
			$day = $week_start->modify('+1day');
			$date = $day->format('Y-m-d');
			if($day < $now) { continue; }

			$session = \Sessions\Model_Session::get_by_date($date);
			$enrollment = empty($session) ? null : $session->current_enrollment();
			$quick_enrolls[$day->format('Y-m-d')] = $enrollment;
		}
		$this->set('enrollments', $quick_enrolls);
	}
}