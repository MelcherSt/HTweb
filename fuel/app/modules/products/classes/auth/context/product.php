<?php

namespace Products;

class Auth_Context_Product extends \Auth_Context_Base {
	
	private $product;
	
	protected function __construct(Model_Product $product, $user) {
		parent::__construct($user);
		
		$this->product = $product;
	}
	
	public static function forge($product, $user=null) {
		if(empty($user)) { $user = \Auth::get_user(); }
		return new Auth_Context_Product($product, $user);
	}
	
	/**
	 * Determine whether user can delete this product
	 * @param array $actions []
	 * @return boolean
	 */
	protected function _can_product_delete(array $actions=null) {
		$result = ($this->product->paid_by == $this->user->id) || $this->is_administrator();
		if(!$result) { $this->push_message('Cannot delete product. You do not have sufficient privilleges.'); }
		return $result;
	}

	protected function override_access() {
		return false;
	}

}