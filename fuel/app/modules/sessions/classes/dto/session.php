<?php

namespace Sessions;

class Dto_Session {
	
	public $id;
	public $date;
	public $paid_by;
	public $deadline;
	public $notes;
	public $cost;
	
	public function __construct(\Sessions\Model_Session $session) {
		$this->id = $session->id;
		$this->date = $session->date;
		$this->paid_by = $session->paid_by;
		$this->deadline = $session->deadline;
		$this->notes = $session->notes;
		$this->cost = $session->cost;
	}
}