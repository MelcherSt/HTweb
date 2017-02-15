<?php

namespace Fuel\Migrations;

/**
 * Adds password reset token table
 */
class Add_Pass_Reset {
	
	public function up() {
		\DBUtil::create_table('reset_tokens', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'token' => array('constraint' => 50, 'type' => 'varchar'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::create_index('reset_tokens', array('token'), 'token', 'UNIQUE');
		
		\DBUtil::add_foreign_key('reset_tokens', array(
			'constraint' => 'fk_user_id_rt',
			'key' => 'user_id',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
		));
	}
	
	public function down() {
		\DBUtil::drop_foreign_key('reset_tokens', 'fk_user_id_rt');
		\DBUtil::drop_table('reset_tokens');		
	}
}