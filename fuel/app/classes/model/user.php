<?php
/**
 * Extension of the standard Auth_User model class including 
 * extended properties
 */
class Model_User extends \Auth\Model\Auth_User {
	protected static $_properties = [
		'id',
		'username',
		'surname',
		'name',
		'lang' => [
			'default' => 'en',
		],
		'phone' => [
			'default'     => '',
			'null'        => true,
		],
		'active' => [
			'default'     => 1,
			'null'        => false,
		],
		'start_year',
		'end_year' => [
			'default'     => 0,
			'null'        => false,
		],
		'points' => [
			'default'     => 0,
			'null'        => false,
		],
		'password',
		'salt',
		'group_id',
		'iban' => [
			'default'     => '',
			'null'        => true,
		],
		'email' => [
			'default'     => '',
			'null'        => false,
		],
		'avatar' => [
			'default'     => '',
			'null'        => false,
		],
		'last_login' => [
			'default' => 0,
		],
		'login_hash' => [
			'default' => ''],
	];
	
	protected static $_conditions = [
        'order_by' => ['name' => 'asc'],
    ];

	public static function validate($factory) {
		$val = Validation::forge($factory);
		$val->add_callable('CustomRules');
		$val->add_field('phone', 'Phone', 'max_length[10]');
		$val->add_field('email', 'Email', 'valid_email|max_length[255]');
		$val->add_field('iban', 'IBAN', 'max_length[30]|valid_iban');
		$val->add_field('lang', 'Language', 'max_length[2]|required|valid_lang');
		return $val;
	}
	
	/**
	 * Get the currently logged-in user
	 * @return Model_User
	 */
	public static function get_current() : Model_User {
		return Model_User::find(\Auth::get_user()->id);
	}
		
	/**
	 * Get a list of users by their state (default is active)
	 * @param boolean $active
	 * @return array Model_User
	 */
	public static function get_by_state(bool $active=true) : array {
		return Model_User::query()
			->where('active', $active)
			->order_by('surname', 'desc')
			->get();
	}
	
	/**
	 * Retrieve the full formatted name of this user (name + surname).
	 * @return string
	 */
	public function get_fullname() : string {
		return $this->name . ' ' . $this->surname;
	}
	
	/**
	 * Retrive a short copy of this user's name. 
	 * @return string
	 */
	public function get_shortname() : string {
		return $this->name;
	}
}
