<?php

/**
 * A notification
 */
class Model_Notification extends \Orm\Model {

	protected static $_properties = array(
		'id',
		'user_id',
		'text',
		'type' => ['default' => 'MISC'],
		'title',
		'icon',
		'href',
		'read' => ['default' => 0],
		'created',
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
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => true,
			'property' => 'created',
		),
	);
	
	protected static $_conditions = [
		'order_by' => ['created' => 'desc']
	];
	
	public static function scrub() {
		static::query()->where(\DB::expr('DATE_ADD(created, INTERVAL 7 DAY)'), '<', \DB::expr('CURDATE()'))->delete();
	}

	public static function get_unread_count($user_id) : int {
		return static::query()->where('user_id', $user_id)
				->where('read', false)
				->count();
	}
	
	public static function get_unread($user_id, $limit = 5) : array {
		return static::query()->where('user_id', $user_id)
				->where('read', false)
				->limit($limit)
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
	
	public static function fire_notification($title, $text, $icon, $href, $user_id, $type = "MISC") {
		static::forge([
			'title' => $title,
			'text' => $text,
			'icon' => $icon,
			'href' => $href,
			'user_id' => $user_id,
			'type' => $type,
			'read' => false,
		])->save();
	}
	
	/**
	 * Check if a notification of the given type is already present for the
	 * given user
	 * @param string $type
	 * @parem int $user_id
	 */
	public static function has_notifications($type, $user_id) : bool {
		$count = static::query()
				->where('user_id', $user_id)
				->where('type', $type)
				->count();		
		return $count > 0;
	}
	
	/**
	 * Delete all notifications of a given type for the given user.
	 * @param string $type
	 * @param int $user_id
	 */
	public static function scrub_notifications($type, $user_id) {
		static::query()->where('user_id', $user_id)
				->where('type', $type)
				->delete();
	}

}
