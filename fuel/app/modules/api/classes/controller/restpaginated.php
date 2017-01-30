<?php

namespace Api;

class Controller_RestPaginated extends Controller_RestAuth {
	
	protected $offset = null;
	protected $limit = null;
	protected $order = 'asc';
			
	
	function before() {
		parent::before();
		
		/* 
		 * Set pagination values. Note that \Fuel\Orm\Query has been changed
		 * to correctly deal with null values for row offset and row limit.
		 */		
		$this->offset = \Input::get('offset');
		$this->limit = \Input::get('limit');
		
		// Only set order when appropriate
		$order_param = \Input::get('order');
		if(in_array($order_param, ['asc', 'desc'])){
			$this->order =$order_param;
		}	
	}
	
	/**
	 * Subsequently paginate and execute query
	 * @param \Orm\Query $query
	 * @return array
	 */
	protected final function paginate_query(\Orm\Query $query) : array {
		return $query->rows_offset($this->offset)
			->rows_limit($this->limit)
			->order_by('date', $this->order)
			->get();
	}
	
	public final function after($array) : \Response {
		// Wrap return body
		return parent::after([
			'total' => sizeof($array),
			'rows' => array_values($array)
		]);
	}
}