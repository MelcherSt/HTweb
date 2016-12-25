<?php
namespace Sessions;

class Model_Session extends \Orm\Model
{
	const DEADLINE_TIME = '16:00';
	const DINER_TIME = '18:00';
	const MAX_COOKS = 1;
	const MAX_DISHWASHER = 2;
	const MAX_GUESTS = 20;
	
	/* Grace variables */
	const DEADLINE_GRACE = '+1day';
	const ENROLLMENT_GRACE = '+5days';
	const COST_GRACE = '+5days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	const SETTLEABLE_AFTER = '5'; // Used to retrieve settleable sessions
	
	protected static $_properties = array(
		'id',
		'date' => array(
			'validation' => array('required', 'valid_date'),
		),
		'notes' => array(
			'default'     => ''
		), 
		'cost' => array(
			'default'     => 0.0
		), 
		'paid_by' => array(
			'default'     => 0
		), 
		'deadline',
		'settled' => array (
			'default' => false,
		),
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
	 * Retrieve sessions in which user is enrolled
	 * @param int $user_id
	 * @param boolean $settled Query settled session only
	 * @return /Sessions/Model_Session[]
	 */
	public static function get_by_user($user_id, $settled=false) {
		return Model_Session::query()
			->related('enrollments')
			->where('enrollments.user_id', $user_id)
			->where('settled', $settled)
			->order_by('date', 'desc')
			->get();
	}

	/**
	 * Retrieve all session older than 5 days that have not been settled yet
	 * @return \Sessions\Model_Session[]
	 */
	public static function get_ready_for_settlement() {
		return Model_Session::find('all', array(
			'where' => array(
				array(\DB::expr('DATE_ADD(date, INTERVAL ' . Model_Session::SETTLEABLE_AFTER . ' DAY)'), '<', date('Y-m-d')),
				array('settled', 0)
			)
		));
	}
	
	public static function get_next_recommended_cook() {
		$users = \Model_User::get_by_state(true);
		$cur_recommendation = null;
		foreach($users as $user) {
			$points = $user->points;
			
			$enrollments = \Sessions\Model_Enrollment_Session::get_ready_for_settlement($user->id);
			foreach($enrollments as $enrollment) {
				$points += $enrollment->get_point_prediction();
			}
		}
		
	}
	
	/* Below this line you will find instance methods */
		
	/**
	 * Retrieve a list of user receipts in this receipt sorted by name alphabetical
	 * @return \Sessions\Model_Enrollment_Session[]
	 */
	public function get_enrollments_sorted() {
		return Model_Enrollment_Session::query()
			->related('user')
			->order_by('user.name', 'asc')
			->where('session_id', $this->id)
			->get();
	}
	
	/**
	 * Determine if the given user is enrolled
	 * @param int $user_id
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
		return $this->get_enrollment(\Auth::get_user_id()[1]);
	}
	
	/**
	 * Determine if session is open for normal enrollment
	 * @deprecated
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
	 * Determine if current time is after the start of diner
	 * @deprecated
	 * @return boolean
	 */
	public function is_past_diner() {
		$now_time = strtotime(date('Y-m-d H:i:s'));
		$start_time = strtotime(date('Y-m-d H:i:s', strtotime(Model_Session::DINER_TIME)));	
		// Now should be after diner start
		if ($now_time > $start_time) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Determine whether the cost of this session may be changed by the cooks
	 * @deprecated
	 * @return boolean
	 */
	public function can_change_cost() {
		return !$this->can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::COST_GRACE));
	}
	
	/**
	 * Determine whether the enrollments of this session may be altered by the cooks
	 * @deprecated
	 * @return boolean
	 */
	public function can_change_enrollments() {
		return !$this->can_enroll() && (strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::ENROLLMENT_GRACE));
	}
	
	/**
	 * Determine whether the deadline of this session may changed
	 * @deprecated since version number
	 * @return boolean
	 */
	public function can_change_deadline() {
		if ($this->can_enroll()) { 
			return true;
		} else {
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DEADLINE_GRACE);
		}
	}
	
	/**
	 * Determine whether an extra cook may be enrolled
	 * @deprecated since version number
	 * @return boolean
	 */
	public function can_cook() {			
		return (int)$this->count_cooks() < static::MAX_COOKS; 	
	}
	
	/**
	 * Determine whether an extra dishwasher may be enrolled
	 * @deprecated since version number
	 * @return type
	 */
	public function can_dishwasher() {
		return (int)$this->count_dishwashers() < static::MAX_DISHWASHER;
	}
	
	/**
	 * Determine if we're in the timespan a dishwasher may enroll
	 * @param boolean $cook Cooks may enroll people in an extended grade period
	 * @deprecated
	 * @return boolean
	 */
	public function can_change_dishwashers() {
		
		// Deadline should be past due + diswasher count should be less than max.
		if(!$this->can_enroll() && $this->is_past_diner() && ($this->count_dishwashers() < static::MAX_DISHWASHER)) {
			// Dishwashers have untill the end of the day to enroll.
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DISHWASHER_ENROLLMENT_GRACE);
		} else {
			return false;
		}
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
	 * @return int
	 */
	public function count_total_participants() {
		return $this->count_participants() + $this->count_guests();
	}
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return \Sessions\Model_Enrollment_Session[]
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
	 * @return \Sessions\Model_Enrollment_Session[]
	 */
	public function get_dishwasher_enrollments() {
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('dishwasher', 1), array('session_id', $this->id)),
		));
		
		return $enrollments;
	}
}
