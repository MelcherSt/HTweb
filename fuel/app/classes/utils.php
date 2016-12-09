<?php

class Utils {
	/**
	 * Redirect to 404 with given message
	 * @param type $message
	 * @throws \HttpNotFoundException
	 */
	public static function handle_irrecoverable_error($message=null) {
		if(isset($message)) {
			\Session::set_flash('error', e($message));
		}		
		throw new \HttpNotFoundException();
	} 	
	
	
	public static function handle_recoverable_error($message=null, $redirect=null) {
		if(isset($message)) {
			\Session::set_flash('error', e($message));
		}
		if (isset($redirect)) {
			return \Response::redirect($redirect);
		}
	}
	
	/**
	 * Check if given string can be date formatted Y-m-d
	 * @param type $date
	 * @return boolean
	 */
	public static function valid_date($date) {
		$d = \DateTime::createFromFormat('Y-m-d', $date);
		return $d && $d->format('Y-m-d') === $date;
	}
	
	/**
	 * Check if a given string is a valid IBAN 
	 * @param type $iban
	 * @return boolean
	 */
	public static function valid_iban($iban) {
		$iban = strtolower($iban);
		$iban_shifted_arr = str_split(substr($iban, 4) . substr($iban,0,4));
		$iban_new = "";
		$result = "";

		// Convert bank id to all ints
		foreach ($iban_shifted_arr as $char) {
			if ( !is_numeric($char) ) {
				$char = Utils::to_number($char);
			}
			$iban_new .= $char;
		}
		
		
		do {
			$a = (int)$result . substr($iban_new, 0, 5);
			$iban_new = substr($iban_new, 5);
			$result = $a % 97;
		}
		while (strlen($iban_new));

		return (int)$result == 1;
	}
	
	/**
	 * Converts character to a number representing its place in the alphabet
	 * @param type $character
	 * @return int
	 */
	private static function to_number($character) {
		if (!$character){
			return 0;		
		}	
		return ord(strtolower($character)) - 87;
	}
}
	
