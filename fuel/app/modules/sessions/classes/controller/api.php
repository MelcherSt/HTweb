<?php

namespace Tables;

class Controller_Api extends \Api\Controller_Paginated {	
	public function get_participated() : \Api\Response_Paginated {
		$query = \Model_User::query()
				->where('active', 1);	
		
		return $this->map_to_dto($this->paginate_query($query));
	}
	
	/**
	 * Map array of \Sessions\Model_Enrollment_Session to \Sessions\Dto_SessionEnrollmentListItem
	 * @param array $array \Sessions\Dto_SessionEnrollmentListItem 
	 * @return \Api\Response_Paginated
	 */
	private function map_to_dto($array) : \Api\Response_Paginated {
		return new \Api\Response_Paginated(array_map(function($item) {
				// Initialize DTO here
				return new Dto_Member($item);
			}, $array[0]), $array[1]);	
	}
}

