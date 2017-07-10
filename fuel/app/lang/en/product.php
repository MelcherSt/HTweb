<?php
return [
	'title' => 'Products',
	'title_admin' => 'Products',
	'name' => 'Product',
	'name_plural' => 'Products',
	'empty_list' => 'There are no products to show here',
	'field' => [
		'date' => 'Date added',
		'paid_by' => 'Paid by',
		'name' => 'Product name',
		'notes' => 'Description',
		'approved' => 'Approved',
		'cost' => 'Cost',
		'amount' => 'Amount',
		'participant' => 'Participant',
		'participant_plural' => 'Participants',
	],
	'index' => [
		'btn' => [
			'add_product' => 'Add product',
		],
		'msg' => 'This list only shows unsettled products that you have bought or have been bought by others for you. For a list of settled product see',
		'paid_by_me' => 'Products I bought',
		'paid_for_me' => 'Products others bought for me',
	],
	'view' => [
		'btn' => [
			'update_product' => 'Update product',
		],
	],
	'modal' => [
		'create' => [
			'title' => 'Create new product',
			'msg' => 'Add a product that you bought for someone else.',
			'participants' => 'Select the people you bought the product for',
			'btn' => 'Create product'	
		],
		'remove' => [
			'title' => 'Delete product',
			'msg' => 'Are you sure you want to remove the product',
			'btn' => 'Delete product',
		]
	],
	'alert' => [
		'success' => [
			'create_product' => 'Product <strong>:name</strong> has been created.',
			'remove_product' => 'Product <strong>:name</strong> has been removed.',
			'update_product' => 'Product has been updated.',
		],
		'error' => [
			'no_product' => 'Cannot find product with id <strong>:id</strong>.',
		]
	]
];
