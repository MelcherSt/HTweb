<?php

namespace Fuel\Migrations;

/**
 * Extend the users table initially created by the Auth package with some extra columns.
 */
class Add_Lang_Users 
{
	public function up() {
		\DBUtil::add_fields('users', array(
			'lang' => array('constraint' => 2, 'type' => 'varchar'),
		));
	}

	public function down() {
		\DBUtil::drop_fields('users', array('lang'));
	}
}