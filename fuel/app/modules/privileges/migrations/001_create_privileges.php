<?php

namespace Fuel\Migrations;

class Create_Privileges {
	public function up() {
		\Auth\Model\Auth_Permission::forge([
			'area' => 'sessions',
			'permission' => 'administration',
			'description' => 'sessions.perm.administration'
		])->save();
		
		\Auth\Model\Auth_Permission::forge([
			'area' => 'products',
			'permission' => 'administration',
			'description' => 'products.perm.administration'
		])->save();
		
		\Auth\Model\Auth_Permission::forge([
			'area' => 'receipts',
			'permission' => 'administration',
			'description' => 'receipts.perm.administration'
		])->save();
		
		\Auth\Model\Auth_Permission::forge([
			'area' => 'users',
			'permission' => 'administration',
			'description' => 'users.perm.administration'
		])->save();
		
		\Auth\Model\Auth_Permission::forge([
			'area' => 'privileges',
			'permission' => 'administration',
			'description' => 'privileges.perm.administration'
		])->save();
	}

	public function down() {
		\DBUtil::truncate_table(\Auth\Model\Auth_Permission::table());	
	}
}