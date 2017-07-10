<?php

namespace Products;

class Controller_Admin extends \Controller_Secure {
	
	public function before() {
		$this->permission = Context_Products::MGMT_PERM;
		parent::before();
	}
	
	public function action_index() {
		$this->push_js('products-modals');
		
		$this->template->title = __('product.title');
		$this->template->page_title = __('product.title');
		$this->template->subtitle = __('privileges.perm.manage');
		$data['products'] = Model_Product::query()->where('settled', 0)->get();			
		$this->template->content = \View::forge('admin/index', $data);
	}
	
	public function action_create() {
		$this->push_js('products-modals');
		
		// Option assoc. array [user_id => user_name]
		$options = [];
		foreach(\Model_User::get_by_state() as $user) {
			$options[$user->id] = $user->get_fullname();
		}
		
		// Users sorted on active state
		$users_sorted = \Model_User::query()
			->where('id', '!=', 0)
			->order_by('active', 'desc')
			->get();
		
		$data['active_user_options'] = $options;
		$data['users'] = $users_sorted;	
		$this->template->title = __('product.title');
		$this->template->page_title = __('product.name');
		$this->template->subtitle = __('actions.create');		
		$this->template->content = \View::forge('admin/create', $data);
	}
}

