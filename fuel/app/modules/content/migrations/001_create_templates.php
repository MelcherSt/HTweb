<?php

namespace Fuel\Migrations;

class Create_Templates
{
	
	public function up()
	{
		\DBUtil::create_table('templates', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 50, 'type' => 'varchar'),
			'description' => array('type' => 'text'),
			'base_template' => array('constraint' => 255, 'type' => 'varchar'),
			'content_template' => array('constraint' => 255, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::create_index('templates', array('name'), 'name', 'UNIQUE');
	}

	public function down()
	{
		\DBUtil::drop_table('templates');		
	}
}