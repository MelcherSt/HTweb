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
			'type' => ['constraint' => 100, 'type' => 'varchar', 'default' => 'MISC'],
			'icon' => ['constraint' => 100, 'type' => 'varchar', 'null' => true],
			'href' => ['constraint' => 255, 'type' => 'varchar', 'default' => '#'],
			'created' => ['type' => 'datetime'],
			'read' => ['type' => 'boolean', 'default' => false],
		], ['id']);
		
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
		
		\DBUtil::create_index('notifications', ['id', 'user_id'], 'notification_user');
	}

	public function down() {
		\DBUtil::drop_index('notifications', 'notification_user');
		\DBUtil::drop_foreign_key('notifications', 'fk_user_id_n');
		\DBUtil::drop_table('notifications');
	}
}
