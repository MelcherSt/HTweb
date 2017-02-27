<?php

/**
 * Renders the navigation bar which is included in the default template
 */
class Controller_Nav_Base extends Controller_Secure {
	
	public function before() {
		$this->public_content = true;
		parent::before();
	}
	
	public function action_index($active_item) {
		$menu_items = []; 
		
		if(!$this->public_request) {
			$menu_items = [
					['sessions', __('session.title'), 'fa-cutlery'],
					['products', __('product.title'), 'fa fa-shopping-bag'],				
					['receipts', __('receipt.title'), 'fa-money'],
					['wall', __('user.wall.title'), 'fa-id-card'],
			];
		}
		
		// Override template (i.e. need to explicitly return view)
		return \View::forge('nav/index', [
			'menu_items' => $menu_items,
			'active_item' => $active_item,
			]);
	}
}