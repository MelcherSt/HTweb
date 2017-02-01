<?php

namespace Api;

class Response_Paginated extends Response_Base {
	public $total = 0;
	public $rows = [];
	
	public function __construct(array $data) {
		if(isset($data)) {
			$this->total = sizeof($data);
			$this->rows = array_values($data);
		}
	}
}
