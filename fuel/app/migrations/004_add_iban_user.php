<?php

namespace Fuel\Migrations;

/**
 * Add IBAN field on users.
 */
class Add_IBAN_User {
	
	public function up() {
		\DBUtil::add_fields('users', array('iban' => array('constraint' => '30', 'type' => 'varchar')));
	}
	
	public function down() {
		\DBUtil::drop_fields('users', array('iban'));
	}
}