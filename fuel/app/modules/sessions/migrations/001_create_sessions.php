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
			'paid_by' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'deadline' => array('type' => 'datetime'),
			'settled' => array('type' => 'boolean'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::create_index('sessions', array('date'), 'date', 'UNIQUE');
		
		\DBUtil::add_foreign_key('sessions', array(
			'constraint' => 'fk_paid_by_ses',
			'key' => 'paid_by',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
	}

	public function down()
	{
		\DBUtil::drop_foreign_key('sessions', 'fk_paid_by_ses');
		\DBUtil::drop_table('sessions');		
	}
}