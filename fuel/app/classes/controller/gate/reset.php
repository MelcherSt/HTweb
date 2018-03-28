<?php

class Controller_Gate_Reset extends Controller_Core_Theme {
	
	public function before() {
		$this->public_content = true;
		parent::before();
	}

	public function action_index() {			
		if($this->public_request) {		
			$token = Input::get('token');
			$this->title_page = __('gate.reset.title');
			
			if(empty($token)) {
				$this->content = View::forge('gate/reset');
			} else {
				// Check token
				$reset_token = Model_ResetToken::query()
						->where('token', $token)
						->get_one();
				
				if(empty($reset_token)) {
					Utils::handle_irrecoverable_error(__('gate.alert.error.invalid_token'));
				}
				
				// Check token expiry
				$now = new DateTime();
				$expiry = (new DateTime())->setTimestamp($reset_token->created_at)->modify('+1 hour');

				if(!($now < $expiry)) {
					// Token was issued more than an hour ago.. delete it
					Utils::handle_recoverable_error(__('gate.alert.error.expired_token'), '/');
					$reset_token->delete();
				}
				
				$this->content = View::forge('gate/reset_pass', ['token' => $token]);
			}
		} else {
			throw new HttpNotFoundException();
		}
	}
	
	public function post_pass() {
		$email = Input::post('email');
		$token = Input::post('token');
		$password = Input::post('password');
		$password2 = Input::post('password2');
		$redirect = '/gate/reset?token=' . $token;
		
		if($password == $password2) {
			$reset_token = Model_ResetToken::query()
				->where('token', $token)
				->get_one();
			
			// Check if token exists
			if(empty($reset_token)) {
				Utils::handle_irrecoverable_error(__('gate.alert.error.invalid_token'));
			}
			
			// Check if token is linked to given mail address
			$user = Model_User::find($reset_token->user_id);
			if($user->email != $email) {
				Utils::handle_recoverable_error(__('gate.alert.error.invalid_token'),$redirect);
			}
			
			// Check token expiry
			$now = new DateTime();
			$expiry = (new DateTime())->setTimestamp($reset_token->created_at)->modify('+1 hour');
			
			if(!($now < $expiry)) {
				// Token was issued more than an hour ago.. delete it
				Utils::handle_recoverable_error(__('gate.alert.error.expired_token'), '/');
				$reset_token->delete();
			}
			
			$salt = \Str::random();
			$user->salt = $salt;
			$user->password = Auth::instance()->hash_password($password . $salt);
			$user->save();
			
			// Delete the token
			$reset_token->delete();	
		} else {
			Utils::handle_recoverable_error(__('gate.alert.error.pass_mismatch'), $redirect);
		}
		
		\Session::set_flash('success', __('gate.alert.success.pass_changed'));
		Response::redirect('/gate/login');
	}
	
	public function post_index() {
		// Find address
		$mail = Input::post('email');
		$user = Model_User::query()
				->where('email', $mail)
				->get_one();
				
		// Generate token
		$token = bin2hex(openssl_random_pseudo_bytes(15));
		
		if(empty($user) || empty($user->email)) {
			// Do nothing?
		} else {
			// Check for existing token
			
			$reset_token = Model_ResetToken::query()
				->where('user_id', $user->id)
				->get_one();
			
			if(isset($reset_token)) {
				// Already a reset in-progess. Stop it.
				$reset_token->delete();
			} 
			
			// Save token to db
			Model_ResetToken::forge([
				'user_id' => $user->id,
				'token' => $token,
			])->save();

			// Send mail - or don't
			try {			
				$email = \Email::forge();
				$email->to($mail, $user->get_fullname());
				$email->subject(__('gate.reset.mail.subject'));
				$email->body(__('gate.reset.mail.body', ['link' => 'hettribunaal.nl/gate/reset?token=' . $token]));
				$email->send();	
			} catch (\Email\EmailSendingFailedException $ex) {
				// Caught
			}
		}
			
		\Session::set_flash('success', __('gate.alert.success.reset_mail'));
		Response::redirect('/gate/login');
	}
}
