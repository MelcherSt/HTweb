<?php
namespace Sessions;

class Model_Session extends \Orm\Model
{
	
	const DEADLINE_GRACE = '+19hours';
	const ENROLLMENT_GRACE = '+3days';
	const DISHWASHER_ENROLLMENT_GRACE = '+1day';
	
	const DEADLINE_TIME = '16:00';
	const MAX_GUESTS = 20;
	
	const SETTLEABLE_AFTER = '3'; // Used to retrieve settleable sessions in days
	
	protected static $_properties = [
		'id',
		'date',
		'notes' => ['default' => ''],
		'cost' => ['default' => 0.0], 
		'paid_by' => ['default' => 0], 
		'deadline',
		'settled' => ['default' => false],
		'created_at',
		'updated_at',
	];

	protected static $_observers = [
		'Orm\Observer_CreatedAt' => [
			'events' => ['before_insert'],
			'mysql_timestamp' => false,
		],
		'Orm\Observer_UpdatedAt' => [
			'events' => ['before_update'],
			'mysql_timestamp' => false,
		]
	];

	protected static $_table_name = 'sessions';	
	
	protected static $_has_many = [
		'enrollments' => [
			'model_to' => 'Sessions\Model_Enrollment_Session',
			'cascade_delete' => true,
		],
	];
	
	public static function validate($factory) {
		$val = \Validation::forge($factory);
		$val->add_field('date', __('product.field.date'), 'required|valid_date[Y-m-d]');
		$val->add_field('notes', __('session.field.notes'), 'max_length[255]');
		$val->add_field('costs', __('session.field.cost'), 'is_numeric');
		return $val;
	}
	
	/**
	 * Delete all orphaned sessions
	 */
	public static function scrub() {
		$today = (new \DateTime())->format('Y-m-d');
				
		// Remove all orphaned sessions
		\DB::delete('sessions')
			->where('id', 'not in', \DB::query('select distinct session_id from enrollment_sessions'))
			->where('date', '<', $today)
			->execute();
		
		// Remove all sessions without cook
		\DB::delete('sessions')
			->where('id', 'in', \DB::query('select distinct session_id from '
					. 'enrollment_sessions where session_id = sessions.id group by sessions.id having sum(cook) = 0'))
			->where('date', '<', $today)
			->execute();
	}
	
	/**
	 * Retrieve session from database by date
	 * @param string $date
	 * @return Model_Session
	 */
	public static function get_by_date(string $date) :?Model_Session {
		return Model_Session::query()
				->where('date', $date)
				->get_one();
	}
	
	/**
	 * Retrieve sessions in which user is enrolled
	 * @param int $user_id
	 * @param boolean $include_self Include sessions in which user cooked
	 * @param boolean $settled Query settled session only
	 * @return array \Sessions\Model_Session
	 */
	public static function get_by_user(int $user_id, bool $include_self=false, bool $settled=false) : array {
		$query = Model_Session::query()
			->related('enrollments')
			->where('enrollments.user_id', $user_id)
			->where('settled', $settled)
			->order_by('date', 'desc');
		
		if(!$include_self) {
			$query = $query->where('enrollments.cook', false);
		}
		return $query->get();
	}
	
	public static function get_by_cook(int $user_id, bool $settled=false) : array {
		return Model_Session::query()
			->related('enrollments')
			->where('enrollments.user_id', $user_id)
			->where('enrollments.cook', true)
			->where('settled', $settled)
			->order_by('date', 'desc')
			->get();
	}

	/**
	 * Retrieve all session older than 5 days that have not been settled yet
	 * @return array \Sessions\Model_Session
	 */
	public static function get_settleable() : array {
		return Model_Session::find('all', array(
			'where' => array(
				array(\DB::expr('DATE_ADD(date, INTERVAL ' . Model_Session::SETTLEABLE_AFTER . ' DAY)'), '<', date('Y-m-d')),
				array('settled', 0)
			)
		));
	}

	/* Below this line you will find instance methods */
	
	/**
	 * Determines if the session is eligable for deadline postponement.
	 * To be eligable for postponement the session should be past due, have no
	 * cooks and at least 1 participant.
	 * @return boolean
	 */
	public function should_postpone() : bool {
		// The deadline must be past-due and there should be 0 cooks
		if ($this->count_participants() > 0) {
			return !$this->is_predeadline() && 
				($this->count_cooks() == 0) && 
				$this->in_deadline_mod_grace();
		} else {
			return false;
		}
	}
	
	/**
	 * Determine if now is before the deadline
	 * @return boolean
	 */
	public function is_predeadline() : bool {
		// Before deadline
		$now = new \DateTime();
		$deadline = new \DateTime($this->deadline);	
		return $now <= $deadline;
	}
	
	/**
	 * Determine if now is after deadline, but before 4 days after deadline
	 * @return boolean
	 */
	public function in_extended_enrollment_period() : bool {
		// After deadline. Before 4 days after.	
		if(!$this->is_predeadline()) {
			$now = new \DateTime();
			$end_extended_period = (new \DateTime($this->date))->modify(static::ENROLLMENT_GRACE);
			return $now < $end_extended_period;
		}
		return false;
	}
	
