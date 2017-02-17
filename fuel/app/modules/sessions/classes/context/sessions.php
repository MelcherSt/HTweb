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
	public static function forge(Model_Session $session, \Model_User $user=null) {
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
	
	/**
	 * Is session editUI visible? 
	 * @return array panel, notes, cost, deadline
	 */
	public function view_update() {
		$result = [];					
		array_push($result, $this->_is_cook() && ($this->_in_normal_enrollment_period() || $this->_in_extended_enrollment_period()));	
		array_push($result, $this->_in_normal_enrollment_period());
		array_push($result, true);
		array_push($result, true);
		return $result;
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
	
	public function update_enroll($user_id=null) {
		return $this->create_enroll($user_id);
	}
	
	public function view_enroll() {
		return true;
	}
	
	/**
	 * Is create enroll UI visible? 
	 * @return array panel, cook, dishwasher, dishwasher-panel
	 */
	public function view_enroll_create() {
		$result = [];					
		array_push($result, $this->_in_normal_enrollment_period() && ($this->session->current_enrollment()) == null);	
		array_push($result, $this->session->count_cooks() != static::MAX_COOKS);
		array_push($result, $this->session->count_dishwashers() != static::MAX_DISHWASHER);		
		array_push($result, $this->_in_dishwasher_enrollment_period());	
		return $result;
	}
	
	/**
	 * Is update enroll UI visible?
	 * @return array panel, cook, dishwasher
	 */
	public function view_enroll_update($user_id=null) {
		if(empty($user_id)) {
			$self = true;
		} else {
			$self = $this->user->id == $user_id;
		}
		
		if($self) {
			$enrollment = $this->session->current_enrollment();
		} else {
			$enrollment = Model_Enrollment_Session::get_by_user($user_id);
		}
		
		$result = [];					
		array_push($result, $this->_in_normal_enrollment_period() && ($this->session->current_enrollment() !== null));	
		array_push($result, $this->session->count_cooks() != static::MAX_COOKS || (isset($enrollment) ? $enrollment->cook : false));
		array_push($result, $this->session->count_dishwashers() != static::MAX_DISHWASHER || (isset($enrollment) ? $enrollment->dishwasher : false));
		return $result;
	}
	
	/**
	 * Is the enroll other UI visible?
	 * @return boolean
	 */
	public function view_enroll_other() {
		return $this->_is_cook() && $this->_in_extended_enrollment_period();
	}
	
	
	public function delete_enroll($user_id=null) {
		return $this->create_enroll($user_id);
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