<?php

namespace Fuel\Migrations;

/**
 * Adds test data
 */
class Add_Mock_Sessions {
	
	public function up() {
		/* Session 1 */
		$s1 = \Sessions\Model_Session::forge(array(
			'notes' => 'Some notes about the food here',
			'cost' => 10.50,
			'paid_by' => 3,
			'deadline' => '2016-10-30 16:00:00',
			'date' => '2016-10-30',
			'settled' => false
		));
		$s1->save();
		
		$s1e1 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 3,
			'session_id' => $s1->id,
			'dishwasher' => false,
			'cook' => true,
			'guests' => 0,
		));
		
		$s1e2 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 1,
			'session_id' => $s1->id,
			'dishwasher' => true,
			'cook' => false,
			'guests' => 2,
		));
		
		$s1e3 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 4,
			'session_id' => $s1->id,
			'dishwasher' => false,
			'cook' => false,
			'guests' => 1,
		));
		
		$s1e1->save();
		$s1e2->save();
		$s1e3->save();
		
		$s2 = \Sessions\Model_Session::forge(array(
			'notes' => 'Some notes about the food here',
			'cost' => 20.19,
			'paid_by' => 4,
			'deadline' => '2016-10-29 17:00:00',
			'date' => '2016-10-29',
			'settled' => false
		));
		$s2->save();
		
		$s2e1 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 3,
			'session_id' => $s2->id,
			'dishwasher' => true,
			'cook' => false,
			'guests' => 0,
		));
		
		$s2e2 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 1,
			'session_id' => $s2->id,
			'dishwasher' => true,
			'cook' => false,
			'guests' => 0,
		));
		
		$s2e3 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 4,
			'session_id' => $s2->id,
			'dishwasher' => false,
			'cook' => true,
			'guests' => 1,
		));
		
		$s2e4 = \Sessions\Model_Enrollment_Session::forge(array(
			'user_id' => 5,
			'session_id' => $s2->id,
			'dishwasher' => false,
			'cook' => false,
			'guests' => 2,
		));
		
		$s2e1->save();
		$s2e2->save();
		$s2e3->save();
	}
	
	public function down() {
		\DBUtil::truncate_table('sessions');
		\DBUtil::truncate_table('enrollment_sessions');
	}
}
