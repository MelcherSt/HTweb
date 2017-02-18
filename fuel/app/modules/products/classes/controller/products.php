<?php

namespace Products;

class Controller_Products extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = __('product.title');
		$this->template->content = \View::forge('index');
	}
	
	public function action_view($id=null) {
		if(empty($product = Model_Product::find($id))) {
			\Utils::handle_irrecoverable_error(__('product.alert.error.no_product', ['id' => $id]));
		}
		
		$this->template->page_title = __('product.name');
		$this->template->title = $product->name . ' - ' . __('product.title');
		$this->template->subtitle = $product->name;		
		$this->template->content = \View::forge('view', ['product' => $product]);
	}
	
	public function post_create() {
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
	
	public function post_delete() {
		$product_id = \Input::post('product_id', null);
		$product = Model_Product::find($product_id);
		$redirect = '/products';
		
		if(empty($product)) {
			// Drop out
			\Utils::handle_irrecoverable_error(__('product.alert.error.no_product', ['id' => $id]));
		}
		
		$context = Auth_Context_Product::forge($product);	
		if($context->has_access(['product.delete'], true)) {
			$name = $product->name;
			$product->delete();
			
			\Session::set_flash('success', __('product.alert.success.remove_[product', ['name' => $name]));
			\Response::redirect($redirect);
		} else {
			// Report error
			\Utils::handle_recoverable_error($context->get_message(), $redirect);
		}
		
	
	}
}