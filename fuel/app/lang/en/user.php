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
		'success' => [
			'update' => 'Settings have been saved',
		],
		'error' => array(
			'no_id' => 'Cannot not find user with id <strong>:id</strong>.',
			'cur_pass' => 'Incorrect password.',
			'update' => 'Cannot update user settings',
			'invalid_iban' => 'The field :label does not contain a valid International Bank Acoount Number (IBAN)'
		),
	),
	'view' => [
		'btn' => 'Edit settings',
		'na' => 'N/a',
	],
	'edit' => [
		'title' => 'Edit settings',
		'label' => 'Change password',
		'placeholder' => [
			'current_pass' => 'Current password',
			'new_pass' => 'New password',
			're_pass' => 'Re-type password',
		],
		'btn' => 'Save settings',
	],
  );
