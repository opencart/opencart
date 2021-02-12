<?php 
$_['paypal_setting'] = array(
	'partner' => array(
		'production' => array(
			'partner_id' => 'TY2Q25KP2PX9L',
			'client_id' => 'AbjxI4a9fMnew8UOMoDFVwSh7h1aeOBaXpd2wcccAnuqecijKIylRnNguGRWDrEPrTYraBQApf_-O3_4'
		),
		'sandbox' => array(
			'partner_id' => 'EJNHWRJJNB38L',
			'client_id' => 'AfeIgIr-fIcEucsVXvdq21Ufu0wAALWhgJdVF4ItUK1IZFA9I4JIRdfyJ9vWrd9oi0B6mBGtJYDrlYsG'
		)
	),
	'order_status' => array(
		'completed' => array(
			'code' => 'completed',
			'name' => 'text_completed_status',
			'id' => 5
		),
		'denied' => array(
			'code' => 'denied',
			'name' => 'text_denied_status',
			'id' => 8
		),
		'failed' => array(
			'code' => 'failed',
			'name' => 'text_failed_status',
			'id' => 10
		),
		'pending' => array(
			'code' => 'pending',
			'name' => 'text_pending_status',
			'id' => 1
		),
		'refunded' => array(
			'code' => 'refunded',
			'name' => 'text_refunded_status',
			'id' => 11
		),
		'reversed' => array(
			'code' => 'reversed',
			'name' => 'text_reversed_status',
			'id' => 12
		),
		'voided' => array(
			'code' => 'voided',
			'name' => 'text_voided_status',
			'id' => 16
		)
	),
	'checkout' => array(
		'express' => array(
			'status' => true,
			'button_align' => 'right',
			'button_size' => 'large',
			'button_color' => 'gold',
			'button_shape' => 'rect',
			'button_label' => 'paypal'
		),
		'card' => array(
			'status' => false,
			'form_align' => 'right',
			'form_size' => 'large',
			'secure_status' => true,
			'secure_scenario' => array(
				'undefined' => 1,
				'error' => 0,
				'skipped_by_buyer' => 0,
				'failure' => 0,
				'bypassed' => 0,
				'attempted' => 1,
				'unavailable' => 0,
				'card_ineligible' => 1
			)
		),
		'message' => array(
			'status' => true,
			'message_align' => 'right',
			'message_size' => 'large',
			'message_layout' => 'text',
			'message_text_color' => 'black',
			'message_text_size' => '12',
			'message_flex_color' => 'blue',
			'message_flex_ratio' => '8x1'
		)
	),
	'currency' => array(
		'AUD' => array(
			'code' => 'AUD',
			'name' => 'text_currency_aud',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'BRL' => array(
			'code' => 'BRL',
			'name' => 'text_currency_brl',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'CAD' => array(
			'code' => 'CAD',
			'name' => 'text_currency_cad',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'CZK' => array(
			'code' => 'CZK',
			'name' => 'text_currency_czk',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'DKK' => array(
			'code' => 'DKK',
			'name' => 'text_currency_dkk',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'EUR' => array(
			'code' => 'EUR',
			'name' => 'text_currency_eur',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'HKD' => array(
			'code' => 'HKD',
			'name' => 'text_currency_hkd',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'HUF' => array(
			'code' => 'HUF',
			'name' => 'text_currency_huf',
			'decimal_place' => 0,
			'express_status' => true,
			'card_status' => true
		),
		'INR' => array(
			'code' => 'INR',
			'name' => 'text_currency_inr',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'ILS' => array(
			'code' => 'ILS',
			'name' => 'text_currency_ils',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'JPY' => array(
			'code' => 'JPY',
			'name' => 'text_currency_jpy',
			'decimal_place' => 0,
			'express_status' => true,
			'card_status' => true
		),
		'MYR' => array(
			'code' => 'MYR',
			'name' => 'text_currency_myr',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'MXN' => array(
			'code' => 'MXN',
			'name' => 'text_currency_mxn',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'TWD' => array(
			'code' => 'TWD',
			'name' => 'text_currency_twd',
			'decimal_place' => 0,
			'express_status' => true,
			'card_status' => false
		),
		'NZD' => array(
			'code' => 'NZD',
			'name' => 'text_currency_nzd',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'NOK' => array(
			'code' => 'NOK',
			'name' => 'text_currency_nok',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'PHP' => array(
			'code' => 'PHP',
			'name' => 'text_currency_php',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'PLN' => array(
			'code' => 'PLN',
			'name' => 'text_currency_pln',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'GBP' => array(
			'code' => 'GBP',
			'name' => 'text_currency_gbp',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'RUB' => array(
			'code' => 'RUB',
			'name' => 'text_currency_rub',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'SGD' => array(
			'code' => 'SGD',
			'name' => 'text_currency_sgd',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'SEK' => array(
			'code' => 'SEK',
			'name' => 'text_currency_sek',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'CHF' => array(
			'code' => 'CHF',
			'name' => 'text_currency_chf',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		),
		'THB' => array(
			'code' => 'THB',
			'name' => 'text_currency_thb',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => false
		),
		'USD' => array(
			'code' => 'USD',
			'name' => 'text_currency_usd',
			'decimal_place' => 2,
			'express_status' => true,
			'card_status' => true
		)
	),
	'button_align' => array(
		'left' => array(
			'code' => 'left',
			'name' => 'text_align_left'
		),
		'center' => array(
			'code' => 'center',
			'name' => 'text_align_center'
		),
		'right' => array(
			'code' => 'right',
			'name' => 'text_align_right'
		)
	),
	'button_size' => array(
		'small' => array(
			'code' => 'small',
			'name' => 'text_small'
		),
		'medium' => array(
			'code' => 'medium',
			'name' => 'text_medium'
		),
		'large' => array(
			'code' => 'large',
			'name' => 'text_large'
		),
		'responsive' => array(
			'code' => 'responsive',
			'name' => 'text_responsive'
		)
	),
	'button_color' => array(
		'gold' => array(
			'code' => 'gold',
			'name' => 'text_gold'
		),
		'blue' => array(
			'code' => 'blue',
			'name' => 'text_blue'
		),
		'silver' => array(
			'code' => 'silver',
			'name' => 'text_silver'
		),
		'white' => array(
			'code' => 'white',
			'name' => 'text_white'
		),
		'black' => array(
			'code' => 'black',
			'name' => 'text_black'
		)
	),
	'button_shape' => array(
		'pill' => array(
			'code' => 'pill',
			'name' => 'text_pill'
		),
		'rect' => array(
			'code' => 'rect',
			'name' => 'text_rect'
		)
	),
	'button_label' => array(
		'checkout' => array(
			'code' => 'checkout',
			'name' => 'text_checkout'
		),
		'pay' => array(
			'code' => 'pay',
			'name' => 'text_pay'
		),
		'buynow' => array(
			'code' => 'buynow',
			'name' => 'text_buy_now'
		),
		'paypal' => array(
			'code' => 'paypal',
			'name' => 'text_pay_pal'
		),
		'installment' => array(
			'code' => 'installment',
			'name' => 'text_installment'
		)
	),
	'button_width' => array(
		'small' => '200px',
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'form_align' => array(
		'left' => array(
			'code' => 'left',
			'name' => 'text_align_left'
		),
		'center' => array(
			'code' => 'center',
			'name' => 'text_align_center'
		),
		'right' => array(
			'code' => 'right',
			'name' => 'text_align_right'
		)
	),
	'form_size' => array(
		'medium' => array(
			'code' => 'medium',
			'name' => 'text_medium'
		),
		'large' => array(
			'code' => 'large',
			'name' => 'text_large'
		),
		'responsive' => array(
			'code' => 'responsive',
			'name' => 'text_responsive'
		)
	),
	'form_width' => array(
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'secure_scenario' => array(
		'undefined' => array(
			'code' => 'undefined',
			'name' => 'text_3ds_undefined',
			'error' => 'error_3ds_undefined',
			'recommended' => 1
		),
		'error' => array(
			'code' => 'error',
			'name' => 'text_3ds_error',
			'error' => 'error_3ds_undefined',
			'recommended' => 0
		),
		'skipped_by_buyer' => array(
			'code' => 'skipped_by_buyer',
			'name' => 'text_3ds_skipped_by_buyer',
			'error' => 'error_3ds_skipped_by_buyer',
			'recommended' => 0
		),
		'failure' => array(
			'code' => 'failure',
			'name' => 'text_3ds_failure',
			'error' => 'error_3ds_failure',
			'recommended' => 0
		),
		'bypassed' => array(
			'code' => 'bypassed',
			'name' => 'text_3ds_bypassed',
			'error' => 'error_3ds_bypassed',
			'recommended' => 0
		),
		'attempted' => array(
			'code' => 'attempted',
			'name' => 'text_3ds_attempted',
			'error' => 'error_3ds_attempted',
			'recommended' => 1
		),
		'unavailable' => array(
			'code' => 'unavailable',
			'name' => 'text_3ds_unavailable',
			'error' => 'error_3ds_unavailable',
			'recommended' => 0
		),
		'card_ineligible' => array(
			'code' => 'card_ineligible',
			'name' => 'text_3ds_card_ineligible',
			'error' => 'error_3ds_card_ineligible',
			'recommended' => 1
		)
	),
	'message_align' => array(
		'left' => array(
			'code' => 'left',
			'name' => 'text_align_left'
		),
		'center' => array(
			'code' => 'center',
			'name' => 'text_align_center'
		),
		'right' => array(
			'code' => 'right',
			'name' => 'text_align_right'
		)
	),
	'message_size' => array(
		'small' => array(
			'code' => 'small',
			'name' => 'text_small'
		),
		'medium' => array(
			'code' => 'medium',
			'name' => 'text_medium'
		),
		'large' => array(
			'code' => 'large',
			'name' => 'text_large'
		),
		'responsive' => array(
			'code' => 'responsive',
			'name' => 'text_responsive'
		)
	),
	'message_width' => array(
		'small' => '200px',
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'message_layout' => array(
		'text' => array(
			'code' => 'text',
			'name' => 'text_text'
		),
		'flex' => array(
			'code' => 'flex',
			'name' => 'text_flex'
		)
	),
	'message_text_color' => array(
		'black' => array(
			'code' => 'black',
			'name' => 'text_black'
		),
		'white' => array(
			'code' => 'white',
			'name' => 'text_white'
		)
	),
	'message_text_size' => array('10', '11', '12', '13', '14', '15', '16'),
	'message_flex_color' => array(
		'blue' => array(
			'code' => 'blue',
			'name' => 'text_blue'
		),
		'black' => array(
			'code' => 'black',
			'name' => 'text_black'
		),
		'white' => array(
			'code' => 'white',
			'name' => 'text_white'
		)
	),
	'message_flex_ratio' => array('1x1', '1x4', '8x1', '20x1')
);
?>