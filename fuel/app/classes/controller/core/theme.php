<?php
/**
 * Applies theme to pages.
 */
class Controller_Core_Theme extends Controller_Core_Base {
	
	/**
	 * The content that will be displayed in the page template.
	 * @var string
	 */
	protected $content;
	
	/**
	 * The title that will be displayed on the page.
	 * If $title is not set, this will be used as title.
	 * @var string 
	 */
	protected $page_title;
	
	/**
	 * The HTML title of the page shown by the browser.
	 * If $page_title is not set, no title will be shown on the page.
	 * @var string 
	 */
	protected $title;
	
	/**
	 * The sub title shown on the page.
	 * @var string 
	 */
	protected $sub_title;
	
	public function before() {
		// Set the default template
		$this->theme = \Theme::instance();
		$this->theme->set_template('template/default');
		parent::before();
	}
	
	public function after($response) {	
		// Start filling in template and its partials
		$this->theme->get_template()
				->set('content', $this->content)
				->set('title', $this->title);
		
		$this->theme->set_partial('navbar', 'partials/navbar');	
		$this->theme->set_partial('footer', 'partials/footer');	
		$this->theme->set_partial('header', 'partials/header')
				->set([
					'title' => $this->title,
					'page_title' => $this->page_title,
					'sub_title' => $this->sub_title
				]);
		
		if(empty($response) || !$response instanceof \Fuel\Core\Response) {
			$response = \Fuel\Core\Response::forge(\Theme::instance());
		}
		return parent::after($response);
	}
}