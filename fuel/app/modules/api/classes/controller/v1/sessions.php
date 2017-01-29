<?php

namespace Api;

class Controller_v1_Sessions extends Controller_RestAuth {
	
	public function action_index() {
		
	}
	
	public function action_admin() {
		return array_values(\Sessions\Model_Session::query()->where('settled', 0)->get());
	}
}

