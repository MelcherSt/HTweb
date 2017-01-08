<?php

namespace Receipts;

class Model_Product_Receipt extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'product_id',
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
	
	protected static $_table_name = 'product_receipts';
	
	protected static $_belongs_to = array(
		'product' => array(
			'key_from' => 'product_id',
			'key_to' => 'id',
			'model_to' => '\Products\Model_Product',
		)
		,'receipt' => array(
			'key_from' => 'receipt_id',
			'key_to' => 'id',
			'model_to' => '\Receipts\Model_Receipt'
		)
	);
}


