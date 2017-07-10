<?php
namespace Products;

/**
 * Definition of a monthly repeatable product. Used for product generation.
 */
class Model_ProductDefinition extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'cost' => ['default' => 0.0],
		'paid_by',
		'name',
		'last_execution',
		'created_at',
		'updated_at',
	);

	protected static $_observers = [
		'Orm\Observer_CreatedAt' => [
			'events' => ['before_insert'],
			'mysql_timestamp' => false,
		],
		'Orm\Observer_UpdatedAt' => [
			'events' => ['before_save'],
			'mysql_timestamp' => false,
		],
	];
	
	public static function validate($factory) {
		$val = \Validation::forge($factory);
		$val->add_field('name', __('product.field.name'), 'required|max_length[50]');
		$val->add_field('cost',  __('product.field.cost'), 'required|numeric_between['. Model_Product::MIN_PRICE.','.Model_Product::MAX_PRICE.']');
		$val->add_field('last_execution', 'Execution', 'valid_date');
		return $val;
	}
	
	/**
	 * Retrieve all products that have been paid by the given user
	 * @param int $user_id
	 * @param boolean $settled Settled products only
	 * @return array \Products\Model_Product
	 */
	public static function get_by_payer(int $user_id) :  array {
		return Model_Product::query()
				->where('paid_by', $user_id)
				->get();
	}
	
	/**
	 * Retrieve user model for paying user
	 * @return \Model_User
	 */
	public function get_payer() : \Model_User {
		return \Model_User::find($this->paid_by);
	}
	
	/**
	 * Determine whether the given user is the payer of this product.
	 * @param int $user_id The user id or id of current user by default.
	 * @return bool
	 */
	public function is_paid_by(int $user_id=null) : bool {
		return $this->paid_by == (empty($user_id) ? \Auth::get_user()->id : $user_id);
	}
}
