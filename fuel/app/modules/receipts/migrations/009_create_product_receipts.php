<?php

namespace Fuel\Migrations;

/**
 * Migration for user <-> receipts relation 
 */
class Create_Product_Receipts {
	
	public function up() {
		\DBUtil::create_table('product_receipts', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'product_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'receipt_id' => array('constraint' => 11, 'type' => 'int', 'unsigned' => true),
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::add_foreign_key('product_receipts', array(
				'constraint' => 'fk_receipt_id_pr',
				'key' => 'receipt_id',
				'reference' => array(
					'table' => 'receipts',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
		));
		
		\DBUtil::add_foreign_key('product_receipts', array(
				'constraint' => 'fk_product_id_pr',
				'key' => 'product_id',
				'reference' => array(
					'table' => 'products',
					'column' => 'id',
				),
				'on_update' => 'CASCADE',
				'on_delete' => 'CASCADE'
		));
		
		\DBUtil::create_index('product_receipts', array('product_id', 'receipt_id'), 'enrollment', 'UNIQUE');		
	}

	public function down() {
		\DBUtil::drop_table('product_receipts');
		\DBUtil::drop_foreign_key('product_receipts', 'fk_receipt_id_pr');
		\DBUtil::drop_foreign_key('product_receipts', 'fk_product_id_pr');
	}
}