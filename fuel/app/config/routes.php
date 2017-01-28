<?php
return array(
	'_root_'  => 'dashboard/index',  // The default route
	'_404_'   => 'errorhandler/404',    // The main 404 route
	'_403_'	  => 'errorhandler/403',
	
	'sessions/admin/(:segment)' => 'sessions/admin/index/$1', // Fixes HTTP verb routes on _index
);
