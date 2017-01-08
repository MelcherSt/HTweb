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
		if($this->public_request) {
			$menu_items = [];
		} else {
			if(Auth::get_user()->group->id == 5) {
				$menu_items = [
					['sessions', __('session.title'), 'fa-cutlery'],
					['products', __('product.title'), 'fa fa-shopping-bag'],
					['wall', __('user.wall.title'), 'fa-id-card'],
					['receipts', __('receipt.title'), 'fa-money'],
					array('stats', __('stats.title'), 'fa-line-chart'),
					array('content/posts', __('content.post.title'), 'fa-file-text'),
				];
			} else {
				$menu_items = [
					['sessions', __('session.title'), 'fa-cutlery'],
					['products', __('product.title'), 'fa fa-shopping-bag'],				
					['receipts', __('receipt.title'), 'fa-money'],
					['wall', __('user.wall.title'), 'fa-id-card'],
				];
			}
		}
		
		// Override template
		return \View::forge('nav/index', [
			'menu_items' => $menu_items,
			'active_item' => $active_item,
			]);
	}
}