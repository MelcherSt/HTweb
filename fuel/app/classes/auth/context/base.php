<?php
/**
 * Allows for context-based permission checking. 
 */
class Auth_Context_Base {
	
	// Default function prefix
	const FUNC_PREFIX = '_can_';
	const FUNC_SPLIT = '_';
	
	protected $messages = [];

	/**
	 * Check to see if the user has the given permissions in this context. An permission can have added actions. Actions are passed as parameters.
	 * @param array $permissions list of permissions. A permission is in the form of 'area.permission[action1, action2]'
	 * @param boolean $verbose if set to true any error messages can be retrieved using get_message() or get_messages()
	 * @param boolean $or_mode retrieve permission result in OR modus instead of default AND. 
	 * @return boolean Determines if the given permissions were granted. 
	 * Depending on modus (AND by default) all or some permissions need to be granted for the result to be true.
	 */
	public function has_access(array $permissions, $verbose=false, $or_mode=false) {
		$this->clear_messages();

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
		
		// Empty last_error property if we aren't verbose
		if (!$verbose) {
			$this->clear_messages();
		}
		
		return $result;
	}
	
	/**
	 * Pop the last message from the messages list.
	 * @return string
	 */
	public function get_message() {
		return array_pop($this->messages);
	}
	
	/**
	 * Get the list of all messages
	 * @return array
	 */
	public function get_messages() {
		return $this->messages;
	}
	
	/**
	 * Empty the messages list
	 */
	protected function clear_messages() {
		$this->messages = [];
	}
}	
