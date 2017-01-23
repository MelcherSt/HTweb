<?php
/**
 * Controller regulating access to protected pages. 
 * Implement this controller when a page needs logging in. 
 */
class Controller_Gate extends Controller_Base
{
	/**
	 * Set by child when it serves public content
	 * @var boolean 
	 */
	protected $public_access = false; 
	
	/**
	 * Set when incoming request is made by a user not currently logged-in
	 * @var boolean 
	 */
	protected $public_request = false; 
	
	public function before() {		
		parent::before();

		if (Request::active()->controller !== 'Controller_Gate' or ! in_array(Request::active()->action, array('login', 'logout'))) {
			if (!Auth::check()) {
				// No user is logged in, this is a public request
				$this->public_request = true;
				
				if(!$this->public_access) {
					// If the page is not publicly accessible and we're not logged-in, redirect to login
					Response::redirect('gate/login');
				}	
			}
		}
	}

	public function action_login() {
		// Already logged in
		Auth::check() and Response::redirect('gate');
		
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
							if(Input::post('rememberme', false) == 'on' ? true : false) {
								Auth::remember_me();
							} else {
								Auth::dont_remember_me();
							}
							
							$dest = Input::post('destination', '/');
							Response::redirect($dest);
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
		Response::redirect('/');
	}
}
