<?php
class Model_Post extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'title',
		'body',
		'author',
		'img',
		'public',
		'featured',
		'category',
		'template',
		'created_at',
		'updated_at',
	);

	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_save'),
			'mysql_timestamp' => false,
		),
	);
	
	protected static $_table_name = 'posts';
	
	protected static $_belongs_to = array(
		'author' => array(
			'key_from' => 'author',
			'key_to' => 'id',
			'model_to' => '\Model_User',
		),
		'template' => array(
			'key_from' => 'template',
			'key_to' => 'id',
			'model_to' => '\Content\Model_Template',
		)
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('title', 'Title', 'required|max_length[50]');
		$val->add_field('author', 'Author', 'required|max_length[50]');
		return $val;
	}

}
