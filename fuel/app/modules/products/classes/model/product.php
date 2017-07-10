<?php
namespace Products;

/**
 * Product
 */
class Model_Product extends \Orm\Model {
	
	const MIN_PRICE = -200;
	const MAX_PRICE = 200;
	
	protected static $_properties = array(
		'id',
		'cost' => ['default' => 0.0],
		'paid_by',
		'name',
		'date',
		'generated' => ['default' => false],
		'notes' => ['default' => ''],
		'approved' => ['default' => false],
		'settled' => ['default' => false],
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
	
	protected static $_has_many = [
		'users' => [
			'model_to' => 'Products\Model_User_Product',
			'cascade_delete' => true,
		],
	];
	
	protected static $_conditions = [
        'order_by' => ['date' => 'desc'],
    ];
	
	public static function validate($factory) {
		$val = \Validation::forge($factory);
		$val->add_field('date', __('product.field.date'), 'required|valid_date');
		$val->add_field('name', __('product.field.name'), 'required|max_length[50]');
		$val->add_field('notes',  __('product.field.notes'), 'max_length[255]');
		$val->add_field('cost',  __('product.field.cost'), 'required|numeric_between['.static::MIN_PRICE.','.static::MAX_PRICE.']');
		$val->add_field('users',  __('product.field.participant_plural'), 'required');
		return $val;
	}
	
	/**
	 * Retrieve all products that have been paid by the given user
	 * @param int $user_id
	 * @param boolean $settled Settled products only
	 * @return array \Products\Model_Product
	 */
	public static function get_by_payer(int $user_id, bool $settled=false) :  array {
		return Model_Product::query()
				->where('paid_by', $user_id)
				->where('settled', $settled)
				->get();
	}
	
	/**
	 * Retrieve all products that were bought for or by the given user.
	 * @param int $user_id
	 * @param boolean $include_self Include products user has bought
	 * @param boolean $settled Settled products only
	 * @return array \Products\Model_Product
	 */
	public static function get_by_user(int $user_id, bool $include_self=true, bool $settled=false) : array {
		$query = Model_Product::query()		
				->where('paid_by', $user_id)
				->where('settled', $settled)
				->or_where_open()
					->related('users')
					->where('users.user_id', $user_id)
					->where('settled', $settled)	
				->or_where_close();
		
		if (!$include_self) {
			$query = $query->where('paid_by', '!=', $user_id);
		}
		return $query->get();
	}
	
	/**
	 * Retrieve all products that have been approved and haven't been settled
	 * @return array \Products\Model_Product
	 */
	public static function get_settleable() : array {
		return Model_Product::query()
				->where('approved', true)
				->where('settled', false)
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
	 * Retrieve the enrollment (if any) for the given user
	 * @param int $user_id
	 * @return Model_User_Product
	 */
	public function get_enrollment($user_id) : ?Model_User_Product {		
		return Model_User_Product::query()
				->where('user_id', $user_id)
				->where('product_id', $this->id)
				->get_one();
	}
		
		/**
	 * Determine whether the given user is the payer of this product.
	 * @param int $user_id The user id or id of current user by default.
	 * @return bool
	 */
	public function is_paid_by(int $user_id=null) : bool {
		return $this->paid_by == (empty($user_id) ? \Auth::get_user()->id : $user_id);
	}
	
	/**
	 * Get the amount of users paying for this product
	 * @return int
	 */
	public function count_participants() : int {
		return Model_Product::query()
				->related('users')
				->where('id', $this->id)
				->count('.user_id', true);
	}
	
	/**
	 * Get the total amount of participants (this takes into account the amounts of purchases)
	 * @return int
	 */
	public function count_total_participants() : int {		
		$guest_count = array_values(\DB::select(\DB::expr('SUM(amount)'))
				->from('user_product')
				->where('product_id', $this->id)
				->execute()[0])[0];
		
		if (empty($guest_count)) {
			return 0;
		}
		return $guest_count;
	}
	
	/**
	 * Retrieve a list of user products in this product sorted by name alphabetically.
	 * @return array \Products\Model_User_Product
	 */
	public function get_participants_sorted() : array {
		return Model_User_Product::query()
			->related('user')
			->order_by('user.name', 'asc')
			->where('product_id', $this->id)
			->get();
	}
	
	/**
	 * Return a string with either the # of participants or the single participant name.
	 * @return string
	 */
	public function get_nicified_participants() : string {	
		if ($this->count_participants() < 4) {	
			return implode(', ', array_map(function(Model_User_Product $x) { return $x->user->get_shortname(); }, $this->get_participants_sorted()));
		} else {
			return $this->count_participants();
		}
	}
}
