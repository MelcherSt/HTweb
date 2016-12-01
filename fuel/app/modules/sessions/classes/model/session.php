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
	public function is_enrolled($user_id=0){
		$user = \Auth::get_user($user_id);
		
		foreach($this->enrollments as $enrollment){
			if ($enrollment->user->id == $user->id)
				return true;
		}
		
		return false;	
	}
	
	/**
	 * Retrieve the enrollment (if any) for the current user
	 * @return \Sessions\Model_Enrollment_Session
	 */
	public function current_enrollment() {
		$user = \Auth::get_user();
		
		$enrollment = Model_Enrollment_Session::find('first', array(
					'where' => array(
						array('user_id', $user->id), array('session_id', $this->id))
				));

		return $enrollment;
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
	 
	
	
	public static function get_by_date($date) {
		$query = \DB::select('*')
				->from(static::$_table_name)
				->where(array('date', $date))
				->execute();
		
		return $query;
	}

}
