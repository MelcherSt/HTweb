<?php

namespace Api;

class Controller_RestPaginated extends \Controller_RestAuth {
	
	protected $offset;
	protected $limit;
	protected $order;
			
	function before() {
		// Set pagination values
		$this->offset = \Input::get('offset', 0);
		$this->limit = \Input::get('limit', null);
		$this->order = \Input::get('order', 'asc');
	}
	
}