<?php

namespace Sessions;

class Dto_SessionRoles {
	
	public $max_cooks;
	public $max_dishwashers;
	public $cooks;
	public $dishwashers;
	
	public function __construct(\Sessions\Model_Session $session) {
		$this->max_cooks = \Sessions\Model_Session::MAX_COOKS;
		$this->max_dishwashers = \Sessions\Model_Session::MAX_DISHWASHER;
		$this->cooks = $session->count_cooks();
		$this->dishwashers = $session->count_dishwashers();
	}
}
