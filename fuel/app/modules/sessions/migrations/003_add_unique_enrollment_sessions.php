<?php

namespace Fuel\Migrations;

/**
 * Ensure uniqueness of users in enrollment session
 */
class Add_Unique_Enrollment_Sessions 
{
	public function up() {
		\DBUtil::create_index('enrollment_sessions', array('user_id', 'session_id'), 'enrollment', 'UNIQUE');	
	}
	
	public function down() {
		\DBUtil::drop_index('enrollment_sessions', 'enrollment');
	}
	
}