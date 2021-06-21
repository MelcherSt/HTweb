<?php

return [
	'title' => 'Sessies',
	'title_admin' => 'Sessies',
	'name' => 'Sessie',
	'name_plural' => 'Sessies',
	'empty_list' => 'Er zijn geen sessies om hier te tonen',
	'type' => 'sessie',
	'role' => [
		'name' => 'Rol',
		'name_plural' => 'Rollen',
		'dishwasher' => 'Afwasser',
		'dishwasher_plural' => 'Afwassers',
		'cook' => 'Kok',
		'cook_plural' => 'Koks',
		'participant' => 'Deelnemer',
		'participant_plural' => 'Deelnemers',
	],
	'day' => [
		'yesterday' => 'Gisteren',
		'today' => 'Vandaag',
		'tomorrow' => 'Morgen'
	],
	'field' => [
		'date' => 'Datum', 
		'point' => 'Punt', 
		'point_plural' => 'Punten',
		'guest', 'Gast',
		'guest_plural' => 'Gasten',
		'deadline' => 'Deadline',
		'notes' => 'Notities',
	],
	'alert' => [
		'deadline' => [
			'changed' => 'De deadline is veranderd! De nieuwe deadline is vandaag om <strong>:time</strong>.',
			'passed' => 'De deadline is voorbij.',
			'no_join' => 'Je kunt je niet langer inschrijven.',
			'no_leave' => 'Je kunt je niet langer uitschrijven.',
			'cook_edit' => 'Voor een beperkte period kan je de inschrijvingen en kosten veranderen.',
		],
		'dishes' => 'Heb jij afgewassen? Zorg ervoor dat je je voor het einde van de dag inschrijft!',
		'success' => [
			'create_enroll' => '<strong>:name</strong> is ingeschreven.',
			'update_enroll' => 'Inschrijving voor </strong>:name</strong> is bijgewerkt.',
			'remove_enroll' => 'Inschrijving voor <strong>:name</strong> is verwijderd.',
			'update_session' => 'Sessie is bijgewerkt.',
			'remove_session' => 'Session is verwijderd.',
		],
		'error' => [
			'create_enroll' => 'Kan <strong>:name</strong> niet inschrijven.',
			'update_enroll' => 'Kan inschrijving voor <strong>:name</strong> niet bijwerken.',
			'remove_enroll' => 'Kan inschrijving voor <strong>:name</strong> niet verwijderen.',
			'no_session' => 'Kan geen sessie vinden met datum <strong>:date</strong>.',
			'no_enrollment' => 'Er is geen inschrijving bekend voor <strong>:name</strong>',
			'deadline_passed' => 'Kan niet in- of uitschrijven na de deadline.',
			'update_session' => 'Kan sessie niet bijwerken.',
			'remove_session' => 'Kan sessie niet verwijderen.',
			'guests' => 'Je kan niet minder dan 0 of meer dan :max_guests gasten meebrengen.',
			'no_perm' => 'Je hebt niet voldoende rechten om deze actie uit te voeren <strong>:action</strong>',
		],
	],
	'modal' => [
		'create_enroll' => [
			'title' => 'Nieuwe inschrijving', 
			'msg' => 'Schrijf een nieuwe gebruik in voor deze sessie.',
			'btn' => 'Inschrijving maken',
		],
		'remove_enroll' => [
			'title' => 'Verwijder inschrijving' ,
			'msg' => 'Je staat op het punt om deze gebruiker uit te schrijven',
			'btn' => 'Uitschrijven',
		],
		'edit_enroll' => [
			'title' => 'Wijzig inschrijving',
			'msg' => 'Je wijzigt de inscrhijving van',
			'btn' => 'Inschrijving bijwerken',
		],
		'remove' => [
			'title' => 'Verwijder sessie',
			'msg' => 'Je staat op het punt deze sessie te verwijderen',
			'btn' => 'Verwijder sessie',
		],
		'convert' => [
			'title' => 'Converteer sessie naar product',
			'msg' => 'Wanneer de sessie naar een product wordt geconverteerd zal deze sessie '
			. 'verwijderd worden en in plaats daarvan een product worden aangemaakt. Dit betekend dat er geen punten zullen worden verdeeld. <br><br>'
			. '<strong>Deze actie kan niet ongedaan worden gemaakt. Weet je zeker dat je deze sessie naar een product wilt converteren?</strong>',
			'btn' => 'Verwijder sessie en maak product',
		]
	],
	'index' => [
		'msg' => 'Deze lijst laat onverrekende sessies zijn. Voor een lijst met verrekende sessies, zie de pagina',
		'cooked_for_me' => 'Maaltijden die anderen voor mij kookten',
		'cooked_by_me' => 'Maaltijden die ik kookte',
		'quick_enroll' => 'Snel inschrijven',
		'quick_btn' => 'Inschrijven voor geselecteerde dagen',
		'convert_btn' => 'Converteer naar product',
	],
	'view' => [
		'msg' => 'Er zijn :p_count deelnemers waaronder :g_count gast(en).',
		'label' => [
			'cook' => 'Ik heb zin om te koken',
			'guests'=> 'Ik breng gasten',
			'later' => 'Ik eet later',
		],
		'btn'=> ['enroll' => 'Inschrijven',
			'update_session' => 'Sessie bijwerken',
			'update_enrollment' => 'Inschrijving bijwerken',
			'unenroll' => 'Uitschrijven',
			'add_enroll' => 'Schrijf extra gebruiker in',
			'enroll_dish' => 'Ik heb afgewassen',
			'unenroll_dish' => 'Eigenlijk heb ik niet afgewassen',
		],
	],
	'stats' => [
		'title' => 'Statistieken',
		'next_cook_msg' => 'Hoogstwaarschijnlijk de volgende kok',
		'all_time' => 'Langlopende',
		'average' => 'Gemiddelde',
		'widget' => [
			'msg' => [
				'tentative' => 'waarvan :points onder voorbehoud'
			],
			'link' => 'Bekijk alle statistieken',
		]
	],
	'widget' => ['msg' => [
			'enrolled_single' => 'En dat ben jij!',
			'enrolled_many' => 'En jij bent een van hen!',
			'no_cook' => 'Uh, er is nog geen kok.',
			'deadline_passed' => 'De deadline is voorbij.'
		],
		'link' => [
			'enroll_first' => 'Wees de eerste!',
			'enroll_many' => 'Eet je mee?',
			'deadline_passed' => 'Heb jij afgewassen?',
			'no_cook' => 'Red de dag, wees een kok!',
			'today' => 'Laat de sessie zien',
		]
	],
];
