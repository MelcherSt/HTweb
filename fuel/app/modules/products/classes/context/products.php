<?php

namespace Products;

final class Context_Products {
		
	private $product;
	private $user;
	
	/**
	 * Name of the ORMAuth permission granting complete administration privileges.
	 * @var type 
	 */
	const MGMT_PERM = 'products.administration';
	
	private function __construct(Model_Product $product, \Model_User $user) {
		$this->product = $product;
		$this->user = $user;
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
	
	public function create() {
		$self = $this->product->paid_by == $this->user->id;
		
		if($self) {
			return $this->_is_active();
		} else {
			return $this->_is_administrator();
		}
	}
	
	public function update() {
		if($this->_is_settled()) {
			return false;
		}
		
		return $this->_is_owner() || $this->_is_administrator();
	}
	
	public function view() {
		return $this->_is_owner() || $this->_is_administrator() || $this->_is_participant();
	}
	
	public function delete() {
		return $this->update();
	}
	
	/**
	 * Is product created by user
	 * @return type
	 */
	private function _is_owner() {
		return $this->product->paid_by == $this->user->id;
	}
	
	/**
	 * Is user enrolled for this product
	 * @return type
	 */
	private function _is_participant() {
		return ($this->product->get_enrollment($this->user->id) !== null);
	}
	
	/**
	 * Has the current user administration privileges
	 * @return boolean
	 */
	private function _is_administrator(){
		return \Auth::has_access(static::MGMT_PERM);
	}
	
	/**
	 * Is the current user active
	 * @return type
	 */
	private function _is_active() {
		return $this->user->active;
	}
	
	/**
	 * Has the product been settled
	 */
	private function _is_settled() {
		return $this->product->settled;
	}
}