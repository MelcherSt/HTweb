<?php

namespace Products;

class Controller_Admin_Macro extends \Controller_Core_Theme {
	
	public function before() {
		$this->permission = Context_Products::MGMT_PERM;
		parent::before();
	}
	
	public function action_create() {
		$this->push_js('products-modals');
		
		// Option assoc. array [user_id => user_name]
		$options = [];
		foreach(\Model_User::get_by_state() as $user) {
			$options[$user->id] = $user->get_fullname();
		}
		
		$data['active_user_options'] = $options;
		$this->page_title = __('product.admin.create_macro.title');
		$this->sub_title = __('actions.create');		
		$this->content = \View::forge('admin/macro/create', $data);
	}
	
	public function post_create() {
		$val = Model_ProductDefinition::validate('create');		
		if($val->run()) {
			$product = Model_ProductDefinition::forge([
				'name' => $name = $val->validated('name'),
				'paid_by' => \Input::post('payer-id', \Auth::get_user()->id),
				'cost' => $val->validated('cost'),		
			]);
			$product->save();
			\Session::set_flash('success', __('product.alert.success.create_macro', ['name' => $name]));
		} else {	
			\Session::set_flash('error', $val->error_message());
		}
		\Response::redirect_back('/products/admin');
	}
	
	public function post_delete() {
		$product_id = \Input::post('macro-id', null);
		$product = \Utils::check_non_null(Model_ProductDefinition::find($product_id));
			
		$name = $product->name;
		$product->delete();
		\Session::set_flash('success', __('product.alert.success.remove_macro', ['name' => $name]));
		\Response::redirect_back();	
	}
	
	public function action_execute(int $productdef_id=null) {
		if(empty($productdef_id)) {
			$defs = Model_ProductDefinition::query()->get();
		} else {
			$defs = [$productdef_id];
		}
		
		$errors = 0;
		$now = new \DateTime('first day of this month');
		foreach($defs as $def) {	
			if(empty($def->last_execution)) {
				$date = new \DateTime('first day of last month');
			} else {
				$date = new \DateTime($def->last_execution);
				if($date->modify('+1month') == $now) {
					continue;
				}
			}
			
			while($date < $now) {
				$date->modify('+1month');
				$product = Model_Product::forge([
					'name' => $def->name,
					'date' => $date->format(\Utils::MYSQL_DATE_FORMAT),
					'notes' => 'Automatically generated from product definition',
					'paid_by' => $def->paid_by,
					'cost' => $def->cost,	
					'generated' => 1,
				]);

				try {
					\DB::start_transaction();
					
					$def->last_execution = $date->format(\Utils::MYSQL_DATETIME_FORMAT);
					$def->save();
					
					$product->save();
					$users = \Model_User::query()->where('active', true)->get();
					foreach($users as $user) {
						$user_product = Model_User_Product::forge([
							'user_id' => $user->id,
							'product_id' => $product->id,
							'amount' => 1,
						]);
						$user_product->save();
					}
					
					\DB::commit_transaction();
				} catch (Exception $ex) {
					\DB::rollback_transaction();
					$errors++;
				}
			}
		}
		
		if($errors > 0) {
			\Session::set_flash('success', __('product.alert.error.macros_executed', ['num' => $errors]));
		} else {
			\Session::set_flash('success', __('product.alert.success.macros_executed'));
		}	
		\Response::redirect_back();
	}
}
