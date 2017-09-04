<?php
/**
 * A password reset token tied to a user in the system that requested a password reset.
 */
class Model_ResetToken extends \Orm\Model  {
	protected static $_properties = array(
		'id',
		'user_id',
		'token',
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
	
	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'key_to' => 'id',
			'model_to' => '\Model_User',
			'cascade_delete' => false,
		)
	);

	protected static $_table_name = 'reset_tokens';	
}
