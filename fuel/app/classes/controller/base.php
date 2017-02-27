<?php
/**
 * Base controller
 * Loads localization for all modules, sets global current user variable
 */
class Controller_Base extends Controller_Template {
	
	const SYSTEM_LANGS = ['nl', 'en'];
	const DEFAULT_LANGUAGE = static::SYSTEM_LANGS[1];
	
	/**
	 * List of additional scripts to be loaded
	 * @var array
	 */
	protected $add_js = [];
	
	/**
	 * List of additional stylesheets to be loaded
	 * @var array
	 */
	protected $add_css = [];
	
	/**
	 * Add additional css
	 * @param mixed $sheet Either stylesheet name (excluding extension) or array of stylesheets.
	 */
	public function push_css($sheet) {
		if (is_array($sheet)) {
			foreach ($sheet as $single_sheet) {
				$this->push_css($single_sheet);
			}
		} else {
			if (!in_array($sheet, $this->add_css)) {
				array_push($this->add_css, $sheet);
			}
		}
	}
	
	/**
	 * Add additional script
	 * @param mixed $script Either scriptname (excluding extension) or array of scriptnames.
	 */
	public function push_js($script) {
		if (is_array($script)) {
			foreach ($script as $single_script) {
				$this->push_js($single_script);
			}
		} else {
			if (!in_array($script, $this->add_js)) {
				array_push($this->add_js, $script);
			}
		}
	}
	
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
		
		if(isset($this->current_user)) {
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

		// Set a global variable so views can use it
		View::set_global('current_user', $this->current_user);
		View::set_global('language', $lang);
		
		parent::before();
	}
	
	public function after($reponse) {
		$this->template->add_js = $this->add_js;
		$this->template->add_css = $this->add_css;
		
		return parent::after($reponse);
	}

}
