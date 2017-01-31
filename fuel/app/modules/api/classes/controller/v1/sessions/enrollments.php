<?php

namespace Api;

class Controller_v1_Sessions_Enrollments extends Controller_RestPaginated {
	
	public function action_index($session_id=null) {	
		$query = \Sessions\Model_Enrollment_Session::query()
				->related('session')
				->where('session.settled', 0);
		
		if(isset($session_id)) {
			$query->where('session.id', $session_id);
		}
		
		$array = $this->paginate_query($query);
		
		return array_map(function($item) {
				return new \Sessions\Dto_SessionEnrollmentListItem($item);
			}, $array);	
	}
}