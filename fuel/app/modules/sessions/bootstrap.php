<?php

namespace Sessions;

\Asset::add_path('assets/js/sessions', 'js');

\Event::register('gather_widgets',function(\Data $data) {
	$data->put_item('sessions/stats/widget');
	$data->put_item('sessions/widget');
});

\Event::register('gather_notifications', function() {
	$type = 'SESSION_COST';

	$sessions = Model_Session::query()
						->where('settled', false)
						->where(\DB::expr('DATE_ADD(date, INTERVAL 1 DAY)'),
								'<', \DB::expr('CURDATE()'))
						->where('cost', 0)
						->get();
	
	$users = [];
	foreach ($sessions as $session) {
		$enrollments = $session->get_cook_enrollments();
		foreach ($enrollments as $enrollment) {
			array_push($users, [$enrollment->user, $session]);
		}
	}
	
	foreach ($users as [$user, $session]) {
		$user_id = $user->id;
		$date = \Utils::format_date($session->date, 'Y-m-d');
		$type = $type . $date;
			
		if (!\Model_Notification::has_notifications($type, $user_id)) {
			\Model_Notification::fire_notification('Vergeet de kosten niet',
					'Je hebt nog geen kosten ingevoerd voor het eten van ' . \Utils::format_date($session->date),
					'fa fa-cutlery',
					'sessions/' . $date , $user_id, $type);
		}
	};
});






