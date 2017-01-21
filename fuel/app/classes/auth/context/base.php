<?php
/**
 * Allows for context-based permission checking. 
 */
class Auth_Context_Base {
	
	// Default function prefix and function split values
	const FUNC_PREFIX = '_can_';
	const FUNC_SPLIT = '_';
	
	/**
	 * Message queue
	 * @var array 
	 */
	protected $messages = [];
	
	/**
	 * User for which to check contextual permissions
	 * @var \Auth\Model_User 
	 */
	protected $user;
	
	private $verbose_mode = false;
	
	protected function __construct($user) {
		if(empty($user)) {
			throw new InvalidArgumentException('Context base needs an initialized user object!');
		}
		
		$this->user = $user; 
	}
	
	/**
	 * Check to see if the user has the given permissions in this context. An permission can have added actions. Actions are passed as parameters.
	 * @param array $permissions list of permissions. A permission is in the form of 'area.permission[action1, action2]'
	 * @param boolean $verbose if set any error messages while be saved in the message queue
	 * @param boolean $or_mode retrieve permission result in OR modus instead of default AND. 
	 * @return boolean Determines if the given permissions were granted. 
	 * Depending on modus (AND by default) all or some permissions need to be granted for the result to be true.
	 */
	public function has_access(array $permissions, $verbose=false, $or_mode=false) {
		$this->verbose_mode = $verbose;

		// Check if user fulfills override requirements
		if ($this->override_access()) {
			// Thanks for stopping by. Bye.
			return true;
		}
		
		// We have the permission until proven otherwise
		$result = true;
		
		foreach($permissions as $permission) {
			$actions = [];
			$area = null;
			
			// Try to split area.permission.
			if(strpos($permission, '.') > 2) {
				$area_perm = explode('.', $permission);
				$area = $area_perm[0];
				$permission = $area_perm[1];
			}					
			
			// Check for any parameters. If 0 params, func is called with null.
			if (($pos = strpos($permission, '[')) > 2) {
				$actions = explode(',', substr($permission, $pos + 1, -1));
				$func = substr($permission, 0, $pos);		
			} else {
				$func = $permission;
			}

			// Prepare callback
			if(!empty($area)) {
				$callback = static::FUNC_PREFIX . $area . static::FUNC_SPLIT . $func;
			} else {
				$callback = static::FUNC_PREFIX . $func;
			}
				
			// Invoke callback
			if(method_exists($this, $callback)) {
				if($or_mode) {
					$result = $result || call_user_func([$this, $callback], $actions);
				} else {
					$result = $result && call_user_func([$this, $callback], $actions);
				}	
			} else {
				// Early exit, callback doesn't exist.
				$result = false;
			}
		}	
		
		return $result;
	}
	
	/**
	 * Checked by has_access() function to determine whether or not to continue checking access rights.
	 * Implement this function to override the normal access evaluation procedure.
	 * @return boolean Returns true when user meets override requirements. Any further access evaluation will seize. 
	 */
	protected function override_access() {
		return false;
	}
	
	/**
	 * Pop the last message from the messages queue.
	 * @return string
	 */
	public function get_message() {
		return array_pop($this->messages);
	}
	
	/**
	 * Retrieve the complete message queue.
	 * @return array
	 */
	public function get_messages() {
		return $this->messages;
	}
	
	/**
	 * Push a message on top of the message queue during access evaluation. 
	 * If verbose mode was not set during access evaluation, message will be discarded.
	 * @param string $msg
	 */
	protected function push_message($msg) {
		if ($this->verbose_mode) { array_push($this->messages, $msg); }
	}
	
	/**
	 * Empty the messages queue.
	 */
	protected function clear_messages() {
		$this->messages = [];
	}
	
	/*
	 * Utility methods below
	 */
	
	/** 
	 * Check if current user is member of (super) administrators group
	 */
	protected function is_administrator() {
		$user_group_id = $this->user->group_id;
		
		return ($user_group_id == 5 || $user_group_id == 6);
	}
}	
