<?php
namespace Sessions;

class Model_Session extends \Orm\Model
{
	const DEADLINE_TIME = '16:00';
	const MAX_COOKS = 1;
	const MAX_DISHWASHER = 2;
	
	/* Grace variables */
	const DEADLINE_GRACE = '+1day';
	const ENROLLMENT_GRACE = '+5days';
	const COST_GRACE = '+5days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	protected static $_properties = array(
		'id',
		'date' => array(
			'validation' => array('required', 'valid_date'),
		),
		'notes',
		'cost',
		'paid_by',
		'deadline',
		'settled',
		'created_at',
		'updated_at',
	);

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

	protected static $_table_name = 'sessions';	
	
	protected static $_has_many = array(
		'enrollments' => array(
			'model_to' => 'Sessions\Model_Enrollment_Session',
			'cascade_delete' => true,
		),
	);
	
	/**
	 * Retrieve session from database by date
	 * @param string $date
	 * @return Model_Session
	 */
	public static function get_by_date($date) {
		return Model_Session::find('first', array(
			'where' => array(
				array('date', $date))
		));
	}

	/**
	 * Retrieve all session older than 5 days
	 * @return [\Sessions\Model_Session]
	 */
	public static function get_ready_for_settlement() {
		return Model_Session::find('all', array(
			'where' => array(
				array(\DB::expr('DATE_ADD(date, INTERVAL 5 DAY)'), '<', \DB::expr('"'. date('Y-m-d'). '"'))
			)
		));
	}
	
	/* Below this line you will find instance methods */
	
	/**
	 * Determine if the given user is enrolled
	 * @param type $user_id
	 * @return boolean
	 */
	public function is_enrolled($user_id) {
		$user = \Auth::get_user($user_id);
		
		$enrollment = Model_Enrollment_Session::find('first', array(
					'where' => array(
						array('user_id', $user->id), array('session_id', $this->id))
				));

		return isset($enrollment);
	}
	
	/**
	 * Retrieve the enrollment (if any) for the given user
	 * @param int $user_id
	 * @return \Sessions\Model_Enrollment_Session
	 */
	public function get_enrollment($user_id) {		
		$enrollment = Model_Enrollment_Session::find('first', array(
					'where' => array(
						array('user_id', $user_id), array('session_id', $this->id))
				));
		return $enrollment;
	}
	
	/**
	 * Retrieve the enrollment (if any) for the current user
	 * @return \Sessions\Model_Enrollment_Session
	 */
	public function current_enrollment() {
		$user = \Auth::get_user();
		return $this->get_enrollment($user->id);
	}
	
	/**
	 * Determine if session is open for normal enrollment
	 * @return boolean
	 */
	public function can_enroll() {
		$now_time = strtotime(date('Y-m-d H:i:s'));
		$expiry_time = strtotime(date('Y-m-d H:i:s', strtotime($this->deadline)));	
		// Deadline should be later than now
		if ($expiry_time > $now_time) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determine whether dishwashers may enroll
	 * @return boolean
	 */
	public function can_enroll_dishwashers() {
		// Deadline should be past due + diswasher count should be less than max.
		if(!$this->can_enroll() && ($this->count_dishwashers() < static::MAX_DISHWASHER)) {
			// Dishwashers have untill the end of the day to enroll.
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DISHWASHER_ENROLLMENT_GRACE);
		} else {
			return false;
		}
	}
	
	/**
	 * Determine whether the cost of this session may be changed by the cooks
	 * @return boolean
	 */
	public function can_change_cost() {
		return !$this->can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::COST_GRACE));
	}
	
	/**
	 * Determine whether the enrollments of this session may be altered by the cooks
	 * @return boolean
	 */
	public function can_change_enrollments() {
		return !$this->can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::ENROLLMENT_GRACE));
	}
	
	/**
	 * Determine whether the deadline of this session may changed
	 * @return type
	 */
	public function can_change_deadline() {
		if ($this->can_enroll()) { 
			return true;
		} else {
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DEADLINE_GRACE);
		}
		
	}
	
	/**
	 * Determine whether cooks may enroll
	 * @return boolean
	 */
	public function can_enroll_cooks() {
		return $this->can_enroll() && ($this->count_cooks() < static::MAX_COOKS); 
	}
	
	/**
	 * Get the number of cooks enrolled in this session
	 * @return int
	 */
	public function count_cooks() {
		return array_values(\DB::select(\DB::expr('COUNT(*)'))
				->from('enrollment_sessions')
				->where('cook', 1)
				->and_where('session_id', $this->id)
				->execute()[0])[0];
	}
	
	/**
	 * Get the number of dishwashers enrolled in this session
	 * @return int
	 */
	public function count_dishwashers() {
		return array_values(\DB::select(\DB::expr('COUNT(*)'))
				->from('enrollment_sessions')
				->where('dishwasher', 1)
				->and_where('session_id', $this->id)
				->execute()[0])[0];
	}
	
	/**
	 * Get the total amount of guests for this session
	 * @return int
	 */
	public function count_guests() {
		return array_values(\DB::select(\DB::expr('SUM(guests)'))
				->from('enrollment_sessions')
				->where('session_id', $this->id)
				->execute()[0])[0];
	}
	
	/**
	 * Get the amount of enrollments 
	 * including cooks and dishwashers, but excluding guests
	 * @return int
	 */
	public function count_participants() {
		return array_values(\DB::select(\DB::expr('COUNT(*)'))
				->from('enrollment_sessions')
				->where('session_id', $this->id)
				->execute()[0])[0];
	}
	
	/**
	 * Get the total amount of participants (all enrollments and their guests)
	 * @return boolean
	 */
	public function count_total_participants() {
		return $this->count_participants() + $this->count_guests();
	}
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return [\Sessions\Model_Enrollment_Session]
	 */
	public function get_cook_enrollments() {
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('cook', 1), array('session_id', $this->id)),
		));
		
		return $enrollments;
	}
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return [\Sessions\Model_Enrollment_Session]
	 */
	public function get_dishwasher_enrollments() {
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('dishwasher', 1), array('session_id', $this->id)),
		));
		
		return $enrollments;
	}
}
