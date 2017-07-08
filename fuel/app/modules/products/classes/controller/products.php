<?php

namespace Products;

class Controller_Products extends \Controller_Secure {
	
	public function action_index() {
		$this->push_js('products-modals');
		
		$this->template->title = __('product.title');
		$this->template->content = \View::forge('index');
	}
	
	public function action_view($id=null) {
		if(empty($product = Model_Product::find($id))) {
			\Utils::handle_irrecoverable_error(__('product.alert.error.no_product', ['id' => $id]));
		}
		
		if(!Context_Products::forge($product)->view()) {
			throw new \HttpNoAccessException();
		}
		
		$this->template->page_title = __('product.name');
		$this->template->title = $product->name . ' - ' . __('product.title');
		$this->template->subtitle = $product->name;		
		$this->template->content = \View::forge('view', ['product' => $product]);
	}
	
	public function post_create() {
		$user_ids = \Input::post('users', []);
		$val = Model_Product::validate('create');
		
		if($val->run(null, true) && sizeof($user_ids) != 0) {
			$product = Model_Product::forge([
				'name' => ($name = \Input::post('name')),
				'date' => $val->validated('date'),
				'notes' => $val->validated('notes'),
				'paid_by' => \Input::post('payer-id', \Auth::get_user()->id),
				'cost' => \Input::post('cost'),		
				'approved' => 1, // Products are approved upon receipt creation
			]);
			
			if(!Context_Products::forge($product)->create()) {
				throw new \HttpNoAccessException();
			}
			
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
			
			\Session::set_flash('success', __('product.alert.success.create_product', ['name' => $name]));
		} else {	
			\Session::set_flash('error', $val->error());
		}
		
		\Response::redirect_back();
	}
	
	public function post_delete() {
		$product_id = \Input::post('product-id', null);
		$product = Model_Product::find($product_id);
		
		if(empty($product)) {
			\Utils::handle_irrecoverable_error(__('product.alert.error.no_product', ['id' => $product_id]));
		}
		
		$context = Context_Products::forge($product);	
		if($context->delete()) {
			$name = $product->name;
			$product->delete();
			
			\Session::set_flash('success', __('product.alert.success.remove_product', ['name' => $name]));
			\Response::redirect_back();
		} else {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
	}
}