<?php

namespace Fuel\Migrations;

class Add_Product_Date
{
	public function up()
	{
		\DBUtil::add_fields('products', [
			'date' => ['type' => 'date']
		]);
	}

	public function down()
	{
		\DBUtil::drop_fields('products', 'date');		
	}
}