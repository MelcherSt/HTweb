<?php

namespace Api;

class Model_User extends \Orm\Model {
	
	protected $table_name = 'users';
	
	protected static $_properties = [
		'id',
		'name',
		'username',
		'surname',
		'email',
	];
	
}