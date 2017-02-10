<?php
class Controller_Users_Admin extends Controller_Gate
{

	public function before() {
		if(!\Auth::has_access('users.administration')) {
			throw new \HttpNoAccessException();
		}
		parent::before();
	}
	
	public function action_index()
	{
		$data['users'] = \Model_User::find('all');
		$this->template->title = "Users";
		$this->template->content = View::forge('users/admin/index', $data);

	}

	public function action_view($id = null)
	{
		\Response::redirect('/users/view/'. $id);
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
					'active' => Input::post('active'),
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

				if ($user and $user->save())
				{
					Session::set_flash('success', e('Added user #'.$user->id.'.'));

					Response::redirect('admin/users');
				}

				else
				{
					Session::set_flash('error', e('Could not save user.'));
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

	public function action_edit($id = null)
	{
		$user = \Model_User::find($id);
		$val = \Model_User::validate('edit');

		if ($val->run())
		{
			$salt = \Utils::rand_str(12);
			$user->username = Input::post('username');
			$user->surname = Input::post('surname');
			$user->name = Input::post('name');
			$user->phone = Input::post('phone', '');
			$user->active = Input::post('active', 1);
			$user->start_year = Input::post('start_year', 0);
			$user->end_year = Input::post('end_year', 0);
			$user->points = Input::post('points', 0);
			$user->iban = Input::post('iban', '');
			$user->password = (Auth::instance()->hash_password(Input::post('password') . $salt));
			$user->salt = $salt;
			$user->group_id = Input::post('group_id', 3);
			$user->email = Input::post('email', '');

			if ($user->save())
			{
				Session::set_flash('success', e('Updated user #' . $id));

				Response::redirect('admin/users');
			}

			else
			{
				Session::set_flash('error', e('Could not update user #' . $id));
			}
		}

		else
		{
			if (Input::method() == 'POST')
			{
				$user->username = $val->validated('username');
				$user->surname = $val->validated('surname');
				$user->name = $val->validated('name');
				$user->phone = $val->validated('phone');
				$user->active = $val->validated('active');
				$user->start_year = $val->validated('start_year');
				$user->end_year = $val->validated('end_year');
				$user->points = $val->validated('points');
				$user->iban = $val->validated('iban');
				$user->password = $val->validated('password');
				$user->group_id = $val->validated('group_id');
				$user->email = $val->validated('email');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = "Users";
		$this->template->content = View::forge('users/admin/edit');

	}

	public function action_delete($id = null)
	{
		if ($user = \Model_User::find($id))
		{
			$user->delete();

			Session::set_flash('success', e('Deleted user #'.$id));
		}

		else
		{
			Session::set_flash('error', e('Could not delete user #'.$id));
		}

		Response::redirect('admin/users');

	}

}
