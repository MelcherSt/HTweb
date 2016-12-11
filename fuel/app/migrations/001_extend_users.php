<?php

namespace Fuel\Migrations;

/**
 * Extend the users table initially created by the Auth package with some extra columns.
 */
class Extend_Users 
{
	public function up() {
		\DBUtil::add_fields('users', array(
			'name' => array('constraint' => 50, 'type' => 'varchar'),
			'surname' => array('constraint' => 50, 'type' => 'varchar'),
			'phone' => array('constraint' => 20, 'type' => 'varchar'),
			'active' => array('type' => 'boolean'),
			'start_year' => array('constraint' => 4, 'type' => 'int'),
			'end_year' => array('constraint' => 4, 'type' => 'int'),
			'points' => array('constraint' => 5, 'type' => 'int'),
			'iban' => array('constraint' => '30', 'type' => 'varchar'),
			'avatar' => array('constraint' => 255, 'type' => 'varchar')
		));
	}

	public function down() {
		\DBUtil::drop_fields('users', array('name', 'surname', 'phone', 
			'active', 'start_year', 'end_year', 'points', 'balance'));
	}
}