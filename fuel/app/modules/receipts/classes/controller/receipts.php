<?php
 
namespace Receipts;

class Controller_Receipts extends \Controller_Core_Theme {
	
	public function action_index() {
		$this->title = __('receipt.title');
		
		$data['receipts'] = Model_Receipt::get_by_user($current_user->id)->order('date', 'desc');
		
		$this->content = \View::forge('index', $data);
	}
	
	public function action_view($id) {
		if (isset($id)) {
			$receipt = Model_Receipt::find($id);
			if (!$receipt) {
				\Utils::handle_irrecoverable_error(__('receipt.alert.error.no_receipt', ['id' => $id]));
			}
			
			$data['receipt'] = $receipt;
			$this->title = __('receipt.title_admin');
			$this->sub_title = date('l j F Y', strtotime($receipt->date));
			$this->content = \View::forge('view', $data);
		} 
	}
	
	
}

