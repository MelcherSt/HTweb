<?php

namespace Privileges;

final class Context_Privileges extends \Context_Base {
		
	private $permission;
	
	/**
	 * Name of the ORMAuth permission granting complete administration privileges.
	 * @var type 
	 */
	const MGMT_PERM = 'privileges.administration';
	
	protected function __construct(\Auth\Model\Auth_Permission $permission, \Model_User $user) {
		parent::__construct($user);
		$this->permission = $permission;	
	}
	
	/**
	 * Construct new Privileges context
	 * @param \Auth\Model\Auth_Permission  \Auth\Model\Auth_Permission $permission
	 * @param \Model_User $user
	 * @return \Products\Context_Privileges
	 */
	public static function forge(\Auth\Model\Auth_Permission $permission, \Model_User $user=null) {
		if(empty($user)) {
			$user = \Model_User::find(\Auth::get_user()->id);
		}	
		return new Context_Privileges($permission, $user);
	}
	
	public function view() : bool {
		return static::_is_administrator();
	}
}