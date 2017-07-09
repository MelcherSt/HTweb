<?php

namespace Sessions;

class Controller_Convert extends \Controller_Secure {
	
	public function action_index(string $date) {
		$session = \Utils::valid_session($date);
		$context = Context_Sessions::forge($session);
		
		if($context->convert()) {
			try {
				\DB::start_transaction();
				$dateStr = \Utils::format_date($session->date, \Utils::DATE_FORMAT);
				$product = \Products\Model_Product::forge([
					'paid_by' => $session->paid_by,
					'cost' => $session->cost,
					'date' => $session->date,
					'name' => 'Session at ' . $dateStr,
					'notes' => 'This product was automatically created from the sesion at ' . $dateStr
				]);
				$product->save();

				$enrollments = $session->enrollments;
				foreach($enrollments as $enrollment) {
					$user_product = \Products\Model_User_Product::forge();
					$user_product->user = $enrollment->user;
					$user_product->product = $product;
					$user_product->amount = $enrollment->guests + 1;
					$user_product->save();
				}
				$session->delete();
				\DB::commit_transaction();
				\Response::redirect('products/view/'.$product->id);
			} catch (Exception $ex) {
				\DB::rollback_transaction();
				\Utils::handle_recoverable_error($ex->message);
			}		
		} else {
			throw new \HttpNotAuthorizedException();
		}
	}
}
