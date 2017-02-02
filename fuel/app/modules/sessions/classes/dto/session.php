<?php

namespace Sessions;

class Dto_Session {
	
	public $id;
	public $date;
	public $payer;
	public $deadline;
	public $notes;
	public $cost;
	public $participants;
	public $guests;
	
	public function __construct(\Sessions\Model_Session $session) {
		$this->id = (int)$session->id;
		$this->date = $session->date;
		$this->payer = new \Dto_UserListItem(\Model_User::find($session->paid_by));
		$this->deadline = date('H:i', strtotime($session->deadline));
		$this->notes = $session->notes;
		$this->cost = $session->cost;
		$this->participants = (int)$session->count_total_participants();
		$this->guests = (int)$session->count_guests();
	}
}