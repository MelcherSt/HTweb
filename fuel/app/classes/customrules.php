<?php
/**
 * Custom validation rules
 */
class CustomRules {
	
	/**
	 * Check if a given two char language identification is a valid system language
	 * @param type $lang
	 * @return type
	 */
	public static function _validation_valid_lang($lang=null) {	
		if(!($pass = in_array($lang, Controller_Core_View::SYSTEM_LANGS))) {
			Validation::active()->set_message('valid_lang', __('user.alert.error.invalid_lang', ['label' => ':label']));
		}
		return $pass;
	}
	
	/**
	 * Check if a given string is a valid IBAN. If empty string, pass. 
	 * @param type $iban
	 * @return boolean
	 */
	public static function _validation_valid_iban($iban=null) {
		// Pass on empty
		if(empty($iban)) {
			return true;
		}
		
		// Create shifted version (bank id at the end)
		$iban = strtolower($iban);
		$iban_shifted_arr = str_split(substr($iban, 4) . substr($iban,0,4));
		$iban_numbers = '';
		$result = '';

		// Convert all character to integers
		foreach ($iban_shifted_arr as $char) {
			if ( !is_numeric($char) ) {
				$char = CustomRules::to_number($char);
			}
			$iban_numbers .= $char;
		}
		
		// Calc mod 97 of each section (length 5)
		do {
			$a = (int)$result . substr($iban_numbers, 0, 5);
			$iban_numbers = substr($iban_numbers, 5);
			$result = $a % 97;
		}
		while (strlen($iban_numbers));

		// Verify final mod equals 1
		
		$pass = (int)$result == 1;
		
		if(!$pass) {
			Validation::active()->set_message('valid_iban', __('user.alert.error.invalid_iban', ['label' => ':label']));
		}
		return $pass;
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