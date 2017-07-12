<?php
/**
 * Base controller
 * Loads localization for all modules, sets global current user variable
 */
class Controller_Core_Base extends Controller_Core_Secure {
	
	const SYSTEM_LANGS = ['nl', 'en'];
	const DEFAULT_LANG = 'en';
	const FALLBACK_LANG = 'en';
	
	/**
	 * List of additional scripts to be loaded
	 * @var array
	 */
	private $add_js = [];
	
	/**
	 * List of additional stylesheets to be loaded
	 * @var array
	 */
	private $add_css = [];
	
	/**
	 * Add additional stylesheet(s).
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
	 * Add additional script(s).
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
		if (($id = \Auth::instance()->get_user_id()) !== false) 	{
			$this->current_user = \Model_User::find($id[1]);
		}
		
		// Set localizations before rendering
		$this->load_localization();	
		
		// Set global current_user for view
		View::set_global('current_user', $this->current_user);
		parent::before();
	}
	
	public function after($reponse) {
		// Theme independant 
		$theme = \Theme::instance();
		$template = $theme->get_template();
		$template->set('add_js', $this->add_js);
		$template->set('add_css', $this->add_css);
		
		// Set menu items	
		if($theme->has_partials('navbar')) {
			$menu_items = []; 		
			if(Auth::check()) {
				$menu_items = [
						['sessions', __('session.title'), 'fa-cutlery'],
						['products', __('product.title'), 'fa fa-shopping-basket'],				
						['receipts', __('receipt.title'), 'fa-money'],
						['wall', __('user.wall.title'), 'fa-id-card'],
						['stats', __('stats.title'), 'fa-area-chart'],
				];
			}
			
			$theme->set_partial('navbar', 'partials/navbar')
					->set('menu_items', $menu_items)
					->set('active_item', \Uri::segment(1));
		}
		return parent::after($reponse);
	}
	
	/**
	 * Determine and pre-load localization.
	 */
	private function load_localization() {
		$lang = Controller_Core_Base::DEFAULT_LANG;
		
		if(isset($this->current_user)) {
			if (!empty($lang_temp = $this->current_user->lang)){
				$lang = $lang_temp;
			}
		} 
		
		// Set language based on preferences
		\Config::set('language', $lang);
		\Config::set('language_fallback', static::FALLBACK_LANG);
		
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
	}
}
