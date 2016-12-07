<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> receipts relation 
 */
class Add_Session_Fk {
	
	public function up() {
		\DBUtil::add_foreign_key('session_receipts', array(
			'constraint' => 'fk_session_id_sr',
			'key' => 'session_id',
			'reference' => array(
				'table' => 'sessions',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'CASCADE'
		));
	}
	
	public function down() {
		\DBUtil::drop_foreign_key('session_receipts', 'fk_session_id_sr');
	}
}

