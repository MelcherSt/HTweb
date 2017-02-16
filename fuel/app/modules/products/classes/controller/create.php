<?php

namespace Products;

class Controller_Create extends \Controller_Gate {
	
	public function post_index() {
		$user_ids = \Input::post('users', []);
		$val = Model_Product::validate('create');	
		
		if($val->run() && sizeof($user_ids) != 0) {
			$product = Model_Product::forge([
				'name' => \Input::post('name'),
				'notes' => \Input::post('notes', null),
				'paid_by' => \Auth::get_user()->id,
				'cost' => \Input::post('cost'),		
				'approved' => 1, // Products are approved upon receipt creation
			]);
			\Security::htmlentities($product)->save();
			
			foreach($user_ids as $user_id) {
				$amount = \Input::post($user_id, 1);	
				if (!($amount > 1 && $amount < 20)) {
					$amount = 1;
				}
				
				$user_product = Model_User_Product::forge([
					'user_id' => $user_id,
					'product_id' => $product->id,
					'amount' => $amount,
				]);
				\Security::htmlentities($user_product)->save();
			}
			
			
		} else {	
			\Session::set_flash('error', $val->error());
		}
		
		\Response::redirect('products');
	}
}
