<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestPaginated {
	
	public function action_index() {
		
	}
	
	public function action_bycook($user_id) {
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id));
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
		
	}
	
	public function action_byuser($user_id) {
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id));
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);

	}
	
	public function action_admin() {
		
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0));
	
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
		
	}
}

