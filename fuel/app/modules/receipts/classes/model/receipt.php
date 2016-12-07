<?php
namespace Receipts;

class Model_Receipt extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'date' => array(
			'validation' => array('required', 'valid_date'),
		),
		'notes',
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

	protected static $_table_name = 'receipts';	
	
	protected static $_has_many = array(
		'sessions' => array(
			'model_to' => '\Receipts\Model_Session_Receipt',
			'cascade_delete' => true,
		),
		'users' => array(
			'model_to' => '\Receipts\Model_User_Receipt',
			'cascade_delete' => false,
		),
	);
}
