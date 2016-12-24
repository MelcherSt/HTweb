<?php
/**
 * Allows for context-based permission checking. 
 */
class Auth_Context_Base {
	
	// Default function prefix
	const FUNC_PREFIX = '_can_';
	const FUNC_SPLIT = '_';
	
	protected $last_error = '';

	/**
	 * Check to see if the user has the given permissions in this context.
	 * Performs an AND on all permissions. If a single permission is not 
	 * granted- or doesn't exist - , the function will report negatively.
	 * An permission can have added actions. Actions are passed as parameters.
	 * @param array $permissions list of permissions in the form of area.permission[action1, action2]
	 * @param boolean $verbose write any errors to $last_error for later retrieval
	 * @return boolean permission-granted
	 */
	public function has_access(array $permissions) {
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
			
			\Log::error('Callback: ' . $callback);
			\Log::error('Arguments: ' . var_dump($actions));
			
			// Invoke callback
			if(method_exists($this, $callback)) {
				\Log::error('Calling callback');
				$result = $result && call_user_func([$this, $callback], $actions);
			} else {
				\Log::error('Not callable. Quit.');
				// Early exit, callback doesn't exist.
				$result = false;
			}
		}	
		
		// Empty last_error property if we aren't verbose
		/*if (!$verbose) {
			$this->last_error = '';
		}*/
		
		return $result;
	}
	
	/**
	 * Get the last error message returned while checking for permissions on this context.
	 * @return string
	 */
	public function get_last_error() {
		return $this->last_error;
	}
}	
