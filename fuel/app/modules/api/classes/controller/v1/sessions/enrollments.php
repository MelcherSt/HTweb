<?php

namespace Api;

class Controller_v1_Sessions_Enrollments extends Controller_RestPaginated {
	
	/**
	 * Enrollment listing
	 * @return \Api\Response_Base
	 */
	public function get_index($session_id=null) : \Api\Response_Base{
		$query = \Sessions\Model_Enrollment_Session::query()
				->related('session');
		
		if(isset($session_id)) {
			$query->where('session.id', $session_id);
		}
		
		if(empty(\Sessions\Model_Session::find($session_id))) {
			return Response_Status::_404();
		}
 		
		$result = $this->paginate_query($query);
		
		return $this->map_to_dto($result);
	}
	
	/**
	 * Single enrollment
	 * @param int $session_id
	 * @return mixed 
	 */
	public function get_single($session_id=null){		
		$user_id = $this->param('user_id');	
		$session = \Sessions\Model_Session::find($session_id);
		$enrollment = $session->get_enrollment($user_id);
		
		if(isset($session) && isset($enrollment)) {
			if($enrollment->session_id == $session->id) {
				return new \Sessions\Dto_SessionEnrollmentListItem($enrollment);
			} else {
				return Response_Status::_400();
			}
		}
		return Response_Status::_404();
	}
	
	/**
	 * Delete enrollment for given user from session
	 * @param int $session_id
	 * @return type
	 */
	public function delete_single($session_id=null) {
		$user_id = $this->param('user_id');		
		$session = \Sessions\Model_Session::find($session_id);
		$enrollment = $session->get_enrollment($user_id);	
		if(isset($session) && isset($enrollment)) {
			$context = new \Sessions\SessionContext($session);
			if ($context->can_enrollment(\Auth_PermissionType::DELETE, $user_id)) {				
				$enrollment->delete();		
				return null; // Nothing to return			
			} else {
				return Response_Status::_405();
			}	
		}
		return Response_Status::_404();
	}
	
	
	public function post_index($session_id=null) {
		$session = \Sessions\Model_Session::find($session_id);
		
		if(isset($session)) {
			$context = new \Sessions\SessionContext($session);
			
			// Booleans indication if we're at max
			$max_cooks = $session->count_cooks() == \Sessions\Model_Session::MAX_COOKS;
			$max_dish = $session->count_dishwashers() == \Sessions\Model_Session::MAX_DISHWASHER;
			
			$user_id = \Input::post('user_id');
			$guests = \Input::post('guests', 0);
			$cook = \Input::post('cook') == 'on' ? true : false;
			$dishwasher = \Input::post('dishwasher') == 'on' ? true : false;
			$later = \Input::post('later') == 'on' ? true : false;
			
			if(empty(\Model_User::find($user_id))) {
				return Response_Status::_422(': invalid user id in data');
			}
			
			if($guests > \Sessions\Model_Session::MAX_GUESTS) {
				return Response_Status::_422(': exceeded maximum amount of guests');
			}
			
			// create new enrollment
			if ($context->can_enrollment(\Auth_PermissionType::CREATE, $user_id)) {				
				if(($cook && $max_cooks) || ($dishwasher && $max_dish)) {
					// Can only have so much cooks/dishwashers
					return Response_Status::_422(': reached maximum amount of cooks and/or dishwashers');
				}

				$enrollment = \Sessions\Model_Enrollment_Session::forge([
					'user_id' => $user_id,
					'session_id' => $session->id,
					'later' => $later,
					'dishwasher' => $dishwasher,
					'cook' => $cook,
					'guests' => $guests,
				]);
				$enrollment->save();	
				return null;
			} else {
				return Response_Status::_405();
			}
		} 
		return Response_Status::_404();
	}
	
	/**
	 * Create / update enrollment for given user for session
	 * @param int $session_id
	 */
	public function put_single($session_id=null) {
		$user_id = $this->param('user_id');		
		$session = \Sessions\Model_Session::find($session_id);
		$enrollment = $session->get_enrollment($user_id);	
		
		if(isset($session)) {
			$context = new \Sessions\SessionContext($session);
			
			// Booleans indication if we're at max
			$max_cooks = $session->count_cooks() == \Sessions\Model_Session::MAX_COOKS;
			$max_dish = $session->count_dishwashers() == \Sessions\Model_Session::MAX_DISHWASHER;
			
			$guests = \Input::put('guests', 0);
			$cook = \Input::put('cook') == 'on' ? true : false;
			$dishwasher = \Input::put('dishwasher') == 'on' ? true : false;
			$later = \Input::put('later') == 'on' ? true : false;
			
			if($guests > \Sessions\Model_Session::MAX_GUESTS) {
				return Response_Status::_422(': exceeded maximum amount of guests');
			}
			
			if(isset($enrollment)) {
				// update enrollment
				if ($context->can_enrollment(\Auth_PermissionType::UPDATE, $user_id)) {			
					if((!$enrollment->cook && $cook && $max_cooks) || (!$enrollment->dishwasher && $dishwasher && $max_dish)) {
						// Can only have so much cooks/dishwashers
						return Response_Status::_422(': reached maximum amount of cooks and/or dishwashers');
					}
					
					$enrollment->dishwasher = $dishwasher;
					$enrollment->cook = $cook;
					$enrollment->guests = $guests;
					$enrollment->later = $later;
					$enrollment->save();
					return [$cook, $dishwasher];
				} else {
					return Response_Status::_405();
				}
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
			}, $array[0]), $array[1]);	
	}
}