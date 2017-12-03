<?php
/**
 * Abstract contextual authentication base class. 
 * Provides basic functionality to create model specific context authentication.
 * @author Melcher
 */
abstract class Context_Base {
	
	/**
	 * User associated with this context.
	 * @var \Model_User 
	 */
	protected $user;
	
	/**
	 * Management permission string in this context. Should be formatted 'area.permission'.
	 * This permission is used by the context's administrator check.
	 * @var String
	 */
	const MGMT_PERM = '';
	
	
	protected function __construct($user) {
		// If no user was specified find the current user
		if(empty($user) || !($user instanceof \Model_User)) {
			$user = \Model_User::find(\Auth::get_user_id()[1]);
		}
		$this->user = $user;
	}
	
	public function create() : bool { return false; }
	
	public function update() : bool { return false; }
	
	public function view() : bool { return false; }
	
	public function delete() : bool { return false; }
	
	/**
	 * Check if the user associated with this context is the guest user.
	 * @return bool
	 */
	protected function is_guest() : bool {
		return $this->user->id == 0;
	}
	
	/**
	 * Check if the user associated with this context is active.
	 * @return boolean
	 */
	protected function is_active() : bool {
		return $this->user->active;
	}
	
	/**
	 * Check if current user has administrative permission of this context.
	 * @return bool
	 */
	public static function _is_administrator() : bool {
		return \Auth::has_access(static::MGMT_PERM);
	}
		
	/**
	 * Check if the current user is active.
	 * @return bool
	 */
	public static function _is_active() : bool {
		return \Model_User::get_current()->active;
	}
	
	/**
	 * Check if the current user is the guest user.
	 * @return bool
	 */
	public static function _is_guest() : bool {
		return \Model_User::get_current()->id == 0;
	}
}
