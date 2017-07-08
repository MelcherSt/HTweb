<?php

namespace Session;

/**
 * Member DTO object for tables.
 */
class Dto_Session {
	public $date;
	public $participants;
	public $cooks;
	public $dishwashers;
	public $cost;
	
	public function __construct(\Sessions\Model_Session $model) {
		$this->date = $model->date;
		$this->participants = $model->count_total_participants();
		array_map(function($a) { return $a[0]; }, $model->get_cook_enrollments());
	}
}
