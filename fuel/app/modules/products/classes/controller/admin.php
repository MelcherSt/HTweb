<?php

namespace Products;

class Controller_Admin extends \Controller_Secure {
	
	public function before() {
		$this->permission_required = Context_Products::MGMT_PERM;
		parent::before();
	}
	
	public function action_index() {
		$this->push_js('products-modals');
		
		$this->template->title = __('product.title_admin');
		$this->template->page_title = __('product.title_admin');
		$data['products'] = Model_Product::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
}

