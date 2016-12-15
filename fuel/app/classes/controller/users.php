<?php
class Controller_Users extends Controller_Gate
{
	public function action_index() {
		$data['users'] = Model_User::get_by_state();
		$this->template->title = __('user.name_plural');
		$this->template->content = View::forge('users/index', $data);
	}
	
	public function action_me() {
		\Response::redirect('users/view/' . \Auth::get_user_id()[1]);
	}
	
	public function action_avatar($id=null) {
		$user = \Model_User::find($id);
		
		if(!isset($user) || empty($user->avatar)) {
			Response::redirect('/assets/img/wall/placeholder.jpg');
		}		
		Response::redirect('/files/users/avatar/'. $user->avatar);
	}
	
	public function action_lang($lang) {
		if (in_array($lang, array('en', 'nl'))) {
			$user = \Model_User::find(\Auth::get_user_id()[1]);
			$user->lang = $lang;
			$user->save();
			\Response::redirect_back();
		}
	}

	public function action_view($id = null)	{	
		$user = \Model_User::find($id);
		
		if(!isset($user)) {
			\Utils::handle_irrecoverable_error(__('user.alert.error.no_id', ['id' => $id]));
		}
		
		$data['user'] = $user;	
		$this->template->title = __('user.name');
		$this->template->subtitle = $user->get_fullname();
		$this->template->content = View::forge('users/view', $data);
	}

	public function action_edit()	{
		$user = \Model_User::find(\Auth::get_user_id()[1]);
		$val = \Model_User::validate('edit');		
		$val->add('password', 'new password')->add_rule('min_length', 5);
		$val->add('password2', 're-type Password')->add_rule('match_field', 'password');
		
		if ($val->run()) {		
			$user->phone = Input::post('phone', '');
			$user->active = Input::post('active', 1);
			$user->email = Input::post('email', '');
			$user->iban = Input::post('iban', null);
			$user->lang = Input::post('lang', null);

			$cur_pass = Input::post('old_password');
			$pass = Input::post('password');
			
			if(!empty($pass)) {
				if (!Auth::change_password($cur_pass, $pass)){
					Session::set_flash('error', __('user.alert.error.cur_pass'));
				}
			}
			
			// Custom configuration for this upload
			$config = array(
				'path' => DOCROOT.'files/users/avatar/',
				'randomize' => true,
				'max_size' => 1024 * 1000,
				'ext_whitelist' => array('jpg', 'jpeg', 'gif', 'png'),
			);

			// process the uploaded files in $_FILES
			Upload::process($config);

			// if there are any valid files
			if (Upload::is_valid())
			{
				// save them according to the config
				Upload::save(0);
				$value = Upload::get_files();  
				$user->avatar =  $value[0]['saved_as'];
			} 
			
			if ($user->save()) {
				Session::set_flash('success', __('user.alert.success.update'));
				Response::redirect('users/edit');
			} else {
				Session::set_flash('error', __('user.alert.error.update'));
			}
		} else {
			if (Input::method() == 'POST')
			{		
				$user->phone = $val->validated('phone');
				$user->password = $val->validated('password');
				$user->email = $val->validated('email');
				$user->iban = $val->validated('iban');

				Session::set_flash('error', $val->error());
			}

			$this->template->set_global('user', $user, false);
		}

		$this->template->title = __('user.edit.title');
		$this->template->subtitle = $user->get_fullname();
		$this->template->content = View::forge('users/edit');
	}
}
