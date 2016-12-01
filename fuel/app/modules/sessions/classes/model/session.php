<?php
namespace Sessions;

class Model_Session extends \Orm\Model
{
	const DEADLINE_TIME = '16:00';
	
	protected static $_properties = array(
		'id',
		'date' => array(
			'validation' => array('required', 'valid_date'),
		),
		'notes',
		'cost',
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
	
	
	/* Below this line you will find custom methods for session trieval etc. */
	
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
		$user = \Auth::get_user();
		
		$enrollment = Model_Enrollment_Session::find('first', array(
					'where' => array(
						array('user_id', $user->id), array('session_id', $this->id))
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
	 * Determine if session is open for enrollment
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
	 * Get the number of cooks enrolled in this session
	 * @return int
	 */
	public function count_cooks() {
		// TODO: optimze
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('cook', 1), array('session_id', $this->id)),
		));
		
		return sizeof($enrollments);
	}
	
	/**
	 * Get the number of dishwashers enrolled in this session
	 * @return int
	 */
	public function count_dishwashers() {
		// TODO: optimze
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('dishwasher', 1), array('session_id', $this->id)),
		));
		
		return sizeof($enrollments);
	}
	
	/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return type
	 */
	public function get_cook_enrollments() {
		// TODO: optimze
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('cook', 1), array('session_id', $this->id)),
		));
		
		return $enrollments;
	}
	
		/**
	 * Get the enrollments for all cooks enrolled in this session
	 * @return type
	 */
	public function get_dishwasher_enrollments() {
		// TODO: optimze
		$enrollments = Model_Enrollment_Session::find('all', array(
			'where' => array(
				array('dishwasher', 1), array('session_id', $this->id)),
		));
		
		return $enrollments;
	}
}
