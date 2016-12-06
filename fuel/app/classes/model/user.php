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
		'phone' => array(
			'default'     => '',
			'null'        => true,
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
		'balance' => array(
			'default'     => 0.0,
			'null'        => false,
		),
		'password',
		'group_id',
		'email',
		'created_at',
		'updated_at',
		'last_login'      => array(
			'form'        => array('type' => false),
		),
		'previous_login'  => array(
			'form'        => array('type' => false),
		),'login_hash'      => array(
			'form'        => array('type' => false),
		),
		'user_id'         => array(
			'default'     => 0,
			'null'        => false,
			'form'        => array('type' => false),
		),
		'created_at'      => array(
			'default'     => 0,
			'null'        => false,
			'form'        => array('type' => false),
		),
		'updated_at'      => array(
			'default'     => 0,
			'null'        => false,
			'form'        => array('type' => false),
		),
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);

	public static function validate($factory) {
		$val = Validation::forge($factory);
		$val->add_field('phone', 'Phone', 'max_length[20]');
		$val->add_field('email', 'Email', 'required|valid_email|max_length[255]');

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
	 * Get a list of user by their bootstate (default is active)
	 * @param type $active
	 * @return type
	 */
	public static function get_by_state($active=true) {
		return Model_User::find('all', array(
			'where' => array(
				array('active', $active),
			)
		));
	}

}
