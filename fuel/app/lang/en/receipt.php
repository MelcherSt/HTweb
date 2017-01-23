<?php

return [
	'title' => 'My Receipts',
	'title_admin' => 'Receipts',
	'empty_list' => '<em>There are no receipts to show here</em>',
	'field' => [
		'notes' => 'Notes',
		'date' => 'Creation date',
		'balance' => 'Balance',
	],
	'index' => [
		'msg' => 'Here you find all your receipts',
	],
	'view' => [
		'point_check' => 'Point checksum',
		'balance_check' => 'Balance checksum',
		'title' => 'Cost and points distribution',
		'msg' => 'A receipt shows the distribution of cost and points over an interval. '
		. 'This means that only the points gained or lost in the given interval will be shown in the chart and table below.',
		'detail' => 'Detailed overview',
		'trans_schema' => [
			'title' => 'Transaction schema',
			'from' => 'From',
			'to' => 'To',
			'amount' => 'Amount',
		]
	],
	'alert' => [
		'error' => [
			'no_receipt' => 'Cannot find receipt with id <strong>:id</strong>.',
		]
	]
];