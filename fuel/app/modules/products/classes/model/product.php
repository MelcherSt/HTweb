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
	
	protected static $_has_many = array(
		'users' => array(
			'model_to' => 'Sessions\Model_User_Product',
			'cascade_delete' => true,
		),
	);
	
	protected static $_has_one = array(
		'payer' => array(
			'key_from' => 'paid_by',
			'model_to' => '\Model_User',
			'key_to' => 'id',
			'cascade_delete' => false,
		)
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
	
	public static function get_ready_for_settlement() {
		return Model_Product::query()->where('approved', true)->get();
	}
	
	public function count_participants() {
		return $this::query()
				->related('users')
				->count();
	}

}
