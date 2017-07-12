<?php

class Controller_Core_Theme extends Controller_Core_Secure {
	
	protected $content;
	public $page_title;
	public $title;
	public $sub_title;
	
	public function after($response) {		
		$this->theme = \Theme::instance();
		
		$this->theme->set_template('template/default')
				->set('content', $this->content)
				->set('title', $this->title);
		
		$this->theme->set_partial('navbar', 'partials/navbar');	
		$this->theme->set_partial('footer', 'partials/footer');	
		$this->theme->set_partial('header', 'partials/header')
				->set([
					'page_title' => $this->page_title,
					'title' => $this->title,
					'sub_title' => $this->sub_title
				]);
		
		if(empty($response) || !$response instanceof \Fuel\Core\Response) {
			$response = \Fuel\Core\Response::forge(\Theme::instance());
		}
		return parent::after($response);
	}
}