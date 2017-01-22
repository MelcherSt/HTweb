<?php

namespace Fuel\Migrations;

class Extend_User_Receipt_Balance {
	
	public function up() {
		\DBUtil::modify_fields('user_receipts', array(
			'balance' => array('constraint' => '20,10', 'type' => 'decimal'),
		));
	}
	
	public function down() {
		\DBUtil::modify_fields('user_receipts', array(
			'balance' => array('constraint' => '10,2', 'type' => 'decimal'),
		));
	}
	
}