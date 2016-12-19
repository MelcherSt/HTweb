<?php

namespace Products;

/**
 * Relate a user to a product
 */
class Model_User_Product extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'user_id',
		'product_id',
		'amount' => ['default' => 1],
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
	
	protected static $_table_name = 'product_user';
	
	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'key_to' => 'id',
			'model_to' => '\Model_User',
		)
		,'session' => array(
			'key_from' => 'product_id',
			'key_to' => 'id',
			'model_to' => '\Sessions\Model_Product'
		)
	);	
}


