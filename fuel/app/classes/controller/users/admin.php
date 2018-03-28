<?php
class Controller_Users_Admin extends Controller_Core_Theme {
	
	/**
	 * Default group for new users is the 'Users' group with id 3
	 */
	const DEFAULT_GROUP = 3;

	public function before() {
		$this->permission = 'users.administration';
		parent::before();
	}
	
	public function action_index() {
		$this->title = __('user.name_plural');
		$this->sub_title = __('privileges.perm.manage');	
		$data['users'] = \Model_User::find('all');	
		$this->content = View::forge('users/admin/index', $data);
	}

	public function action_create() {
		if (Input::method() == 'POST') {
			$val = \Model_User::validate('create');

			if ($val->run())
			{
				$salt = \Str::random();
				$user = \Model_User::forge(array(
					'username' => Input::post('username'),
					'surname' => Input::post('surname'),
					'name' => Input::post('name'),
					'phone' => Input::post('phone', ''),
					'active' => (Input::post('active') == 'on'),
					'start_year' => Input::post('start_year'),
					'end_year' => Input::post('end_year'),
					'points' => Input::post('points'),
					'iban' => Input::post('iban', ''),
					'password' => Auth::instance()->hash_password(Input::post('password') . $salt),
					'salt' => $salt,
					'group_id' => Input::post('group_id', self::DEFAULT_GROUP),
					'email' => Input::post('email'),
					'last_login'      => 0,
					'previous_login'  => 0,
					'login_hash'      => '',
				));

				try {
					\Security::htmlentities($user)->save();
					Session::set_flash('success', __('user.alert.success.update'));
					Response::redirect('users/admin');
				} catch (Exception $ex) {
					Session::set_flash('error', __('user.alert.error.update') . '<br>' . $ex->getMessage());
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->title_page = __('user.name');
		$this->title_sub = __('actions.create');
		$this->content = View::forge('users/admin/create');

	}

	public function action_edit($id = null)	{
		$user = \Utils::valid_user($id);
		
		$data['user'] = $user;	
		$this->title = __('user.name_plural');
		$this->content = View::forge('users/admin/edit', $data);			
	}
	
	public function post_edit($id = null) {
		$user = \Utils::valid_user($id);
		$val = \Model_User::validate('edit');		
		$val->add('password', 'new password')->add_rule('min_length', 5);
		$val->add('password2', 're-type Password')->add_rule('match_field', 'password');
		
		if ($val->run()) {	
			$user->username = Input::post('username');
			$user->surname = Input::post('surname');
			$user->name = Input::post('name');
			$user->phone = Input::post('phone', '');
			$user->group_id = Input::post('group_id', 3);
			$user->active = (Input::post('active') == 'on');
			$user->start_year = Input::post('start_year', 0);
			$user->end_year = Input::post('end_year', 0);
			$user->points = Input::post('points', 0);
			$user->email = Input::post('email', '');
			$user->iban = Input::post('iban');
			$user->lang = Input::post('lang');	
			
			try {
				\Security::htmlentities($user)->save();
				Session::set_flash('success', __('user.alert.success.update'));
				Response::redirect('users/admin');
			} catch (Database_Exception $ex) {
				Session::set_flash('error', __('user.alert.error.update' . '<br>' . $ex->getMessage()));
			}
		} else {
			if (Input::method() == 'POST') {		
				\Session::set_flash('error', $val->error());
			}
		}
		
		\View::set_global('user', $user, false);
	}

	public function action_delete($id = null) {
		$user = \Model_User::find($id);
		try {
			$user->delete();
			Session::set_flash('success', e('Deleted user #'.$id));
		} catch (Database_Exception $ex) {
			Session::set_flash('error', e('Could not delete user #'.$id . '<br>' . $ex->getMessage()));
		}	
		Response::redirect('users/admin');
	}
}