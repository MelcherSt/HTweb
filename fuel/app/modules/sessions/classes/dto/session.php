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
		$this->id = (int)$session->id;
		$this->date = $session->date;
		$this->paid_by = (int)$session->paid_by;
		$this->deadline = $session->deadline;
		$this->notes = $session->notes;
		$this->cost = $session->cost;
	}
}