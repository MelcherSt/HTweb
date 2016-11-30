<?php

namespace Session;

class Controller_Session extends \Controller_Gate {
	
	
	public function action_index() {
		\Response::redirect('session/items/'.date('Y-m-d'));
	}
	
	public function action_yesterday() {
		\Response::redirect('session/items/'.date('Y-m-d', strtotime('-1 day')));
	}
	
	public function action_today() {
		\Response::redirect('session/items/'.date('Y-m-d'));
	}
	
	public function action_tomorrow() {
		\Response::redirect('session/items/'.date('Y-m-d', strtotime('+1 day')));
	}
	
	public function action_items($id=null) {
		$this->template->title = 'Sessions';
		
		if(isset($id)) {
			// Get model, if exists.
			
			// If not, create	
			
			//TODO: use presenters for views
			$this->template->content = $id;
		} else {
			$this->template->content = 'All sessions you participated in.';
		}
		
		
	}
}

