<?php

return [
	'login' => [
		'title' => 'Login',
		'msg' => 'You are about the enter the protected area of the site.',
		'reset' => 'I forgot my password',
		'btn' => 'Login',
		'label' => [
			'username' => 'Email or username',
			'pass' => 'Password',
			'remember_me' => 'Remember me',
		],
	],
	'reset' => [
		'title' => 'Reset your password',
		'msg' => 'Enter your mail address to send a mail with instructions to reset your password.',
		'btn' => 'Request password reset',
		'label' => [
			'mail' => 'Email',
		],
		'mail' => [
			'subject' => 'Password reset requested',
			'body' => 'A password reset was requested for the account on ' . __('site_title') . ' which was registered using this mail address.' .
			'If you did not request your password to be reset, please ignore this mail. Otherwise, visit this link within the next hour to reset your password:' .
			':link',
		],
	],
	'alert' => [
		'success' => [
			'reset_mail' => 'If the given mail address is associated with an account, you will receive a mail containing instructions to reset your password.',
			'pass_changed' => 'Your password has been changed',
		],
		'error' => [
			'invalid_token' => 'The given reset token is invalid',
			'expired_token' => 'The given token has expired',
			'pass_mismatch' => 'The given passwords do not match',
		]
	]
];

