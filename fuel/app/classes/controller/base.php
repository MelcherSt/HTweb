<?php
/**
 * Base controller injecting user information in the $current_user variable
 * as well as settings the user's preferred language.
 */
class Controller_Base extends Controller_Template {
	const DEFAULT_LANGUAGE = 'en';
	
	public function before() {		
		$this->current_user = null;

		foreach (\Auth::verified() as $driver)
		{
			if (($id = $driver->get_user_id()) !== false)
			{
				$this->current_user = \Model_User::find($id[1]);
			}
			break;
		}
		
		$lang = Controller_Base::DEFAULT_LANGUAGE;
		
		if(!empty($this->current_user)) {
			if (!empty($lang_temp = $this->current_user->lang)){
				$lang = $lang_temp;
			}
		}
		
		// Set language based on preferences
		\Config::set('language', $lang);
		\Config::set('language_fallback', 'en');
		
		// Set locale
		$locales = \Config::get('locales');
		setlocale(LC_ALL, $locales[$lang]);
		
		// Pre-load all localization files
		\Lang::load('template'); 
		\Lang::load('session', 'session');
		\Lang::load('user', 'user');
		\Lang::load('actions', 'actions');	
		\Lang::load('alert', 'alert');
		\Lang::load('content', 'content');
		\Lang::load('dashboard', 'dashboard');
		\Lang::load('stats', 'stats'); 	

		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
		
		parent::before();
	}

}
