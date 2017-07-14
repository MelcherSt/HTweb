<?php

namespace Products;

class Controller_Enrollments extends \Controller_Core_Theme {
	
	/**
	 * Handle enrollment deletion
	 * @param string $date
	 */
	public function post_delete(string $product_id) {		
		$product = \Utils::check_non_null(Model_Product::find($product_id), 
				__('product.alert.error.not_found', ['id' => $product_id]));
		
		$context = Context_Products::forge($product);	
		$user_id = \Input::post('user-id');	
		
		if ($context->update()) {
			$enrollment = $product->get_enrollment($user_id);
			
			if(isset($enrollment)) {
				$name = $enrollment->user->name;
				
				try {
					$enrollment->delete();	
					
					if ($product->count_participants() == 0) {
						$product->delete();
					}
					
					\Session::set_flash('success', __('session.alert.success.remove_enroll', ['name' => $name]));
					\Response::redirect('products');
				} catch (\Database_Exception $ex) {
					\Session::set_flash('error', __('session.alert.error.remove_enroll', ['name' => $name]) . '<br>' . $ex->getMessage());	
				}	
			} else {
				\Utils::handle_recoverable_error(__('session.alert.error.no_enrollment', ['name' => '']));
			}	
		} else {
			\Utils::handle_recoverable_error(__('actions.no_perm'));
		}
		\Response::redirect_back();
	}
}