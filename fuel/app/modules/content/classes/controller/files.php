<?php

namespace Content;

class Controller_Files extends \Controller_Gate {
	
	public function action_index($id) {
		return \Response::forge($id);
	}
	
	public function action_view($id) {
		return \Response::forge($id);
	}
}

