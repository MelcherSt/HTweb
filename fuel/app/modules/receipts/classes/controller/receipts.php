<?php
 
namespace Receipts;

class Controller_Receipts extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = 'My Receipts';
		
		$data['receipts'] = Model_Receipt::get_by_user(\Auth::get_user_id()[1]);
		
		$this->template->content = \View::forge('index', $data);
	}
	
	public function action_view($id) {
		if (isset($id)) {
			$receipt = Model_Receipt::find($id);
			if (!$receipt) {
				\Utils::handle_irrecoverable_error("No receipt with id " . $id);
			}
			
			$data['receipt'] = $receipt;
			$this->template->title = 'Receipt';
			$this->template->subtitle = date('l j F Y', strtotime($receipt->date));
			$this->template->content = \View::forge('view', $data);
		} 
	}
	
	
}

