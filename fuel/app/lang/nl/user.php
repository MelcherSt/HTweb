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
			'update' => 'Instellingen zijn opgeslagen',
		],
		'error' => array(
			'no_id' => 'Kan gebruiker vinden met id <strong>:id</strong>.',
			'cur_pass' => 'Verkeerd wachtwoord.',
			'update' => 'Kan gebruiker niet bijwerken',
			'invalid_iban' => 'Het veld :label bevat geen geldig International Bank Acoount Number (IBAN)'
		),
	),
	'view' => [
		'btn' => 'Instellingen aanpassen',
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
