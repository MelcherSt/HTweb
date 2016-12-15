<?php

return array(
	'name' => 'Gebruiker',
	'name_plural' => 'Gebruikers',
	'field' => array(
		'name' => 'Naam',
		'username' => 'Gebruikersnaam',
		'iban' => 'IBAN',
		'phone' => 'Telefoon',
		'start_year' => 'Start jaar',
		'end_year' => 'Eind jaar',
		'email' => 'Email',
		'img' => 'Avatar',
		'lang' => 'Taal',
		
	),
	'language' => [
		'en' => 'Engels',
		'nl' => 'Nederlands',
	],	
	'alert' => array(
		'success' => [
			
		],
		'error' => array(
			'no_id' => 'Kon geen gebruiker vinden met id <strong>:id</strong>.',
		),
	),
	'view' => [
		'btn' => 'Account bewerken',
		'na' => 'N.v.t',
	],
	'edit' => [
		'label' => 'Wachtwoord veranderen',
		'placeholder' => [
			'current_pass' => 'Huidig wachtwoord',
			'new_pass' => 'Nieuw wachtwoord',
			're_pass' => 'Herhaal nieuw wachtwoord',
		],
		'btn' => 'Instellingen opslaan',
	],
  );
