<?php

class Controller_Gate_Admin extends Controller_Core_Theme {
	
	public function before() {
		$this->permission = 'gate.administration';
		parent::before();
	}
	
	public function action_index() {
		$tokens = Model_ResetToken::find('all');
		$this->title = 'Reset things';
		$this->content = View::forge('gate/reset_admin', ['tokens' => $tokens]);
	}
}