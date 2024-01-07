<?php
$_['opayo_setting'] = [
	'general' => [
		'environment'        => 'live',
		'transaction_method' => 'PAYMENT',
		'card_save'          => true,
		'debug'              => false,
		'order_status_id'    => 1
	],
	'cron' => [
		'token'    => '',
		'url'      => '',
		'last_run' => ''
	],
	'environment' => [
		'test' => [
			'code' => 'test',
			'name' => 'text_test'
		],
		'live' => [
			'code' => 'live',
			'name' => 'text_live'
		]
	],
	'transaction_method' => [
		'PAYMENT' => [
			'code' => 'PAYMENT',
			'name' => 'text_payment'
		],
		'DEFERRED' => [
			'code' => 'DEFERRED',
			'name' => 'text_defered'
		],
		'AUTHENTICATE' => [
			'code' => 'AUTHENTICATE',
			'name' => 'text_authenticate'
		]
	],
	'card_type' => [
		'Visa' => [
			'code' => 'VISA',
			'name' => 'Visa',
		],
		'MasterCard' => [
			'code' => 'MC',
			'name' => 'MasterCard'
		],
		'DELTA' => [
			'code' => 'DELTA',
			'name' => 'Visa Delta/Debit'
		],
		'SOLO' => [
			'code' => 'SOLO',
			'name' => 'Solo'
		],
		'MAESTRO' => [
			'code' => 'MAESTRO',
			'name' => 'Maestro'
		],
		'UKE' => [
			'code' => 'UKE',
			'name' => 'Visa Electron UK Debit'
		],
		'AMEX' => [
			'code' => 'AMEX',
			'name' => 'American Express'
		],
		'DC' => [
			'code' => 'DC',
			'name' => 'Diners Club'
		],
		'JCB' => [
			'code' => 'JCB',
			'name' => 'Japan Credit Bureau'
		]
	]
];
