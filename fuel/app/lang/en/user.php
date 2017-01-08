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
			'invalid_iban' => 'The entered <strong>:label</strong> is not a valid International Bank Acoount Number (IBAN)',
			'invalid_lang' => 'The selected </strong>:label</strong> is invalid.',
		),
	),
	'wall' => [
		'title' => 'The wall',
		'sub' => 'of fame',
		'msg' => 'This page shows the photo frames of all current inhabitants.',
		'btn' => 'List of all users',
	],
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
