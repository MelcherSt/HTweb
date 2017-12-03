<?php

namespace Products;

final class Context_Products extends \Context_Base {
		
	private $product;
	
	/**
	 * Name of the ORMAuth permission granting complete administration privileges.
	 * @var type 
	 */
	const MGMT_PERM = 'products.administration';
	
	protected function __construct(Model_Product $product, \Model_User $user) {
		parent::__construct($user);
		$this->product = $product;	
	}
	
	/**
	 * Construct new Session context
	 * @param \Sessions\Model_Session $product
	 * @param \Model_User $user
	 * @return \Sessions\Context_Sessions
	 */
	public static function forge(Model_Product $product, \Model_User $user=null) {
		if(empty($user)) {
			$user = \Model_User::find(\Auth::get_user()->id);
		}	
		return new Context_Products($product, $user);
	}
	
	public function create() : bool {
		$self = $this->product->paid_by == $this->user->id;
		
		if($self) {
			return self::_is_active() || self::_is_administrator();
		} else {
			return self::_is_administrator();
		}
	}
	
	public function update() : bool {
		if($this->_is_settled()) {
			return false;
		}
		
		return ($this->_is_owner() && !$this->product->generated) ||self::_is_administrator();
	}
	
	public function view() : bool {
		return $this->_is_owner() || self::_is_administrator() || $this->_is_participant();
	}
	
	public function delete() : bool {
		return $this->update();
	}
	
	/**
	 * Is product created by user
	 * @return type
	 */
	private function _is_owner() : bool {
		return $this->product->paid_by == $this->user->id && !$this->product->generated;
	}
	
	/**
	 * Is user enrolled for this product
	 * @return type
	 */
	private function _is_participant() : bool {
		return ($this->product->get_enrollment($this->user->id) !== null);
	}
		
	/**
	 * Has the product been settled
	 */
	private function _is_settled() : bool {
		return $this->product->settled;
	}
}