<?php

namespace Fuel\Migrations;

class Add_User_Receipt_Points {

	public function up() {
		\DBUtil::add_fields('user_receipts', array(
			'points' => array('constraint' => 5, 'type' => 'int'),
		));
	}
	
	public function down() {
		\DBUtil::drop_fields('user_receipts', array(
			'points'
		));
	}
}

