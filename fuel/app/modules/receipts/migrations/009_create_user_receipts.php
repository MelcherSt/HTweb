<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> receipts relation 
 */
class Create_User_Receipts {
	
	public function up() {
		\DBUtil::create_table('user_receipts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'receipt_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'balance' => array('constraint' => '10,2', 'type' => 'decimal'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::create_index('user_receipts', array('user_id', 'receipt_id'), 'enrollment', 'UNIQUE');	
		
		\DBUtil::add_foreign_key('user_receipts', array(
			'constraint' => 'fk_user_id_ur',
			'key' => 'user_id',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
		
		\DBUtil::add_foreign_key('user_receipts', array(
				'constraint' => 'fk_receipt_id_ur',
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
		\DBUtil::drop_table('user_receipts');
		\DBUtil::drop_index('user_receipts', 'enrollment');
		\DBUtil::drop_foreign_key('user_receipts', 'fk_user_id_ur');
		\DBUtil::drop_foreign_key('user_receipts', 'fk_session_id_ur');
	}
}