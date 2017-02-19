<?php
/**
 * Implement secure controller access. 
 * By default all access is denied when not authenticated.
 */
class Controller_Secure extends Controller_Base
{
	/**
	 * Must be set by child whenever it serves public accessible content.
	 * @var boolean 
	 */
	protected $public_access = false; 
	
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
				
				if(!$this->public_access) {
					// If the page is not publicly accessible and we're not logged-in, redirect to login
					Response::redirect('gate/login');
				}	
			} else if(isset($this->permission_required)) {
				if(!\Auth::has_access($this->permission_required)) {
					throw new HttpNoAccessException();
				}
			}
		}
	}
}