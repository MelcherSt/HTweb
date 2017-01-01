<?php

/**
 * Extend default ORM Auth login driver to support salted passwords.
 */
class Auth_Login_OrmAuth extends \Auth\Auth_Login_Ormauth {
	
	
	/**
	 * Check the user exists and the correct password was entered
	 *
	 * @return  bool
	 */
	public function validate_user($username_or_email = '', $password = '')
	{
		// get the user identification and password
		$username_or_email = trim($username_or_email) ?: trim(\Input::post(\Config::get('ormauth.username_post_key', 'username')));
		$password = trim($password) ?: trim(\Input::post(\Config::get('ormauth.password_post_key', 'password')));

		// and make sure we have both
		if(empty($username_or_email) or empty($password))
		{
			return false;
		}

		// Look up the user
		$user = \Model_User::query()
			->select('salt', 'password')
			->where_open()
				->where('username', '=', $username_or_email)
				->or_where('email', '=', $username_or_email)
			->where_close()
			->get_one();
		
		if(empty($user)) {
			return false;
		}
		
		// Retrieve salt
		$salt = $user->salt;
		
		// return the user object, or false if not found
		if($user->password === $this->hash_password($password . $salt)) {
			return \Model\Auth_User::find($user->id);
		}
		return false;
	}
}