<?php

namespace Privileges;

class Controller_Privileges extends \Controller_Core_Theme {
		
	public function action_index() {
		$this->title = __('privileges.title');
		$permissions = \Auth::get_user()->permissions;
		$this->content = \View::forge('index', ['permissions' => $permissions]);
	}
}
