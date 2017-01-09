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
	const DEADLINE_GRACE = '+19hours';
	const ENROLLMENT_GRACE = '+5days';
	const COST_GRACE = '+5days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	/* Max counts */
	const MAX_COOKS = 1;
	const MAX_DISHWASHER = 2;
	
	const DINER_TIME = '18:00';
	
	public function __construct($session, $user) {
		$this->session = $session;
		$this->user = $user;
		$this->enrollment = $session->current_enrollment();
		
		// Create bogus enrollment if the user is not enrolled.
		if(empty($this->enrollment)) {
			$this->enrollment = Model_Enrollment_Session::forge();
		}
	}
	
	public static function forge($session, $user) {
		return new Auth_Context_Session($session, $user);
	}
	
	
	/**
	 * Determine whether if enrollment may be deleted. Alias for enroll.update.
	 * @param array $actions [other, dishwasher, cook]
	 * @return boolean
	 */
	protected function _can_enroll_delete(array $actions=null) {
		return $this->_can_enroll_update($actions);
	}
	
	/**
	 * Determine whether user's enrollment may be updated
	 * @param array $actions [dishwasher, cook]
	 * @return boolean
	 */
	protected function _can_enroll_update(array $actions=null) {
		// Must at least be in normal or grace period
		$result = $this->_in_enroll_period() || $this->_in_dishwasher_grace();
		if(!$result) { array_push($this->messages, 'Cannot update enrollment. Outside enrollment period.'); }
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'dishwasher':
						// Check period. If we have the prop, we are allowed to update, otherwise we're adding and need to check max.
						$result = $result && $this->_in_dishwasher_grace() && ($this->enrollment->dishwasher ? true : !$this->_is_max_dishwashers());
						if(!$result) { array_push($this->messages, 'Cannot update dishwasher property on this enrollment.'); }
						break;
					case 'cook':
						$result = $result && $this->_in_enroll_period() && ($this->enrollment->cook ? true : !$this->_is_max_cooks());
						if(!$result) { array_push($this->messages, 'Cannot update cook property on this enrollment.'); }
						break;
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission <strong>'. $action . '</strong> on enroll.update requested.');
						break 2; // Break from for-loop
				}
			}			
		}
		return $result;
	}
	
	/**
	 * Determine whether enrollment may be created.
	 * Basically a wrapper around enroll.update also checking if max counts have been reached.
	 * @param array $actions [dishwasher, cook]
	 * @return boolean
	 */
	protected function _can_enroll_create(array $actions=null) {
		$result = $this->_in_enroll_period();
		if(!$result) { array_push($this->messages, 'Cannot create enrollment. Outside enrollment period.'); }
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'cook':
						$result = $result && !$this->_is_max_cooks();
						if(!$result) { array_push($this->messages, 'Maximum amount of cooks reached.'); }
						break;
					case 'dishwasher':
						// NOTE: Can only apply dishwasher role through update or 'enroll.other'
						$result = false;
						array_push($this->messages, 'Cannot create enrollment with dishwasher property set during normal enrollment period.');
						break 2; // Break from for-loop
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission <strong>'. $action . '</strong> on enroll.create requested.');
						break 2; // Break from for-loop
				}
			}			
		}
		return $result;
	}
	
	/**
	 * 
	 * @param array $actions [dishwasher, cook, set-cook, set-dishwasher]
	 * @return boolean
	 */
	protected function _can_enroll_other(array $actions=null) {
		// Must at least be in normal or grace period
		$result = $this->_in_enroll_mod_grace() && $this->_is_moderator();
		if(!$result) { array_push($this->messages, 'Cannot create/update enrollment for other user. You do not have sufficient privilleges.'); }

		$set_dishwasher = false;
		$set_cook = false;
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'set-dishwasher':
						// Used to check if the 'other' user did already have this prop set.
						$set_dishwasher = true;
						break;
					case 'set-cook':
						$set_cook = true;
						break;	
					case 'dishwasher':
						$result = $result && (!$this->_is_max_dishwashers() || $set_dishwasher);
						if(!$result) { array_push($this->messages, 'Cannot update dishwasher property on other enrollment. Reported previous value: ' . (int)$set_dishwasher); }
						break;
					case 'cook':
						$result = $result && (!$this->_is_max_cooks() || $set_cook);
						if(!$result) { array_push($this->messages, 'Cannot update cook property on other enrollment. Reported previous value: ' . (int)$set_cook); }
						break;
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission <strong>'. $action . '</strong> on enroll.other requested.');
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
	protected function _can_session_update(array $actions=null) {	
		// Must at least be a moderator
		$result = $this->_is_moderator();
		if(!$result) { array_push($this->messages, 'Cannot update session properties. You do not have sufficient privilleges.'); }
		
		if(isset($actions) && $result) {		
			foreach($actions as $action) {
				switch($action) {
					case 'deadline':
						$result = $result && $this->_in_deadline_mod_grace();
						break;
					case 'cost':
						$result = $result && $this->_in_cost_mod_grace();
						break;
					case 'notes':
						$result = $result && $this->_in_enroll_period();
						break;
					default:
						$result = false;
						array_push($this->messages, 'Unknown permission <strong>'. $action . '</strong> requested.');
						break 2; // Break from for-loop
				}
			}			
		}				
		return $result;
	}
	
	/**
	 * Determines if the session can be delayed (e.g. alter deadline).
	 * For a delay to be possible, there should be at least 1 participant.
	 * @param array $actions There are no valid actions 
	 * @return boolean
	 */
	protected function _can_session_delay(array $actions=null) {
		// The deadline must be past-due and there should be 0 cooks
		if ($this->session->count_participants() > 0) {
			return !$this->_in_enroll_period() && 
				($this->session->count_cooks() == 0) && 
				$this->_in_deadline_mod_grace();
		} else {
			return false;
		}
	}
	
	/* Below follow functions used to determine the role of the user in the session */
	
	private function _is_moderator() {
		// Must either be an admin or cook to be able to change advanced settings.
		return $this->enrollment->cook || $this->user->group_id == 5;
	}
	
	
	/* Below follow function used to determine the amount of cooks and dishwashers */
	
	private function _is_max_cooks() {
		return $this->session->count_cooks() >= static::MAX_COOKS; 	
	}
	
	private function _is_max_dishwashers() {
		return $this->session->count_dishwashers() >= static::MAX_DISHWASHER; 	
	}
	
	/* Below follow functions used to determine in what (grace)period we are */
	
	/**
	 * Determine if we're in the normal enroll period for this session
	 * @return boolean
	 */
	private function _in_enroll_period() {
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
	private function _in_dishwasher_grace() {
		return !$this->_in_enroll_period() && $this->_in_past_diner() && strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::DISHWASHER_ENROLLMENT_GRACE);
	}
	
	/**
	 * Determine if we're past diner time (by default diner time is at 18:00)
	 * @return boolean
	 */
	private function _in_past_diner() {
		$now = strtotime(date('Y-m-d H:i:s'));
		$begin = strtotime(date('Y-m-d H:i:s', strtotime(static::DINER_TIME)));	
		// Now should be after diner start
		if ($now > $begin) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determine whether the deadline of this session may changed. Sets both upper and lower boundary.
	 * @return boolean
	 */
	private function _in_deadline_mod_grace() {
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
	private function _in_cost_mod_grace() {
		return !$this->_in_enroll_period() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::COST_GRACE));

	}
	
	/**
	 * Determine whether the enrollments of this session may be altered by the cooks. Sets both upper and lower boundary.
	 * @return boolean
	 */
	private function _in_enroll_mod_grace() {
		return !$this->_in_enroll_period() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->session->date . static::ENROLLMENT_GRACE));

	}
}
