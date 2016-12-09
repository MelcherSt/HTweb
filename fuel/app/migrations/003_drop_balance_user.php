<?php

namespace Fuel\Migrations;

/**
 * Drop the balance field on users as it's not needed anymore.
 */
class Drop_Balance_User {
	
	public function up() {
		\DBUtil::drop_fields('users', array('balance'));
	}
	
	public function down() {
		\DBUtil::add_fields('users', array('balance' => array('constraint' => '10,2', 'type' => 'decimal')));
	}
}