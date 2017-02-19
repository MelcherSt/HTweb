<?php

namespace Privileges;

class Controller_Privileges extends \Controller_Secure {
		
	public function action_index() {
		$this->template->title = __('privileges.title');
		
		
		if(\Auth::has_access('privileges.administration')) {
			$permissions = \Auth\Model\Auth_Permission::find('all');
		} else {
			$permissions = \Auth::get_user()->permissions;
		}
		
		
		$this->template->content = \View::forge('index', ['permissions' => $permissions]);
	}
}
