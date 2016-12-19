<?php
namespace Products;

/**
 * Product
 */
class Model_Product extends \Orm\Model
{
	protected static $_properties = array(
		'id',
		'cost' => ['default' => 0.0],
		'paid_by',
		'name',
		'notes' => ['default' => ''],
		'approved' => ['default' => false],
		'settled' => ['default' => false],
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

	public static function validate($factory)
	{
		$val = Validation::forge($factory);
		$val->add_field('name', 'Name', 'required|max_length[50]');
		$val->add_field('notes', 'Notes', 'max_length[255]');
		$val->add_field('cost', 'Cost', 'required');
		$val->add_field('paid_by', 'Paid By', 'required|valid_string[numeric]');

		return $val;
	}

}
