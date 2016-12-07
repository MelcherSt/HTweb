<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> receipts relation 
 */
class Create_Session_Receipts {
	
	public function up() {
		\DBUtil::create_table('session_receipts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'session_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'receipt_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::create_index('session_receipts', array('session_id', 'receipt_id'), 'enrollment', 'UNIQUE');	
		
		\DBUtil::add_foreign_key('session_receipts', array(
			'constraint' => 'fk_session_id_sr',
			'key' => 'session_id',
			'reference' => array(
				'table' => 'sessions',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
		
		\DBUtil::add_foreign_key('session_receipts', array(
				'constraint' => 'fk_receipt_id_sr',
				'key' => 'receipt_id',
				'reference' => array(
					'table' => 'receipts',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
		));
	}

	public function down() {
		\DBUtil::drop_table('session_receipts');
		\DBUtil::drop_index('session_receipts', 'enrollment');
		\DBUtil::drop_foreign_key('session_receipts', 'fk_user_id_sr');
		\DBUtil::drop_foreign_key('session_receipts', 'fk_session_id_sr');
	}
}