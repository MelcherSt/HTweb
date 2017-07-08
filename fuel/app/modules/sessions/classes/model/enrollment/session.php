<?php

namespace Sessions;

class Model_Enrollment_Session extends \Orm\Model {
	
	protected static $_properties = array(
		'id',
		'user_id',
		'session_id',
		'guests' => ['default' => 0],
		'cook' => ['default' => false],
		'later' => ['default' => false],
		'dishwasher' => ['default' => false],
		'created_at',
		'updated_at',
	);
	
	public static function validate($factory) {
		$val = \Validation::forge($factory);
		$val->add_field('guests', 'Guests', 'numeric_between[0,' . Model_Session::MAX_GUESTS . ']');
		$val->set_message('numeric_between', __('session.alert.error.guests', ['max_guests' => Model_Session::MAX_GUESTS]));
		return $val;
	}
	
	protected static $_observers = array(
		'Orm\Observer_CreatedAt' => array(
			'events' => array('before_insert'),
			'mysql_timestamp' => false,
		),
		'Orm\Observer_UpdatedAt' => array(
			'events' => array('before_update'),
			'mysql_timestamp' => false,
		),
	);
	
	protected static $_table_name = 'enrollment_sessions';
	
	protected static $_belongs_to = array(
		'user' => array(
			'key_from' => 'user_id',
			'key_to' => 'id',
			'model_to' => '\Model_User',
			'cascade_delete' => false,
		)
		,'session' => array(
			'key_from' => 'session_id',
			'key_to' => 'id',
			'model_to' => '\Sessions\Model_Session',
		)
	);
	
	/**
	 * Retrieve all enrollments for the given user
	 * @param type $user_id
	 * @return \Sessions\Model_Enrollment
	 */
	public static function get_by_user($user_id, $session_id) {
		return Model_Enrollment_Session::query()
				->where('user_id', $user_id)
				->where('session_id', $session_id)
				->get_one();
	}
	
	/**
	 * Retrieve a list enrollments for all settleable sessions for current user
	 * @return \Sessions\Model_Enrollment[]
	 */
	public static function get_settleable($user_id) {
		return Model_Enrollment_Session::query()
			->related('session')
			->where('session.settled', false)
			->where(\DB::expr('DATE_ADD(date, INTERVAL ' . Model_Session::SETTLEABLE_AFTER . ' DAY)'), '<', date('Y-m-d'))
			->where('user_id', $user_id)
			->get();
	}
	
	public static function get_unsettleable($user_id) {
		return Model_Enrollment_Session::query()
			->related('session')
			->where('session.settled', false)
			->where(\DB::expr('DATE_ADD(date, INTERVAL ' . Model_Session::SETTLEABLE_AFTER . ' DAY)'), '>', date('Y-m-d'))
			->where('user_id', $user_id)
			->get();
	}
	
	/**
	 * Get a prediction of points delta for this enrollment. 
	 * @pram boolean Will perform a precise calculation as during receipt creation.
	 * @return int
	 */
	public function get_point_prediction($precise=false) {
		// Default loss
		$max_loss = 4;
		$session = $this->session;

		// Gain mutlipliers
		$cook_gain = 2;
		$dish_gain = 1;

		$cook_count = $session->count_cooks();
		$dish_count = $session->count_dishwashers();
		$total_count = $session->count_total_participants();
		$guests = $this->guests;
	
		if($precise) {
			if ($dish_count == 0) {
				$max_loss = 2; // No dishwashers means less loss
			} else if ($dish_count == 1) {
				$dish_gain = 2; // Double the multiplier for a single dishwasher
			}
		}		
		
		if ($cook_count == 2) {
			$cook_gain = 1; // Two cooks split the multiplier
		}

		$temp_points = -($max_loss + $max_loss * $guests);
		
		if ($this->cook) {
			$temp_points += $cook_gain * $total_count;
		}			
		if ($this->dishwasher) {
			$temp_points += $dish_gain * $total_count;
		}				
		
		return $temp_points;		
	}
}


