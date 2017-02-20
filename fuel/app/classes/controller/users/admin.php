<?php
class Controller_Users_Admin extends Controller_Secure
{

	public function before() {
		if(!\Auth::has_access('users.administration')) {
			throw new \HttpNoAccessException();
		}
		parent::before();
	}
	
	public function action_index() {
		$this->template->title = "Users";
		$this->template->page_title = "Users";
		$this->template->subtitle = __('privileges.perm.manage');	
		$data['users'] = \Model_User::find('all');	
		$this->template->content = View::forge('users/admin/index', $data);
	}

	public function action_create() {
		if (Input::method() == 'POST') {
			$val = \Model_User::validate('create');

			if ($val->run())
			{
				$salt = \Utils::rand_str(12);
				$user = \Model_User::forge(array(
					'username' => Input::post('username'),
					'surname' => Input::post('surname'),
					'name' => Input::post('name'),
					'phone' => Input::post('phone', ''),
					'active' => (Input::post('active') == 'on'),
					'start_year' => Input::post('start_year'),
					'end_year' => Input::post('end_year'),
					'points' => Input::post('points', 0),
					'iban' => Input::post('iban', ''),
					'password' => Auth::instance()->hash_password(Input::post('password') . $salt),
					'salt' => $salt,
					'group_id' => Input::post('group_id', 3),
					'email' => Input::post('email'),
					'last_login'      => 0,
					'previous_login'  => 0,
					'login_hash'      => '',
					'created_at'	  => \Date::forge()->get_timestamp(),
					'updated_at'      => 0,
				));

				try {
					\Security::htmlentities($user)->save();
					Session::set_flash('success', __('user.alert.success.update'));
					Response::redirect('users/admin');
				} catch (Exception $ex) {
					Session::set_flash('error', __('user.alert.error.update'));
				}
			}
			else
			{
				Session::set_flash('error', $val->error());
			}
		}

		$this->template->title = "Users";
		$this->template->content = View::forge('users/admin/create');

	}

	public function action_edit($id = null)	{
		$user = \Model_User::find($id);
		
		if(!isset($user)) {
			\Utils::handle_irrecoverable_error(__('user.alert.error.no_id', ['id' => $id]));
		}
		
		$data['user'] = $user;	
		$this->template->title = "Users";
		$this->template->content = View::forge('users/admin/edit', $data);			
	}
	
	public function post_edit($id = null) {
		$user = \Model_User::find($id);
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
			$user->iban = Input::post('iban', null);
			$user->lang = Input::post('lang', null);
			
			
			try {
				\Security::htmlentities($user)->save();
				Session::set_flash('success', __('user.alert.success.update'));
				Response::redirect('users/admin');
			} catch (Exception $ex) {
				Session::set_flash('error', __('user.alert.error.update'));
			}
		} else {
			if (Input::method() == 'POST') {		
				\Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}
	}

	public function action_delete($id = null)
	{
		$user = \Model_User::find($id);
		if (isset($user)) {
			$user->delete();

			Session::set_flash('success', e('Deleted user #'.$id));
		} else {
			Session::set_flash('error', e('Could not delete user #'.$id));
		}
		Response::redirect('users/admin');
	}
}