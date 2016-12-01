<?php

namespace Sessions;

class Model_Enrollment_Session extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'user_id',
		'session_id',
		'guests',
		'cook',
		'dishwasher',
		'paid',
		'created_at',
		'updated_at',
	);
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	
	protected static $_table_name = 'enrollment_sessions';
	
	protected static $_belongs_to = array(
		'user' => array(
			'model_to' => '\Model_Auth_User',
		)
		,'session' => array(
			'model_to' => '\Sessions\Model_Session'
		)
	);
}


