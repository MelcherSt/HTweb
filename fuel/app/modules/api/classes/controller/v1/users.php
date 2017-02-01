<?php

namespace Api;

class Controller_v1_Users extends Controller_RestPaginated {
		
	public function get_index() {		
		$array = $this->paginate_query(\Model_User::query());
		
		return array_map(function($item) {
				return new \Dto_User($item);
			}, $array);	
	}
	
	public function get_single($user_id) {
		return ['user' => new \Dto_User(\Model_User::find($user_id))];
	}

}

