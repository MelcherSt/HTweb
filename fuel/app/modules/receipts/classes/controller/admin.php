<?php

namespace Receipts;

class Controller_Admin extends \Controller_Admin {
	
	
	
	public function action_index() {
		$this->template->title = 'Receipts';
		
		$data['sessions'] = \Sessions\Model_Session::get_ready_for_settlement();
		
		$this->template->content = \View::forge('admin/index', $data);
	}	
	
	public function post_create() {
		$receipt = \Receipts\Model_Receipt::forge();
		$receipt->notes = \Input::post('notes', '');
		$receipt->date = date('Y-m-d');
		$receipt->save();
		
		// handle sessions
		$session_string = rtrim(\Input::post('sessions', ''), ',');
		$session_ids = explode(',', $session_string);
		$this->handle_sessions($session_ids, $receipt);
		
		// TODO: handle products
		
		\Response::redirect('/receipts/admin');
	}
	
	
	public function handle_sessions($session_ids, $receipt) {
		foreach($session_ids as $session_id) {
			$session = \Sessions\Model_Session::find($session_id);
		
			// If there is no session, skip
			if (!$session || $session->settled) {
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
			
			if ($dish_count == 0) {
				$max_loss = 2; // No dishwashers means less loss
			} else if ($dish_count == 1) {
				$dish_gain = 2; // Double the multiplier for a single dishwasher
			}
			if ($cook_count == 2) {
				$cook_gain = 1; // Two cooks split the multiplier
			}
			
			// If there are no people or no cook, skip this session
			if ($total_count == 0 || $cook_count == 0) {
				continue;
			} else {
				$avg_cost = $session->cost / $total_count;
			}
		
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
					// Update values if receipt already exists
					$user_receipt->balance += $temp_balance;
					$user_receipt->points += $temp_points;
				}
				$user_receipt->save();	
			}
		}
	}
}