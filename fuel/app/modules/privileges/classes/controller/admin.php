<?php

namespace Privileges;

class Controller_Admin extends \Controller_Secure {
	
	function before() {
		$this->permission_required = 'privileges.administration';
		parent::before();
	}	
	
	public function action_index() {
		$this->template->title = __('privileges.title');
		$permissions = \Auth\Model\Auth_Permission::find('all');
		$this->template->content = \View::forge('admin/index', ['permissions' => $permissions]);
	
	}
}
