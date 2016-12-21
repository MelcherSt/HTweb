<?php

namespace Sessions;

class Context {
	
	private $session; // \Sessions\Model_Session
	private $user; // Model_User
	private $enrollment; // \Sessions\Model_Enrollment from $user
	
	const FUNC_PREFIX = '_can_';
	
	public function __construct($session, $user) {
		$this->session = $session;
		$this->user = $user;
		$this->enrollment = $session->current_enrollment();
	}
	
	public static function forge($session, $user) {
		return new Context($session, $user);
	}
	
	/**
	 * Check to see if the user may perform the given actions in the current context.
	 * This basically is an AND on all given actions. If a single action is not 
	 * permitted - or doesn't exist - , the function will report negatively.
	 * An action can have added permissions. Permissions are passed as parameters.
	 * @param array $actions
	 */
	public function can_perform($actions) {
		$result = true;
		foreach($actions as $action) {
			$pos = strpos($action, '[');
			$params = [];
			if ($pos > 2 || $pos != false) {
				$params = explode(',', substr($action, $pos + 1, -1));
				$func = substr($action, 0, $pos);		
			} else {
				$func = $action;
			}
			
			echo var_dump($params);
			echo $pos;
			
			$callback = static::FUNC_PREFIX . $func;
			if(method_exists($this, $callback)) {
				$result = $result && call_user_func([$this, $callback], $params);
			} else {
				return false;
			}
		}	
		return $result;
	}	
	
	/**
	 * Most primitive enrollment permission.  
	 * @return boolean
	 */
	private function _can_enroll() {	
		return $this->session->can_enroll() || $this->_can_enroll_other();
	}
	
	/**
	 * Extension of the enrollment perm. User is able to enroll others
	 * only if they're a moderator.
	 * @return boolean
	 */
	private function _can_enroll_other() {
		return $this->_in_enroll_cook_grace() && $this->_is_moderator();
	}
	
	/**
	 * Dishwasher enrollment. Either we're in the general grace period or 
	 * the cook is still in his grace period to (un)enroll users.
	 * @return boolean
	 */
	private function _can_enroll_dishwasher() {
		return $this->_in_dishwasher_grace() || $this->_can_enroll_other();
	}
	
	/**
	 * Most primitive session update permission.
	 * @return boolean
	 */
	private function _can_update_session($permissions) {	
		echo 'hoi';
		
		if(empty($permissions) || sizeof($permissions) == 0) {
			return $this->_is_moderator();
		} else {
			$result = true;			
			foreach($permissions as $permission) {
				switch($permission) {
					case 'deadline':
						$result = $result && $this->session->can_change_deadline();
						break;
					case 'cost':
						$result = $result && $this->session->can_change_cost();
						break;
					case 'notes':
						$result = $result && $this->_can_enroll();
						break;
					default:
						// Unknown permission
						return true;
				}
			}	
			return $result;
		}
	}
	
	/* Below follow functions used to determine the role of the user in the session */
	
	private function _is_moderator() {
		// Must either be an admin or cook to be able to change advanced settings.
		return $this->enrollment->cook || $this->user->group_id == 5;
	}
	
	
	/* Below follow functions used to determine in what timespan we are */
	
	private function _in_dishwasher_grace() {
		return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DISHWASHER_ENROLLMENT_GRACE);
	}
	
	private function _in_deadline_cook_grace() {
		return $this->session->can_change_deadline();
	}
	
	private function _in_cost_cook_grace() {
		return $this->session->can_change_cost();
	}
	
	/**
	 * Can cooks (un)enroll other users in the session
	 * @return type
	 */
	private function _in_enroll_cook_grace() {
		return $this->session->can_change_enrollments();
	}
}
