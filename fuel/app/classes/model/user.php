<?php
/**
 * Extension of the standard Auth_User model class including 
 * extended properties
 */
class Model_User extends \Auth\Model\Auth_User
{
	protected static $_properties = array(
		'id',
		'username',
		'surname',
		'name',
		'phone',
		'active',
		'start_year',
		'end_year',
		'points',
		'balance',
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

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('username', 'Username', 'required|max_length[255]');
		$val->add_field('surname', 'Surname', 'required|max_length[50]');
		$val->add_field('name', 'Name', 'required|max_length[50]');
		$val->add_field('phone', 'Phone', 'required|max_length[20]');
		$val->add_field('active', 'Active', 'required');
		$val->add_field('start_year', 'Start Year', 'required|valid_string[numeric]');
		$val->add_field('end_year', 'End Year', 'valid_string[numeric]');
		$val->add_field('points', 'Point Count', 'required|valid_string[numeric]');
		$val->add_field('balance', 'Balance', 'required');
		$val->add_field('password', 'Password', 'required|max_length[255]');
		$val->add_field('group_id', 'Group', 'required|valid_string[numeric]');
		$val->add_field('email', 'Email', 'required|valid_email|max_length[255]');

		return $val;
	}
	
	public function get_fullname() {
		return $this->name . ' ' . $this->surname;
	}

}
