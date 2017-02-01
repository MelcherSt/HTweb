<?php

namespace Api;

class Controller_v1_Users extends Controller_RestPaginated {
		
	public function get_index() {		
		$query = \Model_User::query()
				->where('active', true);		
		
		$array = $this->paginate_query($query);
		
		return new Response_Paginated(array_map(function($item) {
				if($item instanceof \Model_User) { return new \Dto_UserListItem($item);	}
			}, $array[0]), $array[1]);
	}
	
	public function get_single($user_id) {
		return ['user' => new \Dto_User(\Model_User::find($user_id))];
	}

}

