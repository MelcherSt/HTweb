<?php

namespace Fuel\Migrations;

/**
 * Add IBAN field on users.
 */
class Add_Avatar_User {
	
	public function up() {
		\DBUtil::add_fields('users', array('avatar' => array('constraint' => 255, 'type' => 'varchar')));
	}
	
	public function down() {
		\DBUtil::drop_fields('users', array('avatar'));
	}
}