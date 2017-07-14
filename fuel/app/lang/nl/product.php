<?php

return [
	'title' => 'Producten',
	'name' => 'Product',
	'name_plural' => 'Producten',
	'empty_list' => 'Er zijn geen producten om hier te tonen',
	'field' => [
		'date' => 'Datum',
		'paid_by' => 'Betaald door',
		'name' => 'Naam',
		'notes' => 'Beschrijving',
		'approved' => 'Goedgekeurd',
		'cost' => 'Kosten',
		'amount' => 'Aantal',
		'participant' => 'Deelnemer',
		'participant_plural' => 'Deelnemers',
		'macro_last_executed' => 'Laatst uitgevoerd',
	],
	'index' => [
		'btn' => [
			'add_product' => 'Product toevoegen',
		],
		'msg' => 'Deze lijst laat onverrekende producten zien die jij hebt gekocht of anderen voor jou kochten. Voor een lijst met verrekende producten, kijk op de pagina ',
		'tooltip_macro' => 'Dit product werd automatisch gegenereerd ',
	],
	'view' => [
		'btn' => [
			'update_product' => 'Product bijwerken',
		],
	],
	'admin' => [
		'table_products' => 'Alle producten',
		'table_macros' => 'Product macro\'s',
		'index' => [
			'btn' => [
				'add_product' => 'Product toevoegen',
				'add_macro' => 'Product macro toevoegen',
				'execute_macros' => 'Alle product macro\'s uitvoeren'
			]
		],
		'create' => [
			'participants' => 'Selecteer de gebruikers voor wie je dit product kocht. Inactieve gebruikers zijn oranje gekleurd en moeten met de hand geselecteerd worden.'
		],
		'create_macro' => [
			'title' => 'Macro',
			'msg' => 'Voeg een product macro toe. Een product macro kan worden gebruikt om maandelijks terugkerende kostenposten automatisch te herhalen.',
			'btn' => 'Product macro aanmaken',
		]
	],
	'create' => [
		'participants' => 'Selecteer de gebruikers voor wie je dit product kocht.',
		'btn' => 'Product toevoegen'
	],
	'modal' => [
		'remove' => [
			'title' => 'Product verwijderen',
			'msg' => 'Weet je zeker dat je dit product wilt verwijderen',
			'btn' => 'Product verwijderen',
		],
		'remove_macro' => [
			'title' => 'Product macro verwijderen',
			'msg' => 'Weet je zeker dat je de product macro wilt verwijderen',
			'btn' => 'Product macro verwijderen',
		],
	],
	'alert' => [
		'success' => [
			'create_product' => 'Product <strong>:name</strong> is aangemaakt.',
			'remove_product' => 'Product <strong>:name</strong> is verwijderd.',
			'create_macro' => 'Product macro <strong>:name</strong> is aangemaakt.',
			'remove_macro' => 'Product macro <strong>:name</strong> is verwijderd.',
			'macros_executed' => 'Alle macro\'s uitgevoerd.',
		],
		'error' => [
			'not_found' => 'Kan geen product vinden product met id <strong>:id</strong>.',
			'no_users_selected' => 'Geen gebruikers geselecteerd. Selecteer tenminste 1 gebruiker.',
			'macros_executed' => 'Alle macro\'s uitgevoerd, maar :num producten waren niet aangemaakt.',
		]
	]
];
