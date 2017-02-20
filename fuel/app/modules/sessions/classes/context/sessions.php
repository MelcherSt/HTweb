<?php

namespace Sessions;

final class Context_Sessions extends \Context_Base {
	
	private $session;
	private $cur_enrollment;
	
	/**
	 * Name of the ORMAuth permission granting complete administration privileges.
	 * @var type 
	 */
	const MGMT_PERM = 'sessions.administration';

	const MAX_COOKS = 1;
	const MAX_DISHWASHER = 2;
	
	protected function __construct(Model_Session $session, \Model_User $user) {
		parent::__construct($user);
		$this->session = $session;
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
	
	/**
	 * Determine if session can be updated/edited
	 * @return boolean
	 */
	public function update() {	
		if ($this->_is_settled()) {
			// A settled session may never be edited
			return false;
		}
		
		if($this->_is_administrator()) {
			// Admin may always edit
			return true;
		} else {
			// Cook may edit until 4 days after
			return $this->_is_cook() && ($this->session->in_enrollment_period() || $this->session->in_extended_enrollment_period());
		}
	}
	
	public function view() {
		// Everyone may view
		return true;
	}
	
	/**
	 * Is session edit UI visible? 
	 * @return array panel, notes, deadline, cost, payer
	 */
	public function view_update() {
		$result = [];
		array_push($result, $this->_is_cook() && ($this->session->in_enrollment_period() || $this->session->in_extended_enrollment_period()));	
		array_push($result, $this->session->in_enrollment_period());
		array_push($result, true);
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
				return $this->session->in_enrollment_period() || $this->session->in_extended_enrollment_period();
			} else {
				return $this->session->in_enrollment_period();
			}	
		} else {
			// Enrolling other user, cur user must be a cook in ext-period
			return $this->_is_cook() && $this->session->in_extended_enrollment_period();	
		}
	}
	
	public function update_enroll($user_id=null) {
		return $this->create_enroll($user_id) || $this->session->in_dishwasher_enrollment_period();
	}
	
	public function view_enroll() {
		return true;
	}
	
	public function delete_enroll($user_id=null) {
		return $this->create_enroll($user_id);
	}
	
	/**
	 * Is create enroll UI visible? 
	 * @return array panel, cook, dishwasher, 
	 */
	public function view_enroll_create() {
		$result = [];					
		array_push($result, $this->session->in_enrollment_period() && ($this->session->current_enrollment()) == null);	
		array_push($result, $this->session->count_cooks() != static::MAX_COOKS);
		array_push($result, $this->session->count_dishwashers() != static::MAX_DISHWASHER);			
		return $result;
	}
	
	/**
	 * Is update enroll UI visible?
	 * @return array panel, cook, dishwasher, dishwasher-panel
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
			$enrollment = Model_Enrollment_Session::get_by_user($user_id, $this->session->id);
		}
		
		$result = [];					
		array_push($result, $this->session->in_enrollment_period() && isset($enrollment));	
		array_push($result, $this->session->count_cooks() != static::MAX_COOKS || (isset($enrollment) ? $enrollment->cook : false));
		array_push($result, $this->session->count_dishwashers() != static::MAX_DISHWASHER || (isset($enrollment) ? $enrollment->dishwasher : false));
		array_push($result, $this->session->in_dishwasher_enrollment_period() && isset($enrollment) && $result[2]);	
		return $result;
	}
	
	/**
	 * Is the enroll other UI visible?
	 * @return boolean
	 */
	public function view_enroll_other() {
		return $this->_is_cook() && $this->session->in_extended_enrollment_period();
	}

	/**
	 * Has the current user administration privileges
	 * @return boolean
	 */
	private function _is_administrator(){
		return \Auth::has_access(static::MGMT_PERM);
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
}