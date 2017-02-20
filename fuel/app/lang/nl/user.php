<?php

return array(
	'name' => 'Gebruiker',
	'name_plural' => 'Gebruikers',
	'empty_list' => 'Er zijn geen gebruikers om hier te tonen',
	'field' => array(
		'name' => 'Naam',
		'username' => 'Gebruikersnaam',
		'surname' => 'Achternaam',
		'active' => 'Actief',
		'iban' => [
			'' => 'IBAN',
			'show' => 'Toon IBAN',
			],
		'phone' => 'Telefoon',
		'start_year' => 'Start jaar',
		'end_year' => 'Eind jaar',
		'email' => 'Email',
		'img' => 'Avatar',
		'lang' => 'Taal',	
		'group' => 'Groep'
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
			'no_id' => 'Kan gebruiker met id <strong>:id</strong> niet vinden.',
			'cur_pass' => 'Verkeerd wachtwoord.',
			'update' => 'Kan gebruiker niet bijwerken',
			'invalid_iban' => 'De ingevulde <strong>:label</strong> is niet een correct International Bank Acoount Number (IBAN)',
			'invalid_lang' => 'De geselecteerde <strong>:label</strong> is niet geldig.',
		),
	),
	'wall' => [
		'title' => 'De Muur',
		'sub' => '',
		'msg' => 'Deze pagina laat de lijstjes van alle huidige inwoners zien.',
		'btn' => 'Lijst van alle gebruikers',
	],
	'view' => [
		'btn' => 'Instellingen aanpassen',
		'na' => 'N.v.t.',
	],
	'edit' => [
		'title' => 'Instellingen aanpassen',
		'label' => 'Wachtwoord veranderen',
		'placeholder' => [
			'current_pass' => 'Huidig wachtwoord',
			'new_pass' => 'Nieuw wachtwoord',
			're_pass' => 'Herhaal nieuw wachtwoord',
		],
		'btn' => 'Instellingen opslaan',
	],
	'create' => [
		'btn' => 'Gebruiker toevoegen'
	]
  );
