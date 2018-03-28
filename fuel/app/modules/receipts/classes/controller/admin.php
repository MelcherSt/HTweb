<?php

namespace Receipts;

class Controller_Admin extends \Controller_Core_Theme {
	
	public function before() {
		$this->permission = 'receipts.administration';
		parent::before();
	}
	
	public function action_create() {
		$this->title =__('receipt.title_admin');
		$this->title_sub = __('actions.create');
		$data['sessions'] = \Sessions\Model_Session::fetch_setteable();
		$data['products'] = \Products\Model_Product::get_settleable();
		$this->content = \View::forge('admin/create', $data);
	}
	
	public function action_index() {
		$this->title = __('receipt.title_admin');
		$this->title_page = __('receipt.title_admin');
		$this->title_sub = __('privileges.perm.manage');	
		$data['receipts'] = Model_Receipt::find('all');		
		$this->content = \View::forge('admin/index', $data);
	}

	public function post_create() {
		$session_ids = \Input::post('sessions');
		$product_ids = \Input::post('products');
		
		if(sizeof($session_ids) == 0 && sizeof($product_ids) == 0) {
			\Utils::handle_recoverable_error('A receipt should at least settle one or more sessions/products.', '/receipts/admin/create');
		}
		
		$receipt = \Receipts\Model_Receipt::forge();
		$receipt->notes = \Input::post('notes', '');
		$receipt->date = date('Y-m-d');
		\Security::htmlentities($receipt)->save();

		if(!empty($session_ids)) {
			$this->handle_sessions($session_ids, $receipt);
		}
		
		if(!empty($product_ids)) {
			$this->handle_products($product_ids, $receipt);
		}		
		\Response::redirect('/receipts/admin');
	}
	
	public function post_delete() {
		$id = \Input::post('receipt_id', null);
		
		if(isset($id)) {
			if(!($receipt = Model_Receipt::find($id))) {
				\Utils::handle_irrecoverable_error('Unable to delete non-existant receipt.');
			}
			
			$user_receipts = $receipt->users;	
			foreach($user_receipts as $user_receipt) {
				// Restore the points
				$user = $user_receipt->user;
				$user->points -= $user_receipt->points;
				$user->save();
			}
			
			$session_receipts = $receipt->sessions;
			foreach($session_receipts as $session_receipt) {
				// Undo settled state
				$session = $session_receipt->session;
				$session->settled = false;
				$session->save();		
			}
			
			$product_receipts = $receipt->products;
			foreach($product_receipts as $product_receipt) {
				// Undo settled state
				$product = $product_receipt->product;
				$product->settled = false;
				$product->save();
			}
			
			if($receipt->delete()) {
				\Session::set_flash('success', ('Receipt has been deleted.'));
			} else {
				\Session::set_flash('error', ('Could not delete receipt'));	
			}

			\Response::redirect('/receipts/admin/');
		}
		\Utils::handle_irrecoverable_error('No receipt id specified for deletion');
	}
	
	/**
	 * Create Product receipts and update all User receipts for given product. 
	 * Empty or incomplete products will be deleted in the process.
	 * @param type $product_ids
	 * @param type $receipt
	 */
	public function handle_products($product_ids, $receipt) {
		foreach($product_ids as $product_id) {
			$product = \Products\Model_Product::find($product_id);
			
			if(empty($product) || $product->settled || !$product->approved) {
				//continue;
			}
			
			$product->settled = true;
			$product->save();
			
			$total_count = $product->count_total_participants();
			
			// If there are no people skip this product
			if ($total_count == 0 || $cost = 0) {
				// Remove product alltogether
				$product->delete();
				continue;
			} else {
				$avg_cost = $product->cost / $total_count;
			}
			
			// Create a product receipt to relate the product to this receipt
			$product_receipt = Model_Product_Receipt::forge([
					'product_id' => $product->id,
					'receipt_id' => $receipt->id
				]);
			$product_receipt->save();
			
			
			// Apply debits
			foreach($product->users as $product_user) {
				$user_id = $product_user->user->id;
					
				// Apply avg cost * amount
				$temp_balance = -($avg_cost * $product_user->amount);
				
				$precision = 10;
				
				// Update user receipt
				$this->update_user_receipt($user_id, $receipt->id, round($temp_balance, $precision));
			}
			
			// Process payer seperately (payer may not be a participant)
			$payer = $product->get_payer();	
			$this->update_user_receipt($payer->id, $receipt->id, round($product->cost, $precision));	
		}
	}
	
