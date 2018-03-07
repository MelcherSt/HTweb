<?php
return [
	'title' => 'Products',
	'name' => 'Product',
	'name_plural' => 'Products',
	'empty_list' => 'There are no products to show here',
	'field' => [
		'date' => 'Date',
		'paid_by' => 'Paid by',
		'name' => 'Name',
		'notes' => 'Description',
		'approved' => 'Approved',
		'cost' => 'Cost',
		'amount' => 'Amount',
		'participant' => 'Participant',
		'participant_plural' => 'Participants',
		'macro_last_executed' => 'Last executed',
	],
	'index' => [
		'btn' => [
			'add_product' => 'Add product',
		],
		'msg' => 'This list only shows unsettled products that you have bought or have been bought by others for you. For a list of settled product see',
		'tooltip_macro' => 'This product was automatically generated',
	],
	'view' => [
		'btn' => [
			'update_product' => 'Update product',
		],
	],
	'admin' => [
		'table_products' => 'All products',
		'table_macros' => 'Product macros',
		'index' => [
			'btn' => [
				'add_product' => 'Add product',
				'add_macro' => 'Add product macro',
				'execute_macros' => 'Execute all product macros'
			]
		],
		'create' => [
			'participants' => 'Select the people for which the product was bought. In-active users appear orange and should be selected manually.'
		],
		'create_macro' => [
			'title' => 'Macro',
			'msg' => 'Add a product macro. A product macro can be used to repeat recurring monthly expenses. Note that the cost of a macro are always charged to all active users.',
			'btn' => 'Create product macro',
		]
	],
	'create' => [
		'participants' => 'Select the people you bought this product for.',
		'btn' => 'Create product'	
	],
	'modal' => [
		'remove' => [
			'title' => 'Delete product',
			'msg' => 'Are you sure you want to remove the product',
			'btn' => 'Delete product',
		],
		'remove_macro' => [
			'title' => 'Delete product macro',
			'msg' => 'Are you sure you want to remove the product macro',
			'btn' => 'Delete product macro',
		],
	],
	'alert' => [
		'success' => [
			'create_product' => 'Product <strong>:name</strong> has been created.',
			'remove_product' => 'Product <strong>:name</strong> has been removed.',
			'update_product' => 'Product has been updated.',
			'create_macro' => 'Product macro <strong>:name</strong> has been created.',
			'remove_macro' => 'Product macro <strong>:name</strong> has been removed.',
			'macros_executed' => 'All macros were executed.',
		],
		'error' => [
			'not_found' => 'Cannot find product with id ":id".',
			'no_users_selected' => 'No users were selected. Select at least 1 users.',
			'macros_executed' => 'All macros were executed, but :num products were not created.',
		]
	],
];
