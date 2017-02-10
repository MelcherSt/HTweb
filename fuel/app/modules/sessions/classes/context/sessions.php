<?php

namespace Sessions;

final class Context_Sessions {
	
	private $session;
	private $user;
	private $cur_enrollment;
	
	/**
	 * Name of the ORMAuth permission granting complete administration privileges.
	 * @var type 
	 */
	private $mgmt_perm_name = 'sessions.administration';
	
	const DEADLINE_GRACE = '+19hours';
	const ENROLLMENT_GRACE = '+5days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	const MAX_COOKS = 1;
	const MAX_DISHWASHER = 2;
	
	private function __construct(Model_Session $session, \Model_User $user) {
		$this->session = $session;
		$this->user = $user;
		$this->cur_enrollment = $session->get_enrollment($user->id);
	}
	
	/**
	 * Construct new Session context
	 * @param \Sessions\Model_Session $session
	 * @param \Model_User $user
	 * @return \Sessions\Context_Sessions
	 */
	public static function forge(Model_Session $session, \Model_User $user=null) : Context_Session {
		if(empty($user)) {
			$user = \Model_User::find(\Auth::get_user()->id);
		}	
		return new Context_Sessions($session, $user);
	}
	
	public function create() {
		// Sessions are created by the system, not manually.
		return false;
	}
	
	public function edit() {	
		if ($this->_is_settled()) {
			// A settled session may never be edited
			return false;
		}
		
		if($this->_is_administrator()) {
			// Admin may always edit
			return true;
		} else {
			// Cook may edit until 4 days after
			return $this->_is_cook() && $this->_in_extended_enrollment_period();
		}
	}
	
	public function view() {
		// Everyone may view
		return true;
	}
	
	public function delete() {
		if ($this->_is_settled()) {
			// A settled session may never be deleted
			return false;
		}
		
		// Only admin can delete a session
		return $this->_is_administrator();
	}
		
	public function create_enroll($user_id=null) {
		if(empty($user_id)) {
			$self = true;
		} else {
			$self = $this->user->id == $user_id;
		}
		
		if ($this->_is_settled()) {
			// Cannot add enroll to settled session
			return false;
		}
		
		if($this->_is_administrator()) {
			// Admin may always create enroll
			return true;
		}
		
		if($self) {
			// Enroll ourself
			if($this->_is_cook()) {
				return $this->_in_normal_enrollment_period() || $this->_in_extended_enrollment_period();
			} else {
				return $this->_in_normal_enrollment_period();
			}	
		} else {
			// Enroll someone else
			return $this->_is_cook() && $this->_in_extended_enrollment_period();	
		}
	}
	
	public function edit_enroll($user_id=null) {
		return $this->create_enroll($user_id);
	}
	
	public function view_enroll() {
		return true;
	}
	
	public function delete_enroll($user_id=null) {
		return $this->create_enroll($user_id);
	}
	
	public function show_create_enroll() {
		$result = [];
		
		array_push($this->_in_normal_enrollment_period(), $result);
		array_push($this->session->count_cooks() != static::MAX_COOKS, $result);
		
		//[show panel, show cook]
	}
	
	/**
	 * Has the current user administration privileges
	 * @return boolean
	 */
	private function _is_administrator(){
		return \Auth::has_access($this->mgmt_perm_name);
	}
	
	/**
	 * Has the current user role cook
	 */
	private function _is_cook() {
		if(isset($this->cur_enrollment)) {
			return $this->cur_enrollment->cook;
		}
		return false;
	}
	
	/**
	 * Has the session been settled
	 */
	private function _is_settled() {
		return $this->session->settled;
	}
	
	private function _in_normal_enrollment_period() {
		// Before deadline
		$now = new \DateTime();
		$deadline = new \DateTime($this->session->deadline);
		
		return $now <= $deadline;
	}
	
	private function _in_extended_enrollment_period() {
		// After deadline. Before 4 days after.
		$now = new \DateTime();
		$deadline = new \DateTime($this->session->deadline);		
		if($now > $deadline) {
			$end_extended_period = (new \DateTime($this->session->date))->modify(static::ENROLLMENT_GRACE);
			return $now < $end_extended_period;
		}
		return false;
	}
	
	private function _in_dishwasher_enrollment_period() {
		// After diner time. Before end of the day.
		$now = new \DateTime();
		$diner_time = (new \DateTime($this->session->date))->setTime(18, 00, 00);
		
		if($now > $diner_time) {
			$end_dishwasher_period = (new \DateTime($this->session->date))->modify(static::DISHWASHER_ENROLLMENT_GRACE);
			return $now < $end_dishwasher_period;
		} 
		return false;
	}
}