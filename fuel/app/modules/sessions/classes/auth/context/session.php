<?php

namespace Sessions;

/**
 * Controlling contextual permissions for sessions
 */
class Auth_Context_Session extends \Auth_Context_Base{
	
	private $session;		// \Sessions\Model_Session
	private $user;			// \Model_User
	private $enrollment;	// \Sessions\Model_Enrollment from $user
	
	/* Grace variables */
	const DEADLINE_GRACE = '+1day';
	const ENROLLMENT_GRACE = '+5days';
	const COST_GRACE = '+5days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	public function __construct($session, $user) {
		$this->session = $session;
		$this->user = $user;
		$this->enrollment = $session->current_enrollment();
	}
	
	public static function forge($session, $user) {
		return new Auth_Context_Session($session, $user);
	}
	
	/**
	 * Most primitive enrollment permission.  
	 * @return boolean
	 */
	protected function _can_enroll() {	
		return $this->session->can_enroll() || $this->_can_enroll_other();
	}
	
	/**
	 * Extension of the enrollment perm. User is able to enroll others
	 * only if they're a moderator.
	 * @return boolean
	 */
	protected function _can_enroll_other() {
		return $this->_in_enroll_cook_grace() && $this->_is_moderator();
	}
	
	/**
	 * Dishwasher enrollment. Either we're in the general grace period or 
	 * the cook is still in his grace period to (un)enroll users.
	 * @return boolean
	 */
	protected function _can_enroll_dishwasher() {
		return $this->_in_dishwasher_grace() || $this->_can_enroll_other();
	}
	
	/**
	 * Most primitive session update permission.
	 * @param actions [deadline, notes, cost]
	 * @return boolean
	 */
	protected function _can_update_session($actions=[]) {	
		$result = $this->_is_moderator(); // Base permission
		
		if(!empty($actions)) {		
			foreach($actions as $action) {
				switch($action) {
					case 'deadline':
						$result = $result && $this->_in_deadline_cook_grace();
						break;
					case 'cost':
						$result = $result && $this->_in_cost_cook_grace();
						break;
					case 'notes':
						$result = $result && $this->_can_enroll();
						break;
					default:
						// Unknown permission
						return false;
				}
			}			
		}		
		return $result;
	}
	
	/* Below follow functions used to determine the role of the user in the session */
	
	protected function _is_moderator() {
		// Must either be an admin or cook to be able to change advanced settings.
		return $this->enrollment->cook || $this->user->group_id == 5;
	}
	
	/* Below follow functions used to determine in what timespan we are */
	
	
	protected function _in_dishwasher_grace() {
		return strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::DISHWASHER_ENROLLMENT_GRACE);
	}
	
	/**
	 * Determine whether the deadline of this session may changed
	 * @return boolean
	 */
	protected function _in_deadline_cook_grace() {
		if ($this->_can_enroll()) { 
			return true;
		} else {
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::DEADLINE_GRACE);
		}
	}
	
	/**
	 * Determine whether the cost of this session may be changed by the cooks
	 * @return boolean
	 */
	protected function _in_cost_cook_grace() {
		return !$this->_can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::COST_GRACE));

	}
	
	/**
	 * Determine whether the enrollments of this session may be altered by the cooks
	 * @return boolean
	 */
	protected function _in_enroll_cook_grace() {
				return !$this->_can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::ENROLLMENT_GRACE));

	}
}
