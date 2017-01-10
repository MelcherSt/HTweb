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
		$val->add_field('users', 'Selected users', 'required');
		return $val;
	}
	
	/**
	 * Retrieve all products that have been paid by the given user
	 * @param int $user_id
	 * @param boolean $settled Settled products only
	 * @return array \Products\Model_Product
	 */
	public static function get_by_payer($user_id, $settled=false) {
		return Model_Product::query()
				->where('paid_by', $user_id)
				->where('settled', $settled)
				->get();
	}
	
	/**
	 * Retrieve all products that were bought for the given user
	 * @param int $user_id
	 * @param boolean $include_self Include products user has bought
	 * @param boolean $settled Settled products only
	 * @return array \Products\Model_Product
	 */
	public static function get_by_user($user_id, $include_self=false, $settled=false) {
		$query = Model_Product::query()
				->related('users')
				->where('users.user_id', $user_id)
				->where('settled', $settled);
		
		if (!$include_self) {
			$query = $query->where('paid_by', '!=', $user_id);
		}
	
		return $query->get();
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
		return Model_Product::query()
				->related('users')
				->where('id', $this->id)
				->count('.user_id', true);
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
	
	/**
	 * Return a string with either the # of participants or the single participant name
	 * @return mixed string / int
	 */
	public function get_nicified_participants() {
		$part_no = $this->count_participants();
		
		if ($part_no == 1) {
			$part = Model_User_Product::query()->where('product_id', $this->id)->get_one();
			return $part->user->get_fullname(0);
		} else {
			return $part_no;
		}
	}

}
