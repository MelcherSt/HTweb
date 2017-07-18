<?php

/**
 * Makes sure localization is setup and loaded. Extend this class
 * for localization support.
 */
class Controller_Core_Lang extends Controller_Core_Secure {
	
	const SYSTEM_LANGS = ['nl', 'en'];
	const DEFAULT_LANG = 'en';
	const FALLBACK_LANG = 'en';
	
	/**
	 * Indicates whether the localization files were loaded to prevent 
	 * re-loading the files.
	 * @var boolean
	 */
	private static $current_lang = null;
	
	public function before() {
		Controller_Core_Lang::load_localization();
		parent::before();
	}
	
	/**
	 * Determine current localization settings and load the corresponding 
	 * language files. Can be called by any controller whenever early <br>
	 * initialization of localization files is needed. Normally, files will be loaded 
	 * automatically in the before method of the language controller.
	 * @return string The identifier for the currently active language.
	 */
	public static function load_localization() : string {
		$lang = Controller_Core_Lang::DEFAULT_LANG;
		$user = Model_User::get_current();	
		if(!empty($user) && !empty($lang_temp = $user->lang)){
				$lang = $lang_temp;
		} 
		
		// Set language based on preferences
		\Config::set('language', $lang);
		\Config::set('language_fallback', Controller_Core_Lang::FALLBACK_LANG);
		
		// Set locale
		$locales = \Config::get('locales');
		setlocale(LC_ALL, $locales[$lang]);
		
		// Pre-load all localization files
		\Lang::load('template'); 
		\Lang::load('gate', 'gate');
		\Lang::load('session', 'session');
		\Lang::load('product', 'product');
		\Lang::load('receipt', 'receipt');
		\Lang::load('user', 'user');
		\Lang::load('actions', 'actions');	
		\Lang::load('alert', 'alert');
		\Lang::load('content', 'content');
		\Lang::load('dashboard', 'dashboard');
		\Lang::load('stats', 'stats'); 	
		\Lang::load('privileges', 'privileges');

		// Set global language for view
		View::set_global('language', $lang);
		Controller_Core_Lang::$current_lang = $lang;
		return $lang;
	}
}