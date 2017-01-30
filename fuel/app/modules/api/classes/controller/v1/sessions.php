<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestPaginated {
	
	public function action_index() {
		
	}
	
	public function action_admin() {
		
		$array = \Sessions\Model_Session::query()
				->where('settled', 0)
				->rows_offset($this->offset)
				->rows_limit($this->limit)
				->order_by('date', $this->order)
				->get();
	
		array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
		
		return [
			'total' => sizeof($array),
			'rows' => array_values($array)
		];
	}
	
	
}

