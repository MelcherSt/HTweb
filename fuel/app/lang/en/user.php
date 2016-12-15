<?php

return array(
	'name' => 'User',
	'name_plural' => 'Users',
	'field' => array(
		'name' => 'Name',
		'username' => 'Username',
		'iban' => 'IBAN',
		'phone' => 'Phone',
		'start_year' => 'Start year',
		'end_year' => 'End year',
		'email' => 'Email',
		'img' => 'Avatar',
		'lang' => 'Language',
		
	),
	'language' => [
		'en' => 'English',
		'nl' => 'Dutch',
	],	
	'alert' => array(
		'error' => array(
			'no_id' => 'Could not find user with id <strong>:id</strong>.',
		),
	),
	'view' => [
		'btn' => 'Edit account',
		'na' => 'N/a',
	],
	'edit' => [
		'label' => 'Change password',
		'placeholder' => [
			'current_pass' => 'Current password',
			'new_pass' => 'New password',
			're_pass' => 'Re-type password',
		],
		'btn' => 'Save settings',
	],
  );
