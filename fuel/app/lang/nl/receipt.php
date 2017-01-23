<?php

return [
	'title' => 'Mijn Afrekeningen',
	'title_admin' => 'Afrekeningen',
	'empty_list' => '<em>Er zijn geen verrekeningen om hier te tonen</em>',
	'field' => [
		'notes' => 'Beschrijving',
		'date' => 'Aanmaak datum',
		'balance' => 'Balans',
	],
	'index' => [
		'msg' => 'Hier vind je al je afrekeningen',
	],
	'view' => [
		'point_check' => 'Punten controlesom',
		'balance_check' => 'Balans controlesom',
		'title' => 'Kosten- en puntendistributie',
		'msg' => 'Een afrekening laat de punten en kosten distributie over een gegeven periode zien. '
		. 'Dit betekend dat alleen de gewonnen en verloren punten in die periode zichtbaar zijn in onderstaande grafiek.',
		'detail' => 'Gedetailleerd overzicht',
		'trans_schema' => [
			'title' => 'Transactie schema',
			'from' => 'Van',
			'to' => 'Naar',
			'amount' => 'Bedrag',
		]
	],
	'alert' => [
		'error' => [
			'no_receipt' => 'Kan geen afrekening vinden met id <strong>:id</strong>.',
		]
	]
];