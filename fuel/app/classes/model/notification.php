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
		'read'
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

	public static function validate($factory) {
		$val = Validation::forge($factory);
		$val->add_field('text', 'Tekst', 'required|max_length[100]');
		$val->add_field('title', 'Titel', 'max_length[100]');
		$val->add_field('icon', 'Icoon', 'max_length[100]');
		$val->add_field('href', 'Link', 'max_length[255]');
		$val->add_field('target', 'Doel', 'max_length[20]');
		return $val;
	}

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

}
