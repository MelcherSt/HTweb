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
	protected function _can_enroll(array $actions) {
		// Must at least be in normal or grace period
		$result = $this->_in_enroll_period() || $this->_in_enroll_cook_grace();
		if(!$result) { array_push($this->messages, 'Outside enrollment period.'); }
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'other':
						$result = $result && ($this->_is_moderator() && $this->_in_enroll_cook_grace());
						if(!$result) { array_push($this->messages, 'You do not have permission to enroll others.'); }
						break;
					case 'diswasher':
						$result = $result && $this->_in_dishwasher_grace() || $this->_can_enroll(['other']);
						if(!$result) { array_push($this->messages, 'Cannot enroll dishwasher.'); }
						break;
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission requested.');
						break 2; // Break from for-loop
				}
			}			
		}
		return $result;
	}
		
	/**
	 * Most primitive session update permission.
	 * @param actions [deadline, notes, cost]
	 * @return boolean
	 */
	protected function _can_session_update(array $actions) {	
		// Must at least be a moderator
		$result = $this->_is_moderator();
		if(!$result) { array_push($this->messages, 'You do not have permission to edit the session.'); }
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'deadline':
						$result = $result && $this->_in_deadline_cook_grace();
						break;
					case 'cost':
						$result = $result && $this->_in_cost_cook_grace();
						break;
					case 'notes':
						$result = $result && $this->_in_enroll_period();
						break;
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission requested.');
						break 2; // Break from for-loop
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
	
	/* Below follow functions used to determine in what (grace)period we are */
	
	/**
	 * Determine if we're in the normal enroll period for this session
	 * @return boolean
	 */
	protected function _in_enroll_period() {
		$now = strtotime(date('Y-m-d H:i:s'));
		$deadline = strtotime(date('Y-m-d H:i:s', strtotime($this->session->deadline)));	
		// Check if we live before the deadline
		if ($now < $deadline) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determine if dishwashers may enroll for this session. Sets both upper and lower boundary.
	 * @return boolean
	 */
	protected function _in_dishwasher_grace() {
		return !$this->_in_enroll_period() && strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::DISHWASHER_ENROLLMENT_GRACE);
	}
	
	/**
	 * Determine whether the deadline of this session may changed. Sets both upper and lower boundary.
	 * @return boolean
	 */
	protected function _in_deadline_cook_grace() {
		if ($this->_in_enroll_period()) { 
			// Deadline may be changed during enrollment period just alright.
			return true;
		} else {
			// If the deadline already passed
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::DEADLINE_GRACE);
		}
	}
	
	/**
	 * Determine whether the cost of this session may be changed by the cooks
	 * @return boolean
	 */
	protected function _in_cost_cook_grace() {
		return !$this->_in_enroll_period() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::COST_GRACE));

	}
	
	/**
	 * Determine whether the enrollments of this session may be altered by the cooks. Sets both upper and lower boundary.
	 * @return boolean
	 */
	protected function _in_enroll_cook_grace() {
		return !$this->_in_enroll_period() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::ENROLLMENT_GRACE));

	}
}
