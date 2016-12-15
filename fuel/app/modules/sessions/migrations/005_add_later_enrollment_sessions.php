<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> session enrollment relation 
 */
class Add_Later_Enrollment_Sessions {
	
	public function up() {
		\DBUtil::add_fields('enrollment_sessions', [
			'later' => array('type' => 'boolean'),
		]);
	}

	public function down() {
		\DBUtil::drop_fields('enrollment_sessions', ['later']);
	}
}