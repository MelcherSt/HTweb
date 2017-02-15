<?php

class Controller_Gate_Forgot extends Controller_Gate {
	
	public function before() {
		$this->public_access = true;
		parent::before();
	}

	public function action_index() {	
		if($this->public_request) {		
			$token = Input::get('token');
			$this->template->title = "Reset password";
			
			if(empty($token)) {
				$this->template->content = View::forge('gate/forgot');
			} else {
				// Check token
				$reset_token = Model_ResetToken::query()
						->where('token', $token)
						->get_one();
				
				if(empty($reset_token)) {
					Utils::handle_irrecoverable_error('Invalid token');
				}
				
				// Check token expiry
				$now = new DateTime();
				$expiry = (new DateTime())->setTimestamp($reset_token->created_at)->modify('+1 hour');

				if(!($now < $expiry)) {
					// Token was issued more than an hour ago.. delete it
					Utils::handle_recoverable_error('Token expired', '/');
					$reset_token->delete();
				}
				
				$this->template->content = View::forge('gate/forgot_reset', ['token' => $token]);
			}
		} else {
			throw new HttpNotFoundException();
		}
	}
	
	public function post_reset() {
		$token = Input::post('token');
		$password = Input::post('password');
		$password2 = Input::post('password2');
		
		if($password == $password2) {
			$reset_token = Model_ResetToken::query()
				->where('token', $token)
				->get_one();
			
			if(empty($reset_token)) {
				Utils::handle_irrecoverable_error('Invalid token');
			}
			
			// Check token expiry
			$now = new DateTime();
			$expiry = (new DateTime())->setTimestamp($reset_token->created_at)->modify('+1 hour');
			
			if(!($now < $expiry)) {
				// Token was issued more than an hour ago.. delete it
				Utils::handle_recoverable_error('Token expired', '/');
				$reset_token->delete();
			}
			
			$user = Model_User::find($reset_token->user_id);
			$salt = \Utils::rand_str(12);
			$user->salt = $salt;
			$user->password = Auth::instance()->hash_password($password . $salt);
			$user->save();
			
			// Delete the token
			$reset_token->delete();	
		} else {
			Utils::handle_recoverable_error('Passwords do not match', '/gate/forgot?token=' . $token);
		}
		
		\Session::set_flash('success','Password has been changed!');
		Response::redirect('/');
	}
	
	public function post_index() {
		// Find address
		$mail = Input::post('email');
		$user = Model_User::query()
				->where('email', $mail)
				->get_one();
				
		// Generate token
		$token = bin2hex(openssl_random_pseudo_bytes(15));
		
		if(empty($user)) {
			// Do nothing?
		} else {
			// Check for existing token
			
			$reset_token = Model_ResetToken::query()
				->where('user_id', $user->id)
				->get_one();
			
			if(isset($reset_token)) {
				// Already a reset in-progess. Stop it.
			} else {
				// Save token to db
				Model_ResetToken::forge([
					'user_id' => $user->id,
					'token' => $token,
				])->save();

				// Send mail - or don't
				$email = Email::forge();
				$email->to($mail, $user->get_fullname());
				$email->subject('Password reset requested');
				$email->body('A password reset was requested for the account registered using this mail addresss on Het Tribunaal Web. '
						. 'If you did not request a password reset please ignore this mail. Otherwise, '
						. 'visit this link to reset your password: www.hettribunaal.nl/gate/forgot?token=' . $token
						. ' Note that the link is only valid for one hour after the inital request!');
				$email->send();
			}
		}
			
		\Session::set_flash('success','If the given mail address is associated with an account, you will receive a password reset link.');
		Response::redirect('/');
	}
}
