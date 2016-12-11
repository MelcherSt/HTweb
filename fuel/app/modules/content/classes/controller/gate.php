<?php

namespace Content;

/**
 * Controller regulating access and styling for content pages. 
 */
class Controller_Gate extends \Controller_Base {
	
	private $base_template;
	private $content_template;
	
	public function before() {
		parent::before();
		$post_id = \Request::active()->method_params[0];
		$post = Model_Post::find($post_id);
		
		if(isset($post)) {
			if(!$post->public && !\Auth::check()) {
				// No user is logged in, redirect to login
				Response::redirect('gate/login');
			} else {
				// Find and apply template
				$template = Model_Template::find($post->template);
				$this->base_template = $template->base_template;
				$this->content_template = $template->content_template;
				
				if(empty($this->content_template)) {
					$this->content_template = 'templates/content/default.php';
				}
			}
		} else {
			\Utils::handle_irrecoverable_error('No post with given id exists');
		}
	}
	
	public function after($response) {		
		// Create content from content_template
		$this->template->title = $this->template->post->title;
		// Inject post into content
		$data['post'] = $this->template->post;
		$this->template->content = \View::forge($this->content_template, $data);
		
		// Inject content into base template
		if(empty($this->base_template)) {
			// Delegate (use default)
			return parent::after($response);
		} else {
			// Use a custom base template
			return \Response::forge(\View::forge($this->base_template, $data)->render());	
		}	
	}
}
