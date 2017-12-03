<?php
/**
 * Applies theme to pages.
 * @author Melcher
 */
class Controller_Core_Theme extends Controller_Core_View {
	
	/**
	 * The content that will be displayed in the page template.
	 * @var string
	 */
	protected $content;
		
	/**
	 * The title that will be displayed on the page and in the browser bar.
	 * @var string 
	 */
	protected $title;
	
	/**
	 * The sub title shown on the page.
	 * @var string 
	 */
	protected $title_sub;
	
	public function before() {
		parent::before();
		
		// Set the default template
		$this->theme = \Theme::instance();
		$this->theme->set_template('template/default');
		$this->theme->set_partial('navbar', 'partials/navbar');	
		$this->theme->set_partial('footer', 'partials/footer');	
		$this->theme->set_partial('header', 'partials/header');
		
	}
	
	public function after($response) {	
		// Start filling in template and its partials
		$this->theme->get_template()
				->set('content', $this->content)
				->set('title', $this->title);
				
		if($this->theme->has_partials('header')) {
			$this->theme->set_partial('header', 'partials/header')
				->set([
					'title' => $this->title,
					'title_sub' => $this->title_sub
				]);
		}
		
		// Inject menu items into template navbar partial when present
		if ($this->theme->has_partials('navbar')) {
			$menu_root_name = \Auth::check() ? 'main' : 'main-public';
			$this->theme->set_partial('navbar', 'partials/navbar')
					->set('menu_root_name', $menu_root_name);
		}
		
		if(empty($response) || !$response instanceof \Response) {
			$response = \Theme::instance();
		}
		return parent::after($response);
	}
}