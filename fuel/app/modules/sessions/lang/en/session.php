<?php

return array(
	'title' => 'My Sessions',
	'title_admin' => 'Sessions',
	'type' => 'session',
	'role' => array(
		'name' => 'Role',
		'name_plural' => 'Roles',
		'dishwasher' => 'Dishwasher',
		'dishwasher_plural' => 'Dishwasher',
		'cook' => 'Cook',
		'cook_plural' => 'Cooks',
		'participant' => 'Participant',
		'participant_plural' => 'Participants',
		),
	'day' => array(
		'yesterday' => 'Yesterday',
		'today' => 'Today',
		'tomorrow' => 'Tomorrow'
		),
	'field' => array(
		'date' => 'Date', 
		'point' => 'Point', 
		'point_plural' => 'Points',
		'cost' => 'Cost', 
		'guest', 'Guest',
		'guest_plural' => 'Guests',
		'deadline' => 'Deadline',
		'notes' => 'Notes',
		),
	'alert' => array(
		'deadline' => array(
			'changed' => 'The deadline has been changed! The new deadline is today at <strong>:time</strong>.',
			'passed' => 'The deadline is past due.',
			'no_join' => 'You can no longer join this session.',
			'no_leave' => 'You can no longer leave this session.',
			'cook_edit' => 'For a limited amout of time you will be able to change the enrollments users in this session and enter the cost.',
		),
		'dishes' => 'Did you do the dishes? Be sure to enroll as dishwasher before the end of the day!',
		'success' => array(
			'create_enroll' => '<strong>:name</strong> has been enrolled.',
			'update_enroll' => 'Enrollment for </strong>:name</strong> has been updated.',
			'remove_enroll' => 'Enrollment for <strong>:name</strong> has been removed.'
		),
		'error' => array(
			'create_enroll' => 'Cannot enroll <strong>:name</strong>.',
			'deadline_passed' => 'Cannot (un)enroll after deadline.',
			'no_session' => 'Cannot find session with date <strong>:date</strong>.',
			'no_enrollment' => 'There is no known enrollment for <strong>:name</strong>',
			'update_session' => 'Cannot update session',
			'update_enroll' => 'Cannot update enrollment for <strong>:name</strong>.',
			'remove_enroll' => 'Cannot remove enrollment for <strong>:name</strong>.',
			'too_many_guests' => 'Cannot bring more than :max_guests guests.',
		)
	),
	'modal' => array(
		'create_enroll' => array(
			'title' => 'Create enrollment', 
			'msg' => 'Enroll a new user into this session.',
			'btn' => 'Create enrollment',
		),
		'remove_enroll' => array(
			'title' => 'Remove enrollment' ,
			'msg' => 'Are you sure you want to unenroll',
			'btn' => 'Remove enrollment',
		),
		'edit_enroll' => array(
			'title' => 'Edit enrollment',
			'msg' => 'You are editing the enrollment of',
			'btn' => 'Update enrollment',
		),
	),
	'index' => array(
		'msg' => 'This list only shows unsettled sessions. For a list of settled session you participated in, please see',
	),
	'view' => array(
		'msg' => 'There are :p_count participants of which :g_count are guests.',
		'label' => array(
			'cook' => 'I feel like cooking',
			'guests'=> 'I\'m bringing guests',
		),
		'btn'=> array(
			'enroll' => 'Join session',
			'update' => 'Update session',
			'unenroll' => 'Leave session',
			'add_enroll' => 'Enroll extra user',
			'enroll_dish' => 'I did the dishes',
			'unenroll_dish' => 'Actually, I did not do the dishes',
			)
	)
	);