	/**
	 * Create Session receipts and update all User receipts for given sessions. 
	 * Empty or incomplete sessions will be deleted in the process.
	 * @param type $session_ids
	 * @param type $receipt
	 */
	public function handle_sessions($session_ids, $receipt) {
		foreach($session_ids as $session_id) {
			$session = \Sessions\Model_Session::find($session_id);
		
			// If there is no session, skip
			if (empty($session) || $session->settled) {
				continue;
			} 
			
			$session->settled = true;
			$session->save();
			
			// Default loss
			$max_loss = 4;
			
			// Gain mutlipliers
			$cook_gain = 2;
			$dish_gain = 1;
			
			$dish_count = $session->count_dishwashers();
			$cook_count = $session->count_cooks();
			$total_count = $session->count_total_participants();
			
			// If there are no people or no cook, skip this session
			if ($total_count == 0 || $cook_count == 0) {
				// Remove session altogether
				$session->delete();
				continue;
			} else {
				$avg_cost = $session->cost / $total_count;
			}
			
			
			if ($dish_count == 0) {
				$max_loss = 2; // No dishwashers means less loss
			} else if ($dish_count == 1) {
				$dish_gain = 2; // Double the multiplier for a single dishwasher
			}
			if ($cook_count == 2) {
				$cook_gain = 1; // Two cooks split the multiplier
			}
			
			// Create a session receipt to relate the session to this receipt
			$session_receipt = Model_Session_Receipt::forge(array(
					'session_id' => $session->id,
					'receipt_id' => $receipt->id
				));
			$session_receipt->save();
			
		
			// Create a receipt for each user in the session
			foreach($session->enrollments as $enrollment) {
				$user_id = $enrollment->user->id;
				$guests = $enrollment->guests;
		
				$temp_points = -($max_loss + $max_loss * $guests);
				$temp_balance = -($avg_cost + $avg_cost * $guests);
				
				if ($enrollment->cook) {
					$temp_points += $cook_gain * $total_count;
				}			
				if ($enrollment->dishwasher) {
					$temp_points += $dish_gain * $total_count;
				}			
				if ($session->paid_by == $user_id) {
					$temp_balance += $session->cost;
				}
				
				$precision = 10;
				
				// Update user receipt
				$p_delta = round($temp_points, $precision);
				$b_delta = round($temp_balance, $precision);
				$this->update_user_receipt($user_id, $receipt->id, $b_delta, $p_delta);
				
				// Apply points delta to actual user 
				$user = \Model_User::find($user_id);
				$user->points += round($temp_points, $precision);
				$user->save();
			}
		}
	}
	
	/**
	 * Update the user receipt for given user on given receipt with given deltas
	 * @param type $user_id
	 * @param type $receipt_id 
	 * @param float $b_delta Point delta
	 * @param float $p_delta Balance delta
	 */
	private function update_user_receipt($user_id, $receipt_id, $b_delta, $p_delta=0) {
		$user_receipt = Model_User_Receipt::get_by_user($user_id, $receipt_id);
		
		if (!isset($user_receipt)) {
			$user_receipt = \Receipts\Model_User_Receipt::forge(array(
				'user_id' => $user_id,
				'receipt_id' => $receipt_id,
				'balance' => $b_delta,
				'points' => $p_delta,
			));	
		} else {
			$user_receipt->balance += $b_delta;
			$user_receipt->points += $p_delta;
		}
		$user_receipt->save();
	}
}