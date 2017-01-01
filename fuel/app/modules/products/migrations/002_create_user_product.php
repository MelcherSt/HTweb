<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> session enrollment relation 
 */
class Create_User_Product {
	
	public function up() {
		\DBUtil::create_table('user_product', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'user_id' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'product_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'amount' => array('constraint' => 11, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		
		\DBUtil::add_foreign_key('user_product', array(
			'constraint' => 'fk_user_id_up',
			'key' => 'user_id',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
		
		\DBUtil::add_foreign_key('user_product', array(
				'constraint' => 'fk_product_id_up',
				'key' => 'product_id',
				'reference' => array(
					'table' => 'products',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
		));
		
		\DBUtil::create_index('user_product', array('user_id', 'product_id'), 'used_product', 'UNIQUE');	
	}

	public function down() {
		\DBUtil::drop_table('user_product');
		\DBUtil::drop_foreign_key('user_product', 'fk_user_id_up');
		\DBUtil::drop_foreign_key('user_product', 'fk_product_id_up');
		\DBUtil::drop_index('user_product', 'used_product');
	}
}