<?php
namespace Products;

class Presenter_Overview extends \Presenter {
	
	public function view() {
		$this->set('products', Model_Product::get_by_user(\Auth::get_user()->id));
		$this->set('show_colors', true);
		$this->set('is_admin', false);
	}
	
	public function admin() {
		$this->set('products', Model_Product::query()->where('settled', 0)->get());
		$this->set('show_colors', false);
		$this->set('is_admin', true);
	}
}

