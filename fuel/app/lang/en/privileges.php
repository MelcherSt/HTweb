<?php

return array(
	'title' => 'Privileges',
	'empty_list' => 'You have no privileges',
	'field' => [
		'area' => 'Area',
		'permission' => 'Permission',
		'description' => 'Description',
	],
	'perm' => [
		'manage' => 'Manage'
	],
	'alert' => [
		'success' => [
			'create_enroll' => '<strong>:name</strong> has been granted this privilege.',
		],
		'error' => [
			'create_enroll' => 'Cannot grant this privilege to <strong>:name</strong>.',
			'no_permission' => 'Cannot find privilege with id <strong>:id</strong>.'
		],
	],
	'modal' => [
		'create_enroll' => [
			'title' => 'Grant privilege', 
			'msg' => 'Grant a user this privilege.',
			'btn' => 'Grant privilege',
		],
		'remove_enroll' => [
			'title' => 'Revoke privilege' ,
			'msg' => 'Are you sure you want to revoke this privilege for',
			'btn' => 'Revoke privilege',
		],
	],
);