<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestPaginated {
	
	/**
	 * Session listing where user cooked.
	 * @param int $user_id
	 * @return \Api\Response_Base
	 */
	public function get_bycook($user_id) : \Api\Response_Base {
		$query = \Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->where('enrollments.cook', 1);		
		return $this->map_to_dto($this->paginate_query($query));
	}
	
	/**
	 * Session listing by user, but where user did not cook.
	 * @param int $user_id
	 * @return \Api\Response_Base
	 */
	public function get_byothers($user_id) : \Api\Response_Base {
		$query = \Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id)
				->where('enrollments.cook', false);	
		return $this->map_to_dto($this->paginate_query($query));
	}
	
	/**
	 * Session listing by user
	 * @param int $user_id
	 * @return \Api\Response_Base
	 */
	public function get_byuser($user_id) : \Api\Response_Base {
		$query = \Sessions\Model_Session::query()
				->where('settled', 0)
				->related('enrollments')
				->where('enrollments.user_id', $user_id);
		return $this->map_to_dto($this->paginate_query($query));
	}
	
	/**
	 * Session listing
	 * @return \Api\Response_Base
	 */
	public function get_index() : \Api\Response_Base {
		$query = \Sessions\Model_Session::query()
				->where('settled', 0);		
		
		return $this->map_to_dto($this->paginate_query($query));
	}
	
	/**
	 * Single session
	 * @param int $session_id
	 * @return mixed 
	 */
	public function get_single(int $session_id) : \Sessions\Dto_Session {		
		$session = \Sessions\Model_Session::find($session_id);		
		if (isset($session)) {
			return new \Sessions\Dto_Session($session);
		} else {
			return Response_Status::_404();
		}		
	}
	
	/**
	 * Delete a session
	 * @param int $session_id
	 * @return mixed
	 */
	public function delete_single(int $session_id) {
		$session = \Sessions\Model_Session::find($session_id);	
		if (isset($session)) {
			$context = new \Sessions\SessionContext($session);
			if ($context->can_session(\Auth_PermissionType::DELETE)) {			
				$session->delete();			
				return null; // Nothing to return			
			} else {
				return Response_Status::_403();
			}
		} else {
			return Response_Status::_404();
		}		
	}
	
	/**
	 * Update session
	 * @param int $session_id
	 * @return mixed
	 */
	public function put_single(int $session_id) {		
		$session = \Sessions\Model_Session::find($session_id);
		if (isset($session)) {
			$context = new \Sessions\SessionContext($session);
			if ($context->can_session(\Auth_PermissionType::UPDATE)) {		
				$session->notes = \Input::put('notes', '');
				$session->deadline = date(date('Y-m-d'). ' ' . \Input::put('deadline', \Sessions\Model_Session::DEADLINE_TIME));
				$session->cost = \Input::put('cost', 0.0);
				$session->paid_by = \Input::put('payer_id', null);
				$session->save();		
				return null; // Nothing to return		
			} else {
				return Response_Status::_403();
			}
		} else {
			return Response_Status::_404();
		}		
	}
	
	/**
	 * Map array of \Sessions\Model_Session to \Sessions\Dto_SessionListItem
	 * @param array $array \Sessions\Model_Session 
	 * @return \Api\Response_Paginated
	 */
	private function map_to_dto($array) : \Api\Response_Paginated {
		return new Response_Paginated(array_map(function($item) {
				if($item instanceof \Sessions\Model_Session) { return new \Sessions\Dto_SessionListItem($item);	}
			}, $array));
	}
}

