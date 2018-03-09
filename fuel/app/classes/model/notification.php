<?php

/**
 * A notification
 */
class Model_Notification extends \Orm\Model {

	protected static $_properties = array(
		'text',
		'title',
		'icon',
		'href',
		'read',
		'date_time',
	);
	protected static $_has_one = array(
		'user' => array(
			'key_from' => 'user_id',
			'model_to' => 'Model_User',
			'key_to' => 'id',
			'cascade_save' => true,
			'cascade_delete' => true,
		)
	);

	public static function get_unread_count($user_id) : int {
		return static::query()->where('user_id', $user_id)
				->where('read', false)
				->count();
	}
	
	public static function get_unread($user_id) : array {
		return static::query()->where('user_id', $user_id)
				->where('read', false)
				->get();
	}
	
	public static function get_all($user_id) : array {
		return static::query()->where('user_id', $user_id)
				->get();
	}
	
	public static function set_all_read($user_id) {
		\DB::update('notifications')->set(['read' => true])
				->where('user_id', $user_id)
				->execute();
	}

}
