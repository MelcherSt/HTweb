<?php
class Controller_Users extends Controller_Gate
{
	public function action_me() {
		\Response::redirect('users/view/' . \Auth::get_user_id()[1]);
	}

	public function action_view($id = null)	{	
		$user = \Model_User::find($id);
		$data['user'] = $user;	
		
		$this->template->title = "User";
		$this->template->subtitle = $user->get_fullname();
		$this->template->content = View::forge('users/view', $data);
	}

	public function action_edit()	{
		$user = \Model_User::find(\Auth::get_user_id()[1]);
		$val = \Model_User::validate('edit');

		if ($val->run())
		{
			$user->username = Input::post('username');
			$user->surname = Input::post('surname');
			$user->name = Input::post('name');
			$user->phone = Input::post('phone');
			$user->active = Input::post('active');
			$user->start_year = Input::post('start_year');
			$user->end_year = Input::post('end_year');
			$user->points = Input::post('points');
			$user->balance = Input::post('balance');
			$user->password = Input::post('password');
			$user->group_id = Input::post('group_id');
			$user->email = Input::post('email');

			if ($user->save())
			{
				Session::set_flash('success', e('Updated user account'));

				Response::redirect('admin/users');
			}

			else
			{
				Session::set_flash('error', e('Could not update user account '));
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
				$user->balance = $val->validated('balance');
				$user->password = $val->validated('password');
				$user->group_id = $val->validated('group_id');
				$user->email = $val->validated('email');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = "Users";
		$this->template->content = View::forge('admin/users/edit');

	}
}
