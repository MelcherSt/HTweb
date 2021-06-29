<?php
class Controller_Users extends Controller_Core_Theme
{
	public function action_index() {
		$data['users'] = Model_User::get_by_state();
		$this->title = __('user.name_plural');
		$this->content = View::forge('users/index', $data);
	}
	
	public function action_me() {
		\Response::redirect('users/view/' . \Auth::get_user()->id);
	}
	
	public function action_avatar($id=null) {
		$user = \Model_User::find($id);
		
		if(!isset($user) || empty($user->avatar)) {
			Response::redirect('/assets/img/wall/placeholder.jpg');
		}		
		Response::redirect('/files/users/avatar/'. $user->avatar);
	}

	public function action_view($id = null)	{	
		$user = \Utils::valid_user($id);
		
		$data['user'] = $user;	
		$this->title = __('user.name');
		$this->sub_title = $user->get_fullname();
		$this->content = View::forge('users/view', $data);
	}

	public function action_edit()	{
		$user = \Model_User::get_current();
		$val = \Model_User::validate('edit');			
		$val->add_field('password', 'Wachtwoord', 'min_length[5]');
		$val->add_field('password2', 'Wachtwoord', 'required_with[password]|match_field[password]');
		
		if ($val->run()) {		
			$user->phone = Input::post('phone', '');
			$user->email = Input::post('email', '');
			$user->iban = Input::post('iban', null);
			$user->lang = Input::post('lang', null);

			$cur_pass = $val->validated('old_password');
			$pass = $val->validated('password');
			if(!empty($pass)) {
				// Generate new salt
				$new_salt = \Str::random();
				if ($hashed_pass = Auth::instance()->hash_password($pass . $new_salt)) {
					$user->salt = $new_salt;
					$user->password = $hashed_pass;
				} else {
					Session::set_flash('error', __('user.alert.error.cur_pass'));
				}
			}
			
			try {
				\Security::htmlentities($user)->save();
				Session::set_flash('success', __('user.alert.success.update'));
				Response::redirect('users/edit');
			} catch (Exception $ex) {
				Session::set_flash('error', __('user.alert.error.update'));
			}
		} else {
			if (Input::method() == 'POST') {		
				\Session::set_flash('error', $val->error());
			}

			\View::set_global('user', $user, false);
		}

		$this->title = __('user.edit.title');
		$this->sub_title = $user->get_fullname();
		$this->content = View::forge('users/edit');
	}
}
