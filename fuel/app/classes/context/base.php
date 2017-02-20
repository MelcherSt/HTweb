<?php

class Context_Base {
	
	/**
	 * User associated with context
	 * @var Model_User 
	 */
	protected $user;
	
	
	protected function __construct(Model_User $user) {
		$this->user = $user;
	}
	
	/**
	 * Is the current user active
	 * @return type
	 */
	protected function _is_active() {
		return $this->user->active;
	}
}
