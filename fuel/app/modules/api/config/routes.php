<?php
return array(
	// Sessions API
	'api/v1/sessions/(:num)' => 'api/v1/sessions/single/$1',
	'api/v1/sessions/(:num)/enrollments' => 'api/v1/sessions/enrollments/index/$1',
);
