<?php


class Auth_Context_Base {
	// Default function prefix
	const FUNC_PREFIX = '_can_';
	
	/**
	 * Check to see if the user has the given permissions in this context.
	 * Performs an AND on all permissions. If a single permission is not 
	 * granted- or doesn't exist - , the function will report negatively.
	 * An action can have added actions. Actions are passed as parameters.
	 * @param array $actions
	 */
	public function has_access($permissions) {
		$result = true;
		foreach($permissions as $permission) {
			$pos = strpos($permission, '[');
			$actions = [];
			if ($pos > 2 || $pos != false) {
				$actions = explode(',', substr($permission, $pos + 1, -1));
				$func = substr($permission, 0, $pos);		
			} else {
				$func = $permission;
			}

			$callback = static::FUNC_PREFIX . $func;
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
