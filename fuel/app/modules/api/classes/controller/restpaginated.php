<?php

namespace Api;

/**
 * Controller for dealing with basic paginated API requests.
 * This controller accepts request in the form of '/$endpoint?limit=x&offset=y&order=(asc|desc)
 */
class Controller_RestPaginated extends Controller_RestAuth {
	
	/**
	 * Rows offset 
	 * @var int 
	 */
	protected $offset = null;
	
	/**
	 * Row limit
	 * @var int 
	 */
	protected $limit = null;
	
	/**
	 * Ordering
	 * @var string Either 'asc' for ascending or 'desc' for descending order. 
	 */
	protected $order = 'asc';
			
	
	final function before() {
		parent::before();
		
		/* 
		 * Set pagination values. Note that \Fuel\Orm\Query has been changed
		 * to correctly deal with null values for row offset and row limit.
		 */		
		$this->offset = \Input::get('offset');
		$this->limit = \Input::get('limit');
		
		// Only set order when appropriate
		if(in_array(($order_param = \Input::get('order')), ['asc', 'desc'])){
			$this->order =$order_param;
		}	
	}
	
	/**
	 * Execute given query with the pagination details as requested.
	 * @param \Orm\Query $query The query
	 * @param string $order_column Column name on which to order rows. Defaults to id.
	 * @return array Query result
	 */
	protected final function paginate_query(\Orm\Query $query, string $order_column = 'id') : array {
		return $query->rows_offset($this->offset)
			->rows_limit($this->limit)
			->order_by($order_column, $this->order)
			->get();
	}
	
	public final function after($array) : \Response {
		$array = empty($array) ? [] : $array;
		
		// Wrap return body
		return parent::after([
			'total' => sizeof($array),
			'rows' => array_values($array)
		]);
	}
}