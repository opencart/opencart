<?php 
$_['opayo_setting'] = array(
	'general' => array(
		'environment' => 'live',
		'transaction_method' => 'PAYMENT',
		'card_save' => true,
		'debug' => false,
		'order_status_id' => 1
	),
	'cron' => array(
		'token' => '',
		'url' => '',
		'last_run' => ''
	),
	'environment' => array(
		'test' => array(
			'code' => 'test',
			'name' => 'text_test'
		),
		'live' => array(
			'code' => 'live',
			'name' => 'text_live'
		)
	),
	'transaction_method' => array(
		'PAYMENT' => array(
			'code' => 'PAYMENT',
			'name' => 'text_payment'
		),
		'DEFERRED' => array(
			'code' => 'DEFERRED',
			'name' => 'text_defered'
		),
		'AUTHENTICATE' => array(
			'code' => 'AUTHENTICATE',
			'name' => 'text_authenticate'
		)
	),
	'card_type' => array(
		'Visa' => array(
			'code' => 'VISA',
			'name' => 'Visa',
		),
		'MasterCard' => array(
			'code' => 'MC',
			'name' => 'MasterCard'			
		),
		'DELTA' => array(
			'code' => 'DELTA',
			'name' => 'Visa Delta/Debit'			
		),
		'SOLO' => array(
			'code' => 'SOLO',
			'name' => 'Solo'			
		),
		'MAESTRO' => array(
			'code' => 'MAESTRO',
			'name' => 'Maestro'			
		),
		'UKE' => array(
			'code' => 'UKE',
			'name' => 'Visa Electron UK Debit'			
		),
		'AMEX' => array(
			'code' => 'AMEX',
			'name' => 'American Express'			
		),
		'DC' => array(
			'code' => 'DC',
			'name' => 'Diners Club'			
		),
		'JCB' => array(
			'code' => 'JCB',
			'name' => 'Japan Credit Bureau'			
		)
	)
);
?>