<?php

class Dto_UserListItem {
	
	public $id;
	public $name;
	public $surname;
	
	public function __construct(\Model_User $user) {
		$this->id = (int)$user->id;
		$this->name = $user->name;
		$this->surname = $user->surname;
	}
}
