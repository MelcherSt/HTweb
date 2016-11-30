<?php

/**
 * Simple object that can store different items of a certain type
 */
class Data{
	private $data = array();
	
	/**
	 * Put a new item
	 */
	public function put_item($item){
		$this->data[] = $item;
	}
	
	/**
	 * Get all stored items
	 */
	public function get_items(){
		return $this->data;
	}
}

?>
