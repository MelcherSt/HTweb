<?php
namespace Products;

class Presenter_MacroOverview extends \Presenter {
	
	public function view() {
		$this->set('products', Model_ProductDefinition::find('all'));
	}
}

