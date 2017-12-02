<?php
namespace Products;

class Presenter_Create extends \Presenter {
	
	public function view() {
		$this->set('users', \Model_User::get_by_state());
	}
	
	public function admin() {
		// Option assoc. array [user_id => user_name]
		$options = [];
		foreach(\Model_User::get_by_state() as $user) {
			$options[$user->id] = $user->get_fullname();
		}
		
		// Users sorted on active state
		$users_sorted = \Model_User::query()
			->where('id', '>', 1)
			->order_by('active', 'desc')
			->get();
		
		$this->set('active_user_options', $options);
		$this->set('users', $users_sorted);
		$this->set('is_admin', true);
	}
}