<?php


class Auth_Context_Base {
	// Default function prefix
	const FUNC_PREFIX = '_can_';
	const FUNC_SPLIT = '_';
	
	/**
	 * Check to see if the user has the given permissions in this context.
	 * Performs an AND on all permissions. If a single permission is not 
	 * granted- or doesn't exist - , the function will report negatively.
	 * An action can have added actions. Actions are passed as parameters.
	 * A typical permission is formatted: area.permission[action1,action2]
	 * @param array $actions
	 */
	public function has_access($permissions) {
		$result = true;
		foreach($permissions as $permission) {
			$actions = null;
			$area = null;
			
			if(strpos($permission, '.') > 1) {
				// Try to split area.permission.
				$area_perm = explode('.', $permission);
				$area = $area_perm[0];
				$permission = $area_perm[1];
			}					
			
			if (($pos = strpos($permission, '[')) > 1) {
				// Get parameters for callback
				$actions = explode(',', substr($permission, $pos + 1, -1));
				$func = substr($permission, 0, $pos);		
			} else {
				$func = $permission;
			}

			if(isset($area)) {
				$callback = static::FUNC_PREFIX . $area . static::FUNC_SPLIT . $func;
			} else {
				$callback = static::FUNC_PREFIX . $func;
			}
			
			if(method_exists($this, $callback)) {
				$result = $result && call_user_func([$this, $callback], $actions);
			} else {
				// Early exit, permission doesn't exist.
				return false;
			}
		}	
		return $result;
	}
}	
