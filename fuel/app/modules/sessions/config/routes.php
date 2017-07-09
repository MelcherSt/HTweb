<?php
return array(
	// YYYY-mm-dd to /view/YYYY-mm-dd
	'sessions/(\d{4}-\d{2}-\d{2})' => 'sessions/view/$1',		
	'sessions/admin/(\d{4}-\d{2}-\d{2})' => 'sessions/admin/view/$1',
	
	// Fix params on index (put_index($id) etc.)
	'sessions/admin/(:segment)' => 'sessions/admin/index/$1',	// Fixes HTTP verb routes on _index
	'sessions/convert/(:segment)' => 'sessions/convert/index/$1',
);
