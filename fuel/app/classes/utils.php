<?php

class Utils {
	
	const CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz$_?!-0123456789';
	
	/**
	 * Redirect to 404 and show given error message
	 * @param string $message
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
	 * @param string $message
	 * @param string $redirect
	 * @return \Response
	 */
	public static function handle_recoverable_error($message, $redirect) {
		\Session::set_flash('error', ($message));
		return \Response::redirect($redirect);
	}
	
	/**
	 * Check if given string can be date formatted Y-m-d
	 * @param type $date
	 * @return boolean
	 */
	public static function valid_date($date) {
		if (empty($date)) {
			return false;
		}
		
		$d = \DateTime::createFromFormat('Y-m-d', $date);
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
	

}
	
