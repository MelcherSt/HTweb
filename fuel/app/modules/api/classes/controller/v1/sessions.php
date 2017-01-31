<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestPaginated {
	
	/**
	 * Session listing where user cooked.
	 * @param type $user_id
	 * @return type
	 */
	public function action_bycook($user_id) {
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->where('enrollments.cook', 1));
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);	
	}
	
	/**
	 * Session listing by user, but where user did not cook.
	 * @param type $user_id
	 * @return type
	 */
	public function action_byothers($user_id) {
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->where('enrollments.cook', 0));
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
	}
	
	/**
	 * Session listing by user
	 * @param type $user_id
	 * @return type
	 */
	public function action_byuser($user_id) {
		$array = $this->paginate_query(\Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id));
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
	}
	
	/**
	 * Session listing
	 * @param type $session_id
	 * @return type
	 */
	public function action_index($session_id=null) {
		$query = \Sessions\Model_Session::query()
				->where('settled', 0);
		
		if(isset($session_id)) {
			$query = $query->where('id', $session_id);
		}
		
		$array = $this->paginate_query($query);
	
		return array_map(function($item) {
				return new \Sessions\Dto_SessionListItem($item);
			}, $array);
	}
	
	/**
	 * Single session
	 * @param type $session_id
	 * @return type
	 */
	public function action_single($session_id) {
		return ['session' => new \Sessions\Dto_Session(\Sessions\Model_Session::find($session_id))];
				
	}
}