	/**
	 * Determine if now is after diner time (18:00), but before end of day
	 * @return boolean
	 */
	public function in_dishwasher_enrollment_period() : bool {
		// After diner time. Before end of the day.
		$now = new \DateTime();
		$diner_time = (new \DateTime($this->date))->setTime(18, 00, 00);
		
		if($now > $diner_time) {
			$end_dishwasher_period = (new \DateTime($this->date))->modify(static::DISHWASHER_ENROLLMENT_GRACE);
			return $now < $end_dishwasher_period;
		} 
		return false;
	}
	
	/**
	 * Determine whether the deadline of this session may changed. Sets both upper and lower bounds
	 * @return boolean
	 */
	private function in_deadline_mod_grace() : bool {
		if ($this->is_predeadline()) { 
			// Deadline may be changed during enrollment period just alright.
			return true;
		} else {
			// If the deadline already passed
			return strtotime(date('Y-m-d H:i:s')) < strtotime($this->date . static::DEADLINE_GRACE);
		}
	}
	
	
	/**
	 * Retrieve user model for paying user. Returns guest user when no payer.
	 * @return \Model_User
	 */
	public function get_payer() : \Model_User {
		return \Model_User::find($this->paid_by);
	}
	
	/**
	 * Retrieve a list of user enrollments in this session
	 * @return array \Sessions\Model_Enrollment_Session
	 */
	public function get_enrollments() : array {
		return Model_Enrollment_Session::query()
			->related('user')
			->where('session_id', $this->id)
			->order_by('user.name', 'asc')
			->get();
	}
	
	/**
	 * Retrieve a list of all active users not unrolled in this session
	 * Guest user (id 0) is excluded from the list.
	 * @return array \Model_User
	 */
	public function get_unenrolled() : array {
		//select u.id, u.name from users u where u.id not in (select es.user_id from enrollment_sessions es, sessions s where es.session_id = 2 and s.id = 2);
		return \Model_User::query()
				->where('id', 'not in', \DB::query('select es.user_id from enrollment_sessions es, sessions s where es.session_id = ' . $this->id . ' and s.id = ' . $this->id))
				->where('id', '!=', 0)
				->where('active', true)
				->get();		
	}
	
	/**
	 * Retrieve the enrollment (if any) for the given user
	 * @param int $user_id
	 * @return \Sessions\Model_Enrollment_Session
	 */
	public function get_enrollment(int $user_id) : ?Model_Enrollment_Session {		
		return Model_Enrollment_Session::query()
				->where('user_id', $user_id)
				->where('session_id', $this->id)
				->get_one();
	}
	
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return array \Sessions\Model_Enrollment_Session
	 */
	public function get_cook_enrollments() : array {
		return Model_Enrollment_Session::query()
				->where('cook', true)
				->where('session_id', $this->id)
				->get();
	}
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return array \Sessions\Model_Enrollment_Session
	 */
	public function get_dishwasher_enrollments() : array {
		return Model_Enrollment_Session::query()
				->where('dishwasher', true)
				->where('session_id', $this->id)
				->get();
	}
	
	/**
	 * Retrieve the enrollment (if any) for the current user
	 * @return \Sessions\Model_Enrollment_Session
	 */
	public function current_enrollment() : ?Model_Enrollment_Session {
		return $this->get_enrollment(\Auth::get_user_id()[1]);
	}
	
	/**
	 * Get the number of cooks enrolled in this session
	 * @return int
	 */
	public function count_cooks() : int {
		return Model_Session::query()
				->where('id', $this->id)
				->related('enrollments')
				->where('enrollments.cook', true)
				->count('.user_id', true);
	}
	
	/**
	 * Get the number of dishwashers enrolled in this session
	 * @return int
	 */
	public function count_dishwashers() : int {
		return Model_Session::query()
				->related('enrollments')
				->where('id', $this->id)
				->where('enrollments.dishwasher', true)
				->count('.user_id', true);
	}
	
	/**
	 * Get the total amount of guests for this session
	 * @return int
	 */
	public function count_guests() : int {		
		$guest_count = array_values(\DB::select(\DB::expr('SUM(guests)'))
				->from('enrollment_sessions')
				->where('session_id', $this->id)
				->execute()[0])[0];
		
		if (empty($guest_count)) {
			return 0;
		}
		return $guest_count;
	}
	
	/**
	 * Get the amount of enrollments 
	 * including cooks and dishwashers, but excluding guests
	 * @return int
	 */
	public function count_participants() : int {
		return Model_Session::query()
				->related('enrollments')
				->where('id', $this->id)
				->count('.user_id', true);
	}
	
	/**
	 * Get the total amount of participants (all enrollments and their guests)
	 * @return int
	 */
	public function count_total_participants() : int {
		return $this->count_participants() + $this->count_guests();
	}
}
