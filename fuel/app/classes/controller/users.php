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

		if ($val->run()) {		
			$user->phone = Input::post('phone', '');
			$user->active = Input::post('active', 1);
			$user->email = Input::post('email');

			$cur_pass = Input::post('old_password');
			$pass = Input::post('password');
			$pass2 = Input::post('password2');
			
			if(isset($pass) && (trim($pass) != '')) {
				if($pass != $pass2) {
					Session::set_flash('error', e('Password remains unchanged. Given passwords do not match.'));
				} else {
					if (!Auth::change_password($cur_pass, $pass)){
						Session::set_flash('error', e('Password remains unchanged. Wrong current password.'));
					}
				}
			}
			
			if ($user->save()) {
				Session::set_flash('success', e('Updated user account'));
				Response::redirect('users/edit');
			} else {
				Session::set_flash('error', e('Could not update user account '));
			}
		} else {
			if (Input::method() == 'POST')
			{		
				$user->phone = $val->validated('phone');
				$user->password = $val->validated('password');
				$user->email = $val->validated('email');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = "Users";
		$this->template->content = View::forge('users/edit');

	}
}
