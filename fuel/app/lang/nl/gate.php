<?php

return [
	'login' => [
		'title' => 'Inloggen',
		'msg' => 'Je staat op het punt het beveiligde deel van de site te betreden.',
		'reset' => 'Ik ben mijn wachtwoord vergeten',
		'btn' => 'Login',
		'label' => [
			'username' => 'Email of gebruikersnaam',
			'pass' => 'Wachtwoord',
			'remember_me' => 'Onthoud mij',
		],
	],
	'empty_list' => 'Er zijn geen uitstaande tokens',
	'reset' => [
		'title' => 'Wachtwoord resetten',
		'msg' => 'Voer je email adres in om een mail met wachtwoord reset instructies te ontvangen.',
		'btn' => 'Wachtwoord reset aanvragen',
		'label' => [
			'mail' => 'Email',
		],
		'mail' => [
			'subject' => 'Wachtwoord reset aanvrag',
			'body' => 'Er is een wachtwoord reset aanvrag gedaan voor het account op ' . __('site_title') . ' ' . __('site_sub') . ' dat geregistreerd staat met dit mail adres.' .
			'Negeeer deze mail als je geen aanvraag hebt ingedient. Om je wachtwoord te resetten klik je op onderstaande link: <br><br>' .
			'http://:link <br><br> --Admin',
		],
	],
	'alert' => [
		'success' => [
			'reset_mail' => 'Als het ingevoerde mail adres bij een account hoort ontvang je binnenkort een mail met instructies',
			'pass_changed' => 'Wacthwoord gewijzigd',
		],
		'error' => [
			'invalid_token' => 'Ongeldig reset token',
			'expired_token' => 'Reset token is verlopen',
			'pass_mismatch' => 'De wachtwoorden komen niet overeen',
		]
	]
];

