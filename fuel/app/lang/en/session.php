<?php

return [
	'title' => 'Sessions',
	'title_admin' => 'Sessions',
	'name' => 'Session',
	'name_plural' => 'Session',
	'empty_list' => 'There are no sessions to show here',
	'type' => 'session',
	'role' => [
		'name' => 'Role',
		'name_plural' => 'Roles',
		'dishwasher' => 'Dishwasher',
		'dishwasher_plural' => 'Dishwashers',
		'cook' => 'Cook',
		'cook_plural' => 'Cooks',
		'participant' => 'Participant',
		'participant_plural' => 'Participants',
	],
	'day' => [
		'yesterday' => 'Yesterday',
		'today' => 'Today',
		'tomorrow' => 'Tomorrow'
	],
	'field' => [
		'date' => 'Date', 
		'point' => 'Point', 
		'point_plural' => 'Points',
		'guest', 'Guest',
		'guest_plural' => 'Guests',
		'deadline' => 'Deadline',
		'notes' => 'Notes',
	],
	'alert' => [
		'deadline' => [
			'changed' => 'The deadline has been changed! The new deadline is today at <strong>:time</strong>.',
			'passed' => 'The deadline is past due.',
			'no_join' => 'You can no longer enroll.',
			'no_leave' => 'You can no longer unenroll.',
			'cook_edit' => 'For a limited amout of time you will be able to change the enrollments and enter the cost.',
		],
		'dishes' => 'Did you do the dishes? Be sure to enroll as dishwasher before the end of the day!',
		'success' => [
			'create_enroll' => '<strong>:name</strong> has been enrolled.',
			'update_enroll' => 'Enrollment for </strong>:name</strong> has been updated.',
			'remove_enroll' => 'Enrollment for <strong>:name</strong> has been removed.',
			'update_session' => 'Session has been updated.',
			'remove_session' => 'Session has been removed.',
		],
		'error' => [
			'create_enroll' => 'Cannot enroll <strong>:name</strong>.',
			'update_enroll' => 'Cannot update enrollment for <strong>:name</strong>.',
			'remove_enroll' => 'Cannot remove enrollment for <strong>:name</strong>.',
			'no_session' => 'Cannot find session with date <strong>:date</strong>.',
			'no_enrollment' => 'There is no known enrollment for <strong>:name</strong>',
			'deadline_passed' => 'Cannot (un)enroll after deadline.',
			'update_session' => 'Cannot update session',
			'remove_session' => 'Cannot remove session',
			'guests' => 'Cannot bring less than 0 or more than :max_guests guests.',
			'no_perm' => 'You do not have sufficient permissions to perform this action <strong>:action</strong>',
		],
	],
	'modal' => [
		'create_enroll' => [
			'title' => 'Create enrollment', 
			'msg' => 'Enroll a new user into this session.',
			'btn' => 'Create enrollment',
		],
		'remove_enroll' => [
			'title' => 'Remove enrollment' ,
			'msg' => 'Are you sure you want to unenroll',
			'btn' => 'Remove enrollment',
		],
		'edit_enroll' => [
			'title' => 'Edit enrollment',
			'msg' => 'You are editing the enrollment of',
			'btn' => 'Update enrollment',
		],
		'remove' => [
			'title' => 'Remove session',
			'msg' => 'Are you sure you want to remove this session',
			'btn' => 'Remove session',
		],
		'convert' => [
			'title' => 'Convert session to product',
			'msg' => 'Converting this session to a product will delete this '
			. 'session and instead create a product. This means no points will be distributed. <br><br>'
			. '<strong>This action cannot be undone. Are you sure you want to convert this session to a product?</strong>',
			'btn' => 'Delete session and create product',
		]
	],
	'index' => [
		'msg' => 'This list only shows unsettled sessions. For a list of settled session you participated in, please see',
		'cooked_for_me' => 'Meals others cooked for me',
		'cooked_by_me' => 'Meals I cooked',
		'quick_enroll' => 'Quick enrollment',
		'quick_btn' => 'Enroll for selected',
		'convert_btn' => 'Convert to product',
	],
	'view' => [
		'msg' => 'There are :p_count participants of whom :g_count are guests.',
		'label' => [
			'cook' => 'I feel like cooking',
			'guests'=> 'I\'m bringing guests',
			'later' => 'I\'ll be eating later',
		],
		'btn'=> ['enroll' => 'Enroll',
			'update_session' => 'Update session',
			'update_enrollment' => 'Update enrollment',
			'unenroll' => 'Unenroll',
			'add_enroll' => 'Enroll extra user',
			'enroll_dish' => 'I did the dishes',
			'unenroll_dish' => 'Actually, I did not do the dishes',
		],
	],
	'stats' => [
		'title' => 'Statistics',
		'next_cook_msg' => 'Most likely the next cook',
		'all_time' => 'All-time',
		'average' => 'Average',
		'widget' => [
			'msg' => [
				'tentative' => 'of which :points are tentative'
			],
			'link' => 'See all statistics',
		]
	],
	'widget' => [
		'msg' => [
			'enrolled_single' => 'And that\'s you!',
			'enrolled_many' => 'And you\'re one of them!',
			'no_cook' => 'Uh, there\'s no cook yet.',
			'deadline_passed' => 'Today\'s deadline is past due.'
		],
		'link' => [
			'enroll_first' => 'Be the first!',
			'enroll_many' => 'Why don\'t you join them?',
			'deadline_passed' => 'Did you do the dishes?',
			'no_cook' => 'Save the day, be a cook!',
			'today' => 'View today\'s session',
		]
	],
];
