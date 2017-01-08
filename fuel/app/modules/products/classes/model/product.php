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
			'model_to' => 'Products\Model_User_Product',
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

	public static function validate($factory) {
		$val = \Validation::forge($factory);
		$val->add_field('name', 'Name', 'required|max_length[50]');
		$val->add_field('notes', 'Notes', 'max_length[255]');
		$val->add_field('cost', 'Cost', 'required|numeric_min[0]');
		//$val->add_field('paid_by', 'Paid By', 'required|valid_string[numeric]');
		return $val;
	}
	
	/**
	 * Retrieve all products that have been approved and haven't been settled
	 * @return array \Products\Model_Product
	 */
	public static function get_ready_for_settlement() {
		return Model_Product::query()
				->where('approved', true)
				->where('settled', false)
				->get();
	}
	
	/**
	 * Get the amount of users paying for this product
	 * @return int
	 */
	public function count_participants() {
		return $this::query()
				->related('users')
				->where('id', $this->id)
				->count(false, false);
	}
	
	/**
	 * Retrieve a list of user products in this product sorted by name alphabetically
	 * @return array \Products\Model_User_Product
	 */
	public function get_participants_sorted() {
		return Model_User_Product::query()
			->related('user')
			->order_by('user.name', 'asc')
			->where('product_id', $this->id)
			->get();
	}

}
