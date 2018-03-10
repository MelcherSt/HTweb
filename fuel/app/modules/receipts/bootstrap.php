<?php

namespace Receipts;

\Event::register('gather_notifications', function() {
	$type = 'RECEIPT_REMINDER';
	$users = \Auth\Model\Auth_Permission::query()
					->where('area', 'products')
					->where('permission', 'administration')
					->get_one()
			->users;
	
	foreach ($users as $user) {
		$user_id = $user->id;
		$count = Model_Receipt::query()
				->where(\DB::expr('DATE_ADD(date, INTERVAL 70 DAY)'), '>', \DB::expr('CURDATE()'))
				->count();

		// No receipts last 70 days
		if ($count == 0 && !\Model_Notification::has_notifications($type, $user_id)) {
			\Model_Notification::fire_notification('Tijd voor een nieuwe afrekening',
					'Er is de laatste 3 maanden geen afrekening gedaan!',
					'fa fa-money',
					'receipts/admin', $user_id, $type);
		}

		if ($count > 0 && \Model_Notification::has_notifications($type, $user_id)) {
			\Model_Notification::scrub_notifications($type, $user_id);
		}
	};
});



