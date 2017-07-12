<?php
/**
 * Controller regulating access to protected pages. 
 * Implement this controller when a page needs logging in. 
 */
class Controller_Gate extends Controller_Core_Theme
{
	public function action_login() {
		// Already logged in
		Auth::check() and Response::redirect('/');
		
		$val = Validation::forge();

		if (Input::method() == 'POST') {
			// Create validator rules			
			$val->add('email', 'Email or Username')
			    ->add_rule('required');
			$val->add('password', 'Password')
			    ->add_rule('required');

			// Run validator
			if ($val->run()) {
				if (!Auth::check()) {
					if (Auth::login(Input::post('email'), Input::post('password'))) {
						if (($id = Auth::get_user_id()) !== false) {			
							// Does the user want to be remembered?
							if((Input::post('rememberme') == 'on' ? true : false)) {
								Auth::remember_me();
							} else {
								Auth::dont_remember_me();
							}
							
							$dest = Input::post('destination', '/');
							Response::redirect($dest);
						}
					} else {
						\View::set_global('login_error', 'Login failed!');
					}
				} else {
					\View::set_global('login_error', 'Already logged in!');
				}
			}
		}

		$this->title = 'Login';
		$this->content = View::forge('gate/login', array('val' => $val), false);
	}

	public function action_logout() {
		Auth::dont_remember_me();
		Auth::logout();
		Response::redirect('/');
	}
}
