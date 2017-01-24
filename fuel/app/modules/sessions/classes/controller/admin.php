<?php

namespace Sessions;

class Controller_Admin extends \Controller_Gate {
	
	public function action_index() {
		return \Response::forge('Sessions Admin placeholder');
	}
}