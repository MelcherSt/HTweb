<?php

namespace Fuel\Migrations;

class Create_sessions
{
	public function up()
	{
		\DBUtil::create_table('sessions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'date' => array('type' => 'date'),
			'notes' => array('type' => 'text'),
			'cost' => array('constraint' => '10,2', 'type' => 'decimal'),
			'deadline' => array('type' => 'datetime'),
			'settled' => array('type' => 'boolean'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
		
		\DBUtil::create_index('sessions', array('date'), 'date', 'UNIQUE');
	}

	public function down()
	{
		\DBUtil::drop_table('sessions');
	}
}