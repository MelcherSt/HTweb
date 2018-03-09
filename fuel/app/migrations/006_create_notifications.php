<?php

namespace Fuel\Migrations;

/**
 * Create notifications table.
 */
class Create_Notifications
{

	public function up() {
		\DBUtil::create_table('notifications', [
			'id' => ['constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true],
			'user_id' => ['constraint' => 11, 'type' => 'int'],	
			'text' => ['constraint' => 100, 'type' => 'varchar'],
			'title' => ['constraint' => 100, 'type' => 'varchar', 'null' => true],
			'icon' => ['constraint' => 100, 'type' => 'varchar', 'null' => true],
			'href' => ['constraint' => 255, 'type' => 'varchar', 'default' => '#'],
			'date_time' => ['type' => 'datetime', 'default' => \DB::expr('CURRENT_TIMESTAMP')],
			'read' => ['type' => 'boolean', 'default' => false],
		], ['id', 'user_id']);
		
		// Create FK	
		\DBUtil::add_foreign_key('notifications', array(
			'constraint' => 'fk_user_id_n',
			'key' => 'user_id',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));			
	}

	public function down() {
		\DBUtil::drop_foreign_key('notifications', 'fk_user_id_n');
		\DBUtil::drop_table('notifications');
	}
}
