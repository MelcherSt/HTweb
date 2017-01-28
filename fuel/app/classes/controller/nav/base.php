<?php

/**
 * Renders the navigation bar which is included in the default template
 */
class Controller_Nav_Base extends Controller_Gate {
	
	public function before() {
		$this->public_access = true;
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
			
			if(Auth::get_user()->group->id == 6) {
				$menu_items = array_merge($menu_items, [
					//['stats', __('stats.title'), 'fa-line-chart'],
					//['content/posts', __('content.post.title'), 'fa-file-text'],
				] );
			} 
		}
		
		// Override template
		return \View::forge('nav/index', [
			'menu_items' => $menu_items,
			'active_item' => $active_item,
			]);
	}
}