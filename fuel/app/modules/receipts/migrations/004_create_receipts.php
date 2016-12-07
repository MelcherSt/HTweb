<?php

namespace Fuel\Migrations;

class Create_Receipts
{
	public function up()
	{
		\DBUtil::create_table('receipts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'notes' => array('type' => 'text'),
			'date' => array('type' => 'date'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
	}

	public function down()
	{
		\DBUtil::drop_table('receipts');		
	}
}