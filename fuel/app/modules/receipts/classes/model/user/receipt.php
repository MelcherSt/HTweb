<?php

namespace Receipts;

class Model_User_Receipt extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'user_id',
		'receipt_id',
		'balance' => array(
			'default'     => 0.0,
			'null'        => false,
		),
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
	
	protected static $_table_name = 'user_receipts';
	
	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'key_to' => 'id',
			'model_to' => '\Model_User',
		)
		,'receipt' => array(
			'key_from' => 'receipt_id',
			'key_to' => 'id',
			'model_to' => '\Receipts\Model_Receipt'
		)
	);
}


