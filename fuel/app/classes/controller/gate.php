<?php
/**
 * Controller regulating access to protected pages. 
 * Implement this controller when a page needs logging in. 
 */
class Controller_Gate extends Controller_Base
{

	public function action_index($arg=null) {
		// Nothing to see here. This class only acts as a login gate.
		Response::redirect_back();
	}
	
	public function before() {
		parent::before();

		if (Request::active()->controller !== 'Controller_Gate' or ! in_array(Request::active()->action, array('login', 'logout')))
		{
			if (!Auth::check())
			{
				// No user is logged in, redirect to login
				Response::redirect('gate/login');
			}
		}
	}

	public function action_login() {
		// Already logged in
		Auth::check() and Response::redirect('gate');
		$val = Validation::forge();

		if (Input::method() == 'POST')
		{
			$val->add('email', 'Email or Username')
			    ->add_rule('required');
			$val->add('password', 'Password')
			    ->add_rule('required');

			if ($val->run()) {
				if (!Auth::check()) {
					if (Auth::login(Input::post('email'), Input::post('password'))) {
						if (($id = Auth::get_user_id()) !== false) {
							// Find user
							$current_user = Model\Auth_User::find($id[1]);
							Session::set_flash('success', e('Welcome, '.$current_user->username));
											
							// Does the user want to be remembered?
							if(Input::post('rememberme', false)) {
								Auth::remember_me();
							} else {
								Auth::dont_remember_me();
							}
							
							Response::redirect_back();	
						}
					} else {
						$this->template->set_global('login_error', 'Login failed!');
					}
				} else {
					$this->template->set_global('login_error', 'Already logged in!');
				}
			}
		}

		$this->template->title = 'Login';
		$this->template->content = View::forge('gate/login', array('val' => $val), false);
	}

	public function action_logout() {
		Auth::logout();
		Response::redirect('gate');
	}
}
