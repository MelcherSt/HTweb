<?php

namespace Sessions;

class Dto_SessionListItem {
	
	public $id;
	public $date;
	public $participants;
	public $cooks = "";
	public $dishwashers = "";
	public $cost;
	
	public function __construct(\Sessions\Model_Session $session) {
		$this->id = (int)$session->id;
		$this->date = $session->date;
		$this->participants = (int)$session->count_participants();
		$this->cost = $session->cost;
		
		foreach($session->get_cook_enrollments() as $cook) {
			$this->cooks .= $cook->user->name . ' ';
		}
		
		foreach($session->get_dishwasher_enrollments() as $dishwasher) {
			$this->dishwashers .= $dishwasher->user->name . ' ';
		}
	}
	
}