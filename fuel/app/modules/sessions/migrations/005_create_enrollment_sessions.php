<?php

namespace Fuel\Migrations;

class Create_Enrollment_Sessions
{
	public function up()
	{
		\DBUtil::create_table('enrollment_sessions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),
			'session_id' => array('constraint' => 11, 'type' => 'int'),
			'guests' => array('constraint' => 11, 'type' => 'int'),
			'cook' => array('type' => 'boolean'),
			'dishwasher' => array('type' => 'boolean'),
			'paid' => array('type' => 'boolean'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('enrollment_sessions');
	}
}