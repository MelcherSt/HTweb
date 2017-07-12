<?php
/**
 * Ensures secure controller access. By default all unauthenticated access is denied.
 */
class Controller_Secure extends Controller_Base
{
	/**
	 * Must be set by child whenever it serves publicly accessible content.
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
	protected $permission = null;
	
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
				// Perform page specific authorization steps
				$this->pre_authorize();
			}	
		}
	}
	
	/**
	 * Pre-authorize this page by checking the required permission set. 
	 * Override this function to change authorization behavior.
	 */
	protected function pre_authorize() {
		if(isset($this->permission)) {
			$this->evaluate_permission($this->permission);
		}
	}
	
	/**
	 * Evaluate a permission. If current user does not have the required
	 * permission level, a 403 is returned. 
	 * @throws HttpNoAccessException
	 */
	private function evaluate_permission(string $permission) {
		if(!\Auth::has_access($permission)) {
			throw new HttpNoAccessException();
		}
	}
}