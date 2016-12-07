<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> session enrollment relation 
 */
class Create_Enrollment_Sessions {
	
	public function up() {
		\DBUtil::create_table('enrollment_sessions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'session_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'guests' => array('constraint' => 11, 'type' => 'int'),
			'cook' => array('type' => 'boolean'),
			'dishwasher' => array('type' => 'boolean'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),

		), array('id'));
		
		
		\DBUtil::add_foreign_key('enrollment_sessions', array(
			'constraint' => 'fk_user_id_es',
			'key' => 'user_id',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
		
		\DBUtil::add_foreign_key('enrollment_sessions', array(
				'constraint' => 'fk_session_id_es',
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
		\DBUtil::drop_table('enrollment_sessions');
		\DBUtil::drop_foreign_key('enrollment_sessions', 'fk_user_id_es');
		\DBUtil::drop_foreign_key('enrollment_sessions', 'fk_session_id_es');
	}
}