<?php
/**
 * Ensures secure controller access. By default all unauthenticated access is denied.
 */
class Controller_Secure extends Controller_Base
{
	/**
	 * Must be set by child whenever it serves public accessible content.
	 * @var boolean 
	 */
	protected $public_content = false; 
	
	/**
	 * Set by controller to identify an unauthorized (public) request.
	 * @var boolean 
	 */
	protected $public_request = false; 
	
	/**
	 * Must be set by child when a specific permission is required for accessing content.
	 * @var string 
	 */
	protected $permission_required = null;
	
	public function before() {		
		parent::before();

		if (Request::active()->controller !== 'Controller_Gate' or ! in_array(Request::active()->action, array('login', 'logout'))) {
			if (!Auth::check()) {
				// No user is logged in, this is a public request
				$this->public_request = true;
				
				if(!$this->public_content) {
					// Redirect to login if unauthenticated and content is not public
					$redirect = Uri::create('gate/login', [], ['destination' => Uri::string()]);
					Response::redirect($redirect);
				}	
			} else {
				$this->preauthorize();
			}	
		}
	}
	
	/**
	 * Check required permissions to visit this page. 
	 * Override this method to change behavior.
	 */
	protected function preauthorize() {
		if(isset($this->permission_required)) {
			$this->evaluate_permission();
		}
	}
	
	/**
	 * Evaluate the required permission 
	 * @throws HttpNoAccessException
	 */
	private function evaluate_permission() {
		if(!\Auth::has_access($this->permission_required)) {
			throw new HttpNoAccessException();
		}
	}
}