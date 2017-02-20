<?php
/**
 * Extension of the standard Auth_User model class including 
 * extended properties
 */
class Model_User extends \Auth\Model\Auth_User {
	protected static $_properties = array(
		'id',
		'username',
		'surname',
		'name',
		'lang' => [
			'default' => 'en',
		],
		'phone' => array(
			'default'     => '',
			'null'        => false,
		),
		'active' => array(
			'default'     => 1,
			'null'        => false,
		),
		'start_year',
		'end_year' => array(
			'default'     => 0,
			'null'        => false,
		),
		'points' => array(
			'default'     => 0,
			'null'        => false,
		),
		'password',
		'salt',
		'group_id',
		'iban' => array(
			'default'     => '',
			'null'        => true,
		),
		'email' => array(
			'default'     => '',
			'null'        => false,
		),
		'avatar' => array(
			'default'     => '',
			'null'        => false,
		),
		'last_login' => [
			'default' => 0,
		],
		'login_hash' => [
			'default' => ''],
	);
	
	protected static $_conditions = array(
        'order_by' => array('name' => 'asc'),
    );

	public static function validate($factory) {
		$val = Validation::forge($factory);
		$val->add_callable('CustomRules');
		$val->add_field('phone', 'Phone', 'max_length[20]');
		$val->add_field('email', 'Email', 'valid_email|max_length[255]');
		$val->add_field('iban', 'IBAN', 'max_length[30]|valid_iban');
		$val->add_field('lang', 'Language', 'max_length[2]|required|valid_lang');
		return $val;
	}
	
	/**
	 * Retrieve the full formatted name of this user (name + surname)
	 * @return type
	 */
	public function get_fullname() {
		return $this->name . ' ' . $this->surname;
	}
	
	/**
	 * Get a list of user by their state (default is active)
	 * @param boolean $active
	 * @return type
	 */
	public static function get_by_state($active=true) {
		return Model_User::find('all', array(
			'where' => array(
				array('active', $active)),
			'sort_by' => array('surname', 'desc')
		));
	}
}
