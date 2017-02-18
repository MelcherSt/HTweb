<?php

class Utils {
	
	const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$_?!-0123456789';
	
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
	
	public static function rand_str($len){
		$result = '';
		$charArray = str_split(\Utils::CHARS);
		for($i = 0; $i < $len; $i++){
			$randItem = array_rand($charArray);
			$result .= "".$charArray[$randItem];
		}
		return $result;
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
	

}
	
