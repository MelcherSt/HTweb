<?php
 
namespace Receipts;

class Controller_Receipts extends \Controller_Secure {
	
	public function action_index() {
		$this->template->title = __('receipt.title');
		
		$data['receipts'] = Model_Receipt::get_by_user(\Auth::get_user_id()[1]);
		
		$this->template->content = \View::forge('index', $data);
	}
	
	public function action_view($id) {
		if (isset($id)) {
			$receipt = Model_Receipt::find($id);
			if (!$receipt) {
				\Utils::handle_irrecoverable_error(__('receipt.alert.error.no_receipt', ['id' => $id]));
			}
			
			$data['receipt'] = $receipt;
			$this->template->title = __('receipt.title_admin');
			$this->template->subtitle = date('l j F Y', strtotime($receipt->date));
			$this->template->content = \View::forge('view', $data);
		} 
	}
	
	
}

