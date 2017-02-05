<?php

namespace Api;

/**
 * Enables pagination on API controllers. 
 * This controller accepts request in the form of '/$endpoint?limit=x&offset=y&order=(asc|desc)
 * To generate paginated output, explicitly return data using the Response_Paginated wrapper.
 */
class Controller_Paginable extends Controller_Base {
	
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
			
	/**
	 * Column on which to sort. Default is id.
	 * @var string 
	 */
	protected $sort = 'id';
	
	final function before() {
		parent::before();
		
		/* 
		 * Set pagination values. Note that \Fuel\Orm\Query has been changed
		 * to correctly deal with null values for row offset and row limit.
		 */		
		$this->offset = \Input::get('offset');
		$this->limit = \Input::get('limit');
		$this->sort = \Input::get('sort', 'id');
		
		// Only set order when appropriate
		if(in_array(($order_param = \Input::get('order')), ['asc', 'desc'])){
			$this->order =$order_param;
		}	
	}
	
	/**
	 * Execute given query with the pagination details as requested.
	 * @param \Orm\Query $query The query
	 * @param string $order_column Column name on which to order rows. Defaults to id.
	 * @return array [query result array, total records]
	 */
	protected final function paginate_query(\Orm\Query $query) : array {
		$count = $query->count();
		
		
		return [$query->rows_offset($this->offset)
			->rows_limit($this->limit)
			->order_by($this->sort, $this->order)
			->get(), $count];
	}
}