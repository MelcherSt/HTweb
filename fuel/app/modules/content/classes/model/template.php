<?php
class Model_Template extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'name',
		'description',
		'base_template',
		'content_template',
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
	
	protected static $_table_name = 'templates';
	
	protected static $_has_many = array(
		'posts' => array(
			'key_from' => 'id',
			'key_to' => 'template',
			'model_to' => '\Content\Model_Post',
		)
	);

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Name', 'required|max_length[50]');
		$val->add_field('base_template', 'Base template', 'max_length[255]');
		$val->add_field('content_template', 'Content template', 'max_length[255]');
		return $val;
	}

}
