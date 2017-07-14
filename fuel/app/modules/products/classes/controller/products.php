<?php

namespace Products;

class Controller_Products extends \Controller_Core_Theme {
	
	public function action_index() {
		$this->push_js('products-modals');
		$this->page_title = __('product.title');
		$this->content = \View::forge('index',['is_admin' => \Auth::has_access('products.administration')]);
	}
	
	public function action_view(int $product_id) {		
		$product = \Utils::check_non_null(Model_Product::find($product_id), 
				__('product.alert.error.not_found', ['id' => $product_id]));
		
		if(!Context_Products::forge($product)->view()) {
			throw new \HttpNoAccessException();
		}
		
		$this->push_js('products-modals');
		$this->page_title = __('product.name');
		$this->title = $product->name . ' - ' . __('product.title');
		$this->sub_title = $product->name;		
		$this->content = \View::forge('view', ['product' => $product]);
	}
	
	public function action_create() {
		$this->push_js('products-modals');
		$this->title = __('product.title');
		$this->page_title = __('product.name');
		$this->sub_title = __('actions.create');		
		$this->content = \Presenter::forge('create');
	}
	
	public function post_create() {
		$user_ids = \Input::post('users', []);
		$val = Model_Product::validate('create');	
		
		if($val->run() && sizeof($user_ids) > 0) {
			$product = Model_Product::forge([
				'name' => $name = $val->validated('name'),
				'date' => $val->validated('date'),
				'notes' => $val->validated('notes'),
				'paid_by' => \Input::post('payer-id', \Auth::get_user()->id),
				'cost' => $val->validated('cost'),		
			]);
			
			if(!Context_Products::forge($product)->create()) {
				throw new \HttpNoAccessException();
			}	

			try {
				\DB::start_transaction();
				
				e($product)->save();	
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
					$user_product->save();
				}
				
				\DB::commit_transaction();
			} catch (Exception $ex) {
				\DB::rollback_transaction();
				throw $ex;
			}
			
			\Session::set_flash('success', __('product.alert.success.create_product', ['name' => $name]));
		} else if (sizeof($user_ids == 0)) {
			\Session::set_flash('error', __('product.alert.error.no_users_selected'));
		} else {	
			\Session::set_flash('error', $val->error_message());
		}		
		\Response::redirect(\Input::referrer());
	}
	
	public function post_update(int $product_id=null) {
		$product = \Utils::check_non_null(Model_Product::find($product_id), 
				__('product.alert.error.not_found', ['id' => $product_id]));
		
		if(!Context_Products::forge($product)->update()) {
			throw new \HttpNoAccessException();
		}
		
		$val = Model_Product::validate('update');
		if($val->run([], true)) {
			$product->cost = $val->validated('cost');
			$product->notes = $val->validated('notes');
			$product->save();
			\Session::set_flash('success', __('product.alert.success.update_product'));
		} else {
			\Session::set_flash('error', $val->error_message());
		}
		\Response::redirect_back();	
	}
	
	public function post_delete() {
		$product_id = \Input::post('product-id', null);
		$product = \Utils::check_non_null(Model_Product::find($product_id), 
				__('product.alert.error.not_found', ['id' => $product_id]));
		
		if(!Context_Products::forge($product)) {
			throw new \HttpNoAccessException();
		}

		$name = $product->name;
		$product->delete();
		\Session::set_flash('success', __('product.alert.success.remove_product', ['name' => $name]));
		\Response::redirect_back();
	}
}