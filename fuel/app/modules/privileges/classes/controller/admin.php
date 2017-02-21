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
	
	public function action_view($id=null) {
		$this->push_js('admin/privileges-modals');
		
		if(empty($permission = \Auth\Model\Auth_Permission::find($id))) {
			\Utils::handle_irrecoverable_error(__('privileges.alert.error.no_permission', ['id' => $id]));
		}
		
		$this->template->title = __('privileges.title');
		$this->template->content = \View::forge('admin/view', ['permission' => $permission]);		
	}
	
	/**
	 * Create new user <-> permission association. 
	 * @param type $id
	 */
	public function post_create($id=null) {
		if(empty($permission = \Auth\Model\Auth_Permission::find($id))) {
			\Utils::handle_irrecoverable_error(__('privileges.alert.error.no_permission', ['id' => $id]));
		}
		
		$user = \Utils::valid_user(\Input::post('user-id'));
		
		$userperm = \Auth\Model\Auth_Userpermission::forge([
			'user_id' => $user->id,
			'perms_id' => $permission->id,
			'actions' => [],
		]);
		
		try {
			$userperm->save();
			\Session::set_flash('success', __('privileges.alert.success.create_enroll', ['name' => $user->name]));	
		} catch (\Fuel\Core\Database_Exception $ex) {
			\Session::set_flash('error', __('privileges.alert.error.create_enroll', ['name' => $user->name]) . '<br>' . $ex->getMessage());	
		}
		
		\Response::redirect_back();
	}
	
	public function post_delete($id=null) {
		$user_id = \Input::post('user-id', null);
		$user = \Utils::valid_user($user_id);
		
		
		$permission = \Auth\Model\Auth_Permission::find($id);
		
		if(empty($permission)) {
			\Utils::handle_irrecoverable_error(__('privileges.alert.error.no_permission', ['id' => $perm_id]));
		}
		
		$user_permission = \Auth\Model\Auth_Userpermission::query()
				->where('user_id', $user->id)
				->where('perms_id', $permission->id)
				->get_one();
				
				
		$name = $user->name;
		$user_permission->delete();
			
		\Session::set_flash('success', __('product.alert.success.remove_product', ['name' => $name]));
		\Response::redirect_back();
	}
}
