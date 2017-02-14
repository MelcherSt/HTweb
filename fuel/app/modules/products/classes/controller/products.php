<?php

namespace Products;

class Controller_Products extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = __('product.title');
		$this->template->content = \View::forge('index');
	}
	
	public function action_view($id=null) {
		$product = Model_Product::find($id);
		
		if(!isset($product)) {
			\Utils::handle_irrecoverable_error(__('product.alert.error.no_product', ['id' => $id]));
		}
		
		$data['left_content'] = \View::forge('details', ['product'=>$product]);	
		$data['right_content'] = \View::forge('participants', ['product'=>$product]);	
	
		$this->template->page_title = __('product.name');
		$this->template->title = $product->name . ' - ' . __('product.title');
		$this->template->subtitle = $product->name;		
		$this->template->content = \View::forge('layout/splitview', $data);
	}
}