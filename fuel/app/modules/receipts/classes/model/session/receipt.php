<?php

namespace Receipts;

class Model_Session_Receipt extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'session_id',
		'receipt_id',
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
	
	protected static $_table_name = 'session_receipts';
	
	protected static $_belongs_to = array(
		'session' => array(
			'key_from' => 'session_id',
			'key_to' => 'id',
			'model_to' => '\Sessions\Model_Session',
		)
		,'receipt' => array(
			'key_from' => 'receipt_id',
			'key_to' => 'id',
			'model_to' => '\Receipts\Model_Receipt'
		)
	);
}


