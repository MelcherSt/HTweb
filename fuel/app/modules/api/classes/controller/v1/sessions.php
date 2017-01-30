<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestPaginated {
	
	public function action_index() {
		
	}
	
	public function action_bycook($user_id) {
		$array = \Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->rows_offset($this->offset)
				->rows_limit($this->limit)
				->order_by('date', $this->order)
				->get();
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
		
	}
	
	public function action_byuser($user_id) {
		$array = \Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->rows_offset($this->offset)
				->rows_limit($this->limit)
				->order_by('date', $this->order)
				->get();
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);

	}
	
	public function action_admin() {
		
		$array = \Sessions\Model_Session::query()
				->where('settled', 0)
				->rows_offset($this->offset)
				->rows_limit($this->limit)
				->order_by('date', $this->order)
				->get();
	
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
		
	}
	
	public function after($array) {
		
		return parent::after([
			'total' => sizeof($array),
			'rows' => array_values($array)
		]);
	}
	
	
}

