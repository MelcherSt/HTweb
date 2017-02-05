<?php
namespace Api;

class Response_Paginated extends Response_Base {
	public $total = 0;
	public $current = 0;
	public $rows = [];
	
	public function __construct(array $data, int $total=null) {
		if(isset($data)) {
			if(isset($total)) {
				$this->total = $total;
			} else {
				$this->total = sizeof($data);
			}
			$this->current = sizeof($data);
			$this->rows = array_values($data);
		}	
	}
}