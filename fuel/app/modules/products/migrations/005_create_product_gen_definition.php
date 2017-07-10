<?php

namespace Fuel\Migrations;

class Create_Product_Gen_Definition {
	public function up() {
		\DBUtil::create_table('product_definitions', array(
			'id' => array('constraint' => 11, 'type' => 'int', 'auto_increment' => true, 'unsigned' => true),
			'name' => array('constraint' => 50, 'type' => 'varchar'),
			'cost' => array('constraint' => '10,2', 'type' => 'decimal'),
			'paid_by' => array('constraint' => 11, 'type' => 'int'),			// Auth users migration uses signed id's
			'last_execution' => ['type' => 'datetime', 'null' => true],
			'created_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
			'updated_at' => array('constraint' => 11, 'type' => 'int', 'null' => true),
		), array('id'));
		
		\DBUtil::add_foreign_key('products', array(
			'constraint' => 'fk_paid_by_pgd',
			'key' => 'paid_by',
			'reference' => array(
				'table' => 'users',
				'column' => 'id',
			),
			'on_update' => 'CASCADE',
			'on_delete' => 'RESTRICT'
		));
	}

	public function down() {
		\DBUtil::drop_foreign_key('product_definitions', 'fk_paid_by_pgd');
		\DBUtil::drop_table('product_definitions');		
	}
}