<?php

namespace Fuel\Migrations;

/**
 * Adds test data
 */
class Add_Mock_Users {
	
	public function up() {
		$pass = \Auth::instance()->hash_password('dev');
		$timestamp = \Date::forge()->get_timestamp();
		
		$admin = \Model_User::find(1);
		$admin->name = 'Addy';
		$admin->surname = 'Ministrator';
		$admin->phone = '';
		$admin->save();		
		
		$u1 = \Model_User::forge(array(
			'username' => 'demo1',
			'surname' => 'Demo',
			'name' => 'One',
			'phone' => '',
			'active' => 1,
			'start_year' => 0,
			'end_year' => 0,
			'points' => 0,
			'balance' => 0.0,
			'password' => $pass,
			'group_id' => 3,
			'email' => 'demo1@example.com',
			'last_login'      => 0,
			'previous_login'  => 0,
			'login_hash'      => '',
			'created_at'	  => $timestamp,
			'updated_at'      => 0,
		));
		
		$u1->save();
		
		$u2 = \Model_User::forge(array(
			'username' => 'demo2',
			'surname' => 'Demo',
			'name' => 'Two',
			'phone' => '',
			'active' => 1,
			'start_year' => 0,
			'end_year' => 0,
			'points' => 0,
			'balance' => 0.0,
			'password' => $pass,
			'group_id' => 3,
			'email' => 'demo2@example.com',
			'last_login'      => 0,
			'previous_login'  => 0,
			'login_hash'      => '',
			'created_at'	  => $timestamp,
			'updated_at'      => 0,
		));
		
		$u2->save();
		
		$u3 = \Model_User::forge(array(
			'username' => 'demo3',
			'surname' => 'Demo',
			'name' => 'Three',
			'phone' => '',
			'active' => 1,
			'start_year' => 0,
			'end_year' => 0,
			'points' => 0,
			'balance' => 0.0,
			'password' => $pass,
			'group_id' => 3,
			'email' => 'demo3@example.com',
			'last_login'      => 0,
			'previous_login'  => 0,
			'login_hash'      => '',
			'created_at'	  => $timestamp,
			'updated_at'      => 0,
		));
		
		$u3->save();
	}
	
	public function down() {
		
	}
}