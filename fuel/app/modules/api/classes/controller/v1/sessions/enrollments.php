<?php

namespace Api;

class Controller_v1_Sessions_Enrollments extends Controller_RestPaginated {
	
	/**
	 * Enrollment listing
	 * @return \Api\Response_Base
	 */
	public function get_index($session_id=null) : \Api\Response_Base{
		$query = \Sessions\Model_Enrollment_Session::query()
				->related('session')
				->where('session.settled', 0);
		
		if(isset($session_id)) {
			$query->where('session.id', $session_id);
		}
		
		$result = $this->paginate_query($query);
		if(sizeof($result) == 0) {
			return Response_Status::_404();
		}
		
		return $this->map_to_dto($result);
	}
	
	/**
	 * Single enrollment
	 * @param int $session_id
	 * @return mixed \Api\Response_Base
	 */
	public function get_single($session_id=null) : \Api\Response_Base {		
		$user_id = $this->param('user_id');	
		$session = \Sessions\Model_Session::find($session_id);
		$enrollment = \Sessions\Model_Enrollment_Session::get_by_user($user_id);
		
		if(isset($session) && isset($enrollment)) {
			if($enrollment->session_id == $session->id) {
				return new \Sessions\Dto_SessionEnrollmentListItem($enrollment);
			} else {
				return Response_Status::_400();
			}
		}
		return Response_Status::_404();
	}
	
	public function delete_single($session_id=null) {
		$user_id = $this->param('user_id');		
		$session = \Sessions\Model_Session::find($session_id);
		$enrollment = \Sessions\Model_Enrollment_Session::get_by_user($user_id);
		
		if(isset($session) && isset($enrollment)) {
			if($enrollment->session_id == $session->id) {
				//return new \Sessions\Dto_SessionEnrollmentListItem($enrollment);
				// TODO : auth & delete
			} else {
				return Response_Status::_400();
			}
		}
		return Response_Status::_404();
	}
	
	/**
	 * Map array of \Sessions\Model_Enrollment_Session to \Sessions\Dto_SessionEnrollmentListItem
	 * @param array $array \Sessions\Dto_SessionEnrollmentListItem 
	 * @return \Api\Response_Paginated
	 */
	private function map_to_dto($array) : \Api\Response_Paginated {
		return new Response_Paginated(array_map(function($item) {
				if($item instanceof \Sessions\Model_Enrollment_Session ) { return new \Sessions\Dto_SessionEnrollmentListItem($item); }
			}, $array));	
	}
}