<?php

namespace Privileges;

class Controller_Admin extends \Controller_Core_Theme {
	
	function before() {
		$this->permission = 'privileges.administration';
		parent::before();
	}	
	
	public function action_index() {
		$this->title = __('privileges.title');
		$this->title_page = __('privileges.title');
		$this->title_sub = __('privileges.perm.manage');
		
		$permissions = \Auth\Model\Auth_Permission::find('all');
		$this->content = \View::forge('admin/index', ['permissions' => $permissions]);
	}
	
	public function action_view($id=null) {
		$this->push_js('admin/privileges-modals');
		
		if(empty($permission = \Auth\Model\Auth_Permission::find($id))) {
			\Utils::handle_irrecoverable_error(__('privileges.alert.error.no_permission', ['id' => $id]));
		}
		
		$this->title = __('privileges.title');
		$this->content = \View::forge('admin/view', ['permission' => $permission]);		
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
		
		$user_permission = \Auth\Model\Auth_Userpermission::forge([
			'user_id' => $user->id,
			'perms_id' => $permission->id,
			'actions' => [],
		]);
		
		try {
			$user_permission->save();
			\Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.permissions.user_' . $user->id);
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
		try {
			$user_permission->delete();
			\Cache::delete(\Config::get('ormauth.cache_prefix', 'auth').'.permissions.user_' . $user->id);
			\Session::set_flash('success', __('privileges.alert.success.remove_enroll', ['name' => $name]));	
		} catch (\Fuel\Core\Database_Exception $ex) {
			\Session::set_flash('error', __('privileges.alert.error.remove_enroll', ['name' => $name]) . '<br>' . $ex->getMessage());	
		}	
		\Response::redirect_back();
	}
	
	/**
	 * Flush all permission by deleting permission cache
	 */
	public function action_reload() {
		\Cache::delete_all(\Config::get('ormauth.cache_prefix', 'auth').'.permissions');
		\Response::redirect_back();
	}
}
