<?php

namespace Fuel\Migrations;

class Add_Product_Gen
{
	public function up()
	{
		\DBUtil::add_fields('products', [
			'generated' => ['type' => 'boolean', 'default' => false, 'null' => false]
		]);
	}

	public function down()
	{
		\DBUtil::drop_fields('products', 'generated');		
	}
}