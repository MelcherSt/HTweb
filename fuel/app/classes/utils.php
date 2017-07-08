<?php

class Utils {
	/**
	 * The format in which MySQL stores datetime values
	 */
	const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
	
	/**
	 * The format in which MySQL stores and HTML transfers date values
	 */
	const MYSQL_DATE_FORMAT = 'Y-m-d';
	
	/**
	 * Typical date format
	 */
	const DATE_FORMAT = 'd/m/Y';
	
	/**
	 * Typical 24-hour time format
	 */
	const TIME_FORMAT = 'H:i';
	
	/**
	 * Typical combined
	 */
	const DATETIME_FORMAT = 'd/m/Y H:i';
	
	/**
	 * Redirect to 404 and show given error message
	 * @param string $message Error message
	 * @throws \HttpNotFoundException
	 */
	public static function handle_irrecoverable_error($message=null) {
		if(isset($message)) {
			\Session::set_flash('error', ($message));
		}		
		throw new \HttpNotFoundException();
	} 	
	
	/**
	 * Redirect and show error message
	 * @param string $message Error message 
	 * @param string $redirect If empty, redirect back to last page
	 * @return \Response
	 */
	public static function handle_recoverable_error($message, $redirect=null) {
		\Session::set_flash('error', ($message));
		
		if(empty($redirect)) {
			return \Response::redirect_back();
		} else {
				return \Response::redirect($redirect);
		}
	}
	
	/**
	 * Check if given string can be date formatted Y-m-d
	 * @param string $date
	 * @return boolean
	 */
	public static function valid_date($date) {
		if (empty($date)) {
			return false;
		}
		
		$d = DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}
	
	/**
	 * Check if the given date string is valid and has a session associated
	 * @param string $date String with session's date
	 * @param boolean $enforce_existance If true, when no session is associated report error
	 * @return mixed
	 */
	public static function valid_session($date, $enforce_existance=true) {
		if(!\Utils::valid_date($date)) {
			// Invalid date string
			\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
		}
		
		$session = \Sessions\Model_Session::get_by_date($date);		
		if($enforce_existance && empty($session)) {
			// If there is no session and session is enforced, report error
			\Utils::handle_irrecoverable_error(__('session.alert.error.no_session', ['date' => $date]));
		}
		return $session;
	}
	
	/**
	 * Check if the given user-id is associated with a user
	 * @param int $user_id
	 * @param boolean $enforce_existance If true, when no user report error
	 * @return mixed
	 */
	public static function valid_user($user_id, $enforce_existance=true) {
		$user = \Model_User::find($user_id);
		if($enforce_existance && empty($user)) {
			\Utils::handle_irrecoverable_error(__('user.alert.error.no_id', ['id' => $user_id]));
		}
		return $user;
	}	
	
	/**
	 * Create a \DateTime object from a date and time .
	 * @param string $date Formatted date string d/M/Y
	 * @param string $time Formatted time string H:i
	 * @return \DateTime
	 */
	public static function to_date_time(string $date, string $time) : \DateTime {
		$date_arr = explode('/', $date);
		$date_time = (new \DateTime())->setDate($date_arr[2], $date_arr[1], $date_arr[0]);
		
		$time_arr = explode(':', $time);
		$date_time->setTime($time_arr[0], $time_arr[1], 0);
		
		return $date_time;	
	}
	
	/**
	 * Format a string as a date.
	 * @param string $date The date string to format
	 * @param string $format A valid DateTime format string, defaults to DATETIME_FORMAT
	 */
	public static function format_date(string $date, string $format = null) : string {
		if(empty($format)) {
			$format = static::DATETIME_FORMAT;
		}	
		return (new DateTime($date))->format($format);
	}

	/**
	 * Retrieve the name of the current GIT branch.
	 * @return string
	 */
	public static function current_branch() { 
		try {
			$gitFile = file('../.git/HEAD', FILE_USE_INCLUDE_PATH);
			$branchName = explode("/", $gitFile[0], 3)[2];	
		} catch (Exception $ex) {
			$branchName = 'unknown branch';
		}	
		return trim($branchName);
	}
	
	/**
	 * Retrieve the current head checksum.
	 * @return string
	 */
	public static function current_head() { 
		try {
			$gitFile = file('../.git/refs/heads/' .static::current_branch(), FILE_USE_INCLUDE_PATH);
			$branchHead = $gitFile[0];	
		} catch (Exception $ex) {
			$branchHead = 'unknown commit';
		}	
		return trim($branchHead);
	}
}
	
