<?php
return array(
	// Users 
	'api/v1/users/(:num)' => 'api/v1/users/single/$1',
	
	// Sessions 
	'api/v1/sessions/(:num)' => 'api/v1/sessions/single/$1',
	'api/v1/sessions/(:num)/enrollments' => 'api/v1/sessions/enrollments/index/$1',
	'api/v1/sessions/(:num)/enrollments/:user_id' => 'api/v1/sessions/enrollments/single/$1',
	'api/v1/sessions/(:num)/roles' => 'api/v1/sessions/roles/$1',
	'api/v1/sessions/(:num)/notenrolled' => 'api/v1/sessions/enrollments/notenrolled/$1',
	'api/v1/sessions/(:num)/dishwashers' => 'api/v1/sessions/enrollments/dishwashers/$1',
);
