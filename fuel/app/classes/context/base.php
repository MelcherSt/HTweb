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
	
	public function create() { return false; }
	
	public function update() { return false; }
	
	public function view() { return false; }
	
	public function delete() { return false; }
	
	/**
	 * Is the current user active
	 * @return type
	 */
	protected function _is_active() {
		return $this->user->active;
	}
}
