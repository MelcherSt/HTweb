<?php
 
namespace Receipts;

class Controller_Receipts extends \Controller_Gate {
	
	public function action_index() {
		$this->template->title = 'Receipts';
		
		$data['sessions'] = \Sessions\Model_Session::get_ready_for_settlement();
		
		$this->template->content = \View::forge('index', $data);
	}
	
	public function post_create() {
		$receipt = \Receipts\Model_Receipt::forge();
		$receipt->notes = \Input::post('notes', '');
		$receipt->date = date('Y-m-d');
		$receipt->save();
		
		// handle sessions
		$session_string = rtrim(\Input::post('sessions', ''), ',');
		$session_ids = explode(',', $session_string);
		
		foreach($session_ids as $session_id) {
			$session = \Sessions\Model_Session::find($session_id);
		
			if (!$session) {
				// err
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
			
			if ($dish_count == 0) {
				$max_loss = 2;
			} else if ($dish_count == 1) {
				$dish_gain = 2;
			}
			
			if ($cook_count == 2) {
				$cook_gain = 1;
			}
			
			
			// Average cost
			if ($total_count == 0) {
				$avg_cost = 0;
			} else {
				$avg_cost = $session->cost / $total_count;
			}
		
			foreach($session->enrollments as $enrollment) {
				$user_id = $enrollment->user->id;
				$guests = $enrollment->guests;
				
				$temp_points = 0;
				$temp_balance = 0.0;
				
				$temp_balance -= $avg_cost + $avg_cost * $guests;
				
				// Default point loss
				$temp_points -= $max_loss + $max_loss * $guests;
				
				if ($enrollment->cook) {
					$temp_points += $cook_gain * $total_count;
				}
				
				if ($enrollment->dishwasher) {
					$temp_points += $dish_gain * $total_count;
				}
				
				if ($session->paid_by == $user_id) {
					$temp_balance += $session->cost;
				}
				
				$user_receipt = Model_User_Receipt::get_by_user($user_id, $receipt->id);
				
				if (!isset($user_receipt)) {
					// Create new one
					$user_receipt = \Receipts\Model_User_Receipt::forge(array(
						'user_id' => $enrollment->user->id,
						'receipt_id' => $receipt->id,
						'balance' => $temp_balance,
						'points' => $temp_points,
					));	
				} else {
					$user_receipt->balance += $temp_balance;
					$user_receipt->points += $temp_points;
				}
	
				
				$user_receipt->save();	
			}
		}
		
		\Response::redirect('\receipts');
		
	}
}

