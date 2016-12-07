<?php
 
namespace Receipts;

class Controller_Receipts extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = 'Receipts';
		
		$data['sessions'] = \Sessions\Model_Session::get_ready_for_settlement();
		
		$this->template->content = \View::forge('index', $data);
	}
	
	
}

