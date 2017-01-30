<?php

namespace Api;

class Controller_RestPaginated extends Controller_RestAuth {
	
	protected $offset = null;
	protected $limit = null;
	protected $order = 'asc';
			
	function before() {
		parent::before();
		
		// Set pagination values
		$this->offset = \Input::get('offset');
		$this->limit = \Input::get('limit');
		
		$order_param = \Input::get('order');
		if($order_param == 'desc' || $order_param == 'asc'){
			$this->order =$order_param;
		}	
	}
}