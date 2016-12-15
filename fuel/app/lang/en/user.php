<?php

return array(
	'name' => 'User',
	'name_plural' => 'Users',
	'field' => array(
		'name' => 'Name',
		'username' => 'Username',
		'iban' => 'IBAN',
		'phone', 'Phone',
		'start_year' => 'Start year',
		'end_year' => 'End year',
		'email' => 'Email',
		'img' => 'Avatar',
		
	),
	'alert' => array(
		'error' => array(
			'no_id' => 'Could not find user with id <strong>:id</strong>.',
		),
	),
	'view' => [
		'btn' => 'Edit account',
	],
	'edit' => [
		'btn' => 'Save',
	]
  );
