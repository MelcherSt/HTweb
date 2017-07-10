<?php

return [
	'title' => 'Producten',
	'title_admin' => 'Producten',
	'name' => 'Product',
	'name_plural' => 'Producten',
	'empty_list' => 'Er zijn geen producten om hier te tonen',
	'field' => [
		'date' => 'Datum toegevoegd',
		'paid_by' => 'Betaald door',
		'name' => 'Productnaam',
		'notes' => 'Beschrijving',
		'approved' => 'Goedgekeurd',
		'cost' => 'Kosten',
		'amount' => 'Aantal',
		'participant' => 'Deelnemer',
		'participant_plural' => 'Deelnemers',
	],
	'index' => [
		'btn' => [
			'add_product' => 'Product toevoegen',
		],
		'paid_by_me' => 'Producten die ik kocht',
		'paid_for_me' => 'Producten die anderen voor mij kochten',
		'msg' => 'Deze lijst laat onverrekende producten zien die jij kochten of anderen voor jou kochten. Voor een lijst met verrekende producten, kijk op de pagina ',
	],
		'view' => [
		'btn' => [
			'update_product' => 'Product bijwerken',
		],
	],
	'modal' => [
		'create' => [
			'title' => 'Nieuw product toevoegen',
			'msg' => 'Voeg hier een product toe dat je voor een ander hebt aangeschaft.',
			'participants' => 'Selecteer gebruikers voor wie je dit product kocht.',
			'btn' => 'Product toevoegen'
		],
		'remove' => [
			'title' => 'Product verwijderen',
			'msg' => 'Weet je zeker dat je dit product wilt verwijderen: ',
			'btn' => 'Product verwijderen',
		]
	],
	'alert' => [
		'success' => [
			'create_product' => 'Product <strong>:name</strong> is aangemaakt.',
			'remove_product' => 'Product <strong>:name</strong> is verwijderd.',
		],
		'error' => [
			'no_product' => 'Kan geen product vinden product met id <strong>:id</strong>.',
		]
	]
];
