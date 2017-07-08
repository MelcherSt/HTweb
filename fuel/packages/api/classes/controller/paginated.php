<?php

namespace Api;

/**
 * Enables pagination on API controllers. <br>
 * This controller accepts request in the form of <br>
 * <b>/$endpoint?search={text}&sort={property/column}&limit=x&offset=y&order=(asc|desc)</b><br><br>
 * To generate paginated output, explicitly return data using the Response_Paginated wrapper.
 */
class Controller_Paginated extends Controller_Auth {
	
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
	protected $sort;
	
	/**
	 * Search string. 
	 * @var string 
	 */
	protected $search;
	
	private static $_default_sort_key = 'id';
	
	/**
	 * Columns that will be ignored when defined as sorting key.
	 * @var array 
	 */
	protected $unsortable_columns = [];
	
	public function before() {
		parent::before();
		
		/* 
		 * Set pagination values based on query - or use defaults instead.
		 */		
		$this->offset = \Input::get('offset', 0);
		$this->limit = \Input::get('limit', PHP_INT_MAX);
		$this->sort = \Input::get('sort', self::$_default_sort_key);
		$this->search = \Input::get('search');
		
		// Only set order when appropriate
		if(in_array(($order_param = \Input::get('order')), ['asc', 'desc'])){
			$this->order =$order_param;
		}	
		
		// Reset sort key whenever it was defined as an unsortable column. 
		if(in_array($this->sort, $this->unsortable_columns)) {
			$this->sort = self::$_default_sort_key;
		}
	}
	
	/**
	 * Execute given query with the pagination details as requested.
	 * @param \Orm\Query $query The query
	 * @return array [query result array, total records]
	 */
	protected final function paginate_query(\Orm\Query $query) : array {
		/* Apply search query to this SQL query. 
		Searches only apply on the currently sorted column defined by 'sort'. */
		if(isset($this->search) && ($this->search != '')) {
			$query = $query->where($this->sort, 'like', '%' . $this->search . '%');
		}
		
		$count = $query->count();
		$pagQuery = $query
				->rows_offset($this->offset)
				->rows_limit($this->limit)
				->order_by($this->sort, $this->order);
		
		/* Catch faulty queries which may be created 
		by using non-existant properties as sort key */
		try {
			$result = $pagQuery->get();
		} catch (\Fuel\Core\Database_Exception $ex) {
			// Caught
			$result = [];
		}		
				
		return [$result, $count];
	}
}