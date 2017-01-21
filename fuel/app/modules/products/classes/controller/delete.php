<?php

namespace Products;

class Controller_Delete extends \Controller_Gate {
	
	public function post_index() {
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
