<?php
$_['paypal_setting'] = [
	'version' => '2.2.0',
	'partner' => [
		'production' => [
			'partner_id'             => 'TY2Q25KP2PX9L',
			'client_id'              => 'AbjxI4a9fMnew8UOMoDFVwSh7h1aeOBaXpd2wcccAnuqecijKIylRnNguGRWDrEPrTYraBQApf_-O3_4',
			'partner_attribution_id' => 'OPENCARTLIMITED_Cart_OpenCartPCP'
		],
		'sandbox' => [
			'partner_id'             => 'EJNHWRJJNB38L',
			'client_id'              => 'AfeIgIr-fIcEucsVXvdq21Ufu0wAALWhgJdVF4ItUK1IZFA9I4JIRdfyJ9vWrd9oi0B6mBGtJYDrlYsG',
			'partner_attribution_id' => 'OPENCARTLIMITED_Cart_OpenCartPCP'
		]
	],
	'general' => [
		'debug'                => false,
		'sale_analytics_range' => 'month',
		'checkout_mode'        => 'multi_button',
		'transaction_method'   => 'capture',
		'country_code'         => 'US',
		'currency_code'        => 'USD',
		'currency_value'       => '1',
		'card_currency_code'   => 'USD',
		'card_currency_value'  => '1',
		'webhook_token'        => '',
		'cron_token'           => ''
	],
	'button' => [
		'checkout' => [
			'page_code' => 'checkout',
			'page_name' => 'text_checkout',
			'status'    => true,
			'align'     => 'right',
			'size'      => 'large',
			'color'     => 'gold',
			'shape'     => 'rect',
			'label'     => 'paypal',
			'funding'   => [
				'paylater'    => 1,
				'card'        => 0,
				'bancontact'  => 0,
				'blik'        => 0,
				'eps'         => 0,
				'giropay'     => 0,
				'ideal'       => 0,
				'mercadopago' => 0,
				'mybank'      => 0,
				'p24'         => 0,
				'sepa'        => 0,
				'sofort'      => 0,
				'venmo'       => 0
			]
		],
		'cart' => [
			'page_code'   => 'cart',
			'page_name'   => 'text_cart',
			'status'      => true,
			'insert_tag'  => '#content',
			'insert_type' => 'append',
			'align'       => 'right',
			'size'        => 'large',
			'color'       => 'gold',
			'shape'       => 'rect',
			'label'       => 'paypal',
			'tagline'     => 'false'
		],
		'product' => [
			'page_code'   => 'product',
			'page_name'   => 'text_product',
			'status'      => true,
			'insert_tag'  => '#content #product #button-cart',
			'insert_type' => 'after',
			'align'       => 'center',
			'size'        => 'responsive',
			'color'       => 'gold',
			'shape'       => 'rect',
			'label'       => 'paypal',
			'tagline'     => 'false'
		]
	],
	'googlepay_button' => [
		'status' => false,
		'align'  => 'right',
		'size'   => 'large',
		'color'  => 'black',
		'shape'  => 'rect',
		'type'   => 'buy'
	],
	'applepay_button' => [
		'status' => false,
		'align'  => 'right',
		'size'   => 'large',
		'color'  => 'black',
		'shape'  => 'rect',
		'type'   => 'buy'
	],
	'card' => [
		'status'          => true,
		'align'           => 'right',
		'size'            => 'large',
		'secure_status'   => true,
		'secure_scenario' => [
			'failed_authentication'    => 0,
			'rejected_authentication'  => 0,
			'attempted_authentication' => 1,
			'unable_authentication'    => 0,
			'challenge_authentication' => 0,
			'card_ineligible'          => 1,
			'system_unavailable'       => 0,
			'system_bypassed'          => 1
		]
	],
	'message' => [
		'checkout' => [
			'page_code'  => 'checkout',
			'page_name'  => 'text_checkout',
			'status'     => true,
			'align'      => 'right',
			'size'       => 'large',
			'layout'     => 'text',
			'text_color' => 'black',
			'text_size'  => '12',
			'flex_color' => 'blue',
			'flex_ratio' => '8x1'
		],
		'cart' => [
			'page_code'   => 'cart',
			'page_name'   => 'text_cart',
			'status'      => true,
			'insert_tag'  => '#content',
			'insert_type' => 'append',
			'align'       => 'right',
			'size'        => 'large',
			'layout'      => 'text',
			'text_color'  => 'black',
			'text_size'   => '12',
			'flex_color'  => 'blue',
			'flex_ratio'  => '8x1'
		],
		'product' => [
			'page_code'   => 'product',
			'page_name'   => 'text_product',
			'status'      => true,
			'insert_tag'  => '#content #product',
			'insert_type' => 'before',
			'align'       => 'center',
			'size'        => 'responsive',
			'layout'      => 'text',
			'text_color'  => 'black',
			'text_size'   => '12',
			'flex_color'  => 'blue',
			'flex_ratio'  => '8x1'
		],
		'home' => [
			'page_code'   => 'home',
			'page_name'   => 'text_home',
			'status'      => true,
			'insert_tag'  => '#common-home',
			'insert_type' => 'prepend',
			'align'       => 'center',
			'size'        => 'responsive',
			'layout'      => 'text',
			'text_color'  => 'black',
			'text_size'   => '12',
			'flex_color'  => 'blue',
			'flex_ratio'  => '8x1'
		]
	],
	'order_status' => [
		'completed' => [
			'code' => 'completed',
			'name' => 'text_completed_status',
			'id'   => 5
		],
		'denied' => [
			'code' => 'denied',
			'name' => 'text_denied_status',
			'id'   => 8
		],
		'failed' => [
			'code' => 'failed',
			'name' => 'text_failed_status',
			'id'   => 10
		],
		'pending' => [
			'code' => 'pending',
			'name' => 'text_pending_status',
			'id'   => 1
		],
		'refunded' => [
			'code' => 'refunded',
			'name' => 'text_refunded_status',
			'id'   => 11
		],
		'reversed' => [
			'code' => 'reversed',
			'name' => 'text_reversed_status',
			'id'   => 12
		],
		'voided' => [
			'code' => 'voided',
			'name' => 'text_voided_status',
			'id'   => 16
		]
	],
	'contact' => [
		'oid'                           => '00D300000000LaY',
		'retURL'                        => 'https://www.opencart.com/',
		'Vendor_Partner_ID_VPID_MAM__c' => '0018000000LjXtY',
		'Campaign_ID__c'                => '7012E000001XNG7',
		'lead_source'                   => 'Partner',
		'recordType'                    => '0122E000000Qq4v',
		'company'                       => '',
		'first_name'                    => '',
		'last_name'                     => '',
		'email'                         => '',
		'url'                           => '',
		'phone'                         => '',
		'country'                       => '',
		'00N30000000gJEZ'               => '',
		'00N2E00000II4xQ'               => '',
		'00N2E00000II4xP'               => false,
		'00N2E00000II4xO'               => '',
		'00N80000004IGsC'               => ''
	],
	'sale_analytics_range' => [
		'day' => [
			'code' => 'day',
			'name' => 'text_day'
		],
		'week' => [
			'code' => 'week',
			'name' => 'text_week'
		],
		'month' => [
			'code' => 'month',
			'name' => 'text_month'
		],
		'year' => [
			'code' => 'year',
			'name' => 'text_year'
		]
	],
	'checkout_mode' => [
		'multi_button' => [
			'code' => 'multi_button',
			'name' => 'text_multi_button'
		],
		'one_button' => [
			'code' => 'one_button',
			'name' => 'text_one_button'
		]
	],
	'transaction_method' => [
		'authorize' => [
			'code' => 'authorize',
			'name' => 'text_authorization'
		],
		'capture' => [
			'code' => 'capture',
			'name' => 'text_sale'
		]
	],
	'paylater_country' => [
		'US' => [
			'code' => 'US'
		],
		'GB' => [
			'code' => 'GB'
		],
		'FR' => [
			'code' => 'FR'
		],
		'DE' => [
			'code' => 'DE'
		],
		'IT' => [
			'code' => 'IT'
		],
		'ES' => [
			'code' => 'ES'
		],
		'AU' => [
			'code' => 'AU'
		]
	],
	'currency' => [
		'AUD' => [
			'code'          => 'AUD',
			'name'          => 'text_currency_aud',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'BRL' => [
			'code'          => 'BRL',
			'name'          => 'text_currency_brl',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'CAD' => [
			'code'          => 'CAD',
			'name'          => 'text_currency_cad',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'CZK' => [
			'code'          => 'CZK',
			'name'          => 'text_currency_czk',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'DKK' => [
			'code'          => 'DKK',
			'name'          => 'text_currency_dkk',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'EUR' => [
			'code'          => 'EUR',
			'name'          => 'text_currency_eur',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'HKD' => [
			'code'          => 'HKD',
			'name'          => 'text_currency_hkd',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'HUF' => [
			'code'          => 'HUF',
			'name'          => 'text_currency_huf',
			'decimal_place' => 0,
			'status'        => true,
			'card_status'   => true
		],
		'INR' => [
			'code'          => 'INR',
			'name'          => 'text_currency_inr',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'ILS' => [
			'code'          => 'ILS',
			'name'          => 'text_currency_ils',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'JPY' => [
			'code'          => 'JPY',
			'name'          => 'text_currency_jpy',
			'decimal_place' => 0,
			'status'        => true,
			'card_status'   => true
		],
		'MYR' => [
			'code'          => 'MYR',
			'name'          => 'text_currency_myr',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'MXN' => [
			'code'          => 'MXN',
			'name'          => 'text_currency_mxn',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'TWD' => [
			'code'          => 'TWD',
			'name'          => 'text_currency_twd',
			'decimal_place' => 0,
			'status'        => true,
			'card_status'   => false
		],
		'NZD' => [
			'code'          => 'NZD',
			'name'          => 'text_currency_nzd',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'NOK' => [
			'code'          => 'NOK',
			'name'          => 'text_currency_nok',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'PHP' => [
			'code'          => 'PHP',
			'name'          => 'text_currency_php',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'PLN' => [
			'code'          => 'PLN',
			'name'          => 'text_currency_pln',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'GBP' => [
			'code'          => 'GBP',
			'name'          => 'text_currency_gbp',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'RUB' => [
			'code'          => 'RUB',
			'name'          => 'text_currency_rub',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'SGD' => [
			'code'          => 'SGD',
			'name'          => 'text_currency_sgd',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'SEK' => [
			'code'          => 'SEK',
			'name'          => 'text_currency_sek',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'CHF' => [
			'code'          => 'CHF',
			'name'          => 'text_currency_chf',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		],
		'THB' => [
			'code'          => 'THB',
			'name'          => 'text_currency_thb',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => false
		],
		'USD' => [
			'code'          => 'USD',
			'name'          => 'text_currency_usd',
			'decimal_place' => 2,
			'status'        => true,
			'card_status'   => true
		]
	],
	'button_insert_type' => [
		'into_begin' => [
			'code' => 'prepend',
			'name' => 'text_insert_prepend'
		],
		'into_end' => [
			'code' => 'append',
			'name' => 'text_insert_append'
		],
		'before' => [
			'code' => 'before',
			'name' => 'text_insert_before'
		],
		'after' => [
			'code' => 'after',
			'name' => 'text_insert_after'
		]
	],
	'button_align' => [
		'left' => [
			'code' => 'left',
			'name' => 'text_align_left'
		],
		'center' => [
			'code' => 'center',
			'name' => 'text_align_center'
		],
		'right' => [
			'code' => 'right',
			'name' => 'text_align_right'
		]
	],
	'button_size' => [
		'small' => [
			'code' => 'small',
			'name' => 'text_small'
		],
		'medium' => [
			'code' => 'medium',
			'name' => 'text_medium'
		],
		'large' => [
			'code' => 'large',
			'name' => 'text_large'
		],
		'responsive' => [
			'code' => 'responsive',
			'name' => 'text_responsive'
		]
	],
	'button_color' => [
		'gold' => [
			'code' => 'gold',
			'name' => 'text_gold'
		],
		'blue' => [
			'code' => 'blue',
			'name' => 'text_blue'
		],
		'silver' => [
			'code' => 'silver',
			'name' => 'text_silver'
		],
		'white' => [
			'code' => 'white',
			'name' => 'text_white'
		],
		'black' => [
			'code' => 'black',
			'name' => 'text_black'
		]
	],
	'button_shape' => [
		'pill' => [
			'code' => 'pill',
			'name' => 'text_pill'
		],
		'rect' => [
			'code' => 'rect',
			'name' => 'text_rect'
		]
	],
	'button_label' => [
		'checkout' => [
			'code' => 'checkout',
			'name' => 'text_checkout'
		],
		'pay' => [
			'code' => 'pay',
			'name' => 'text_pay'
		],
		'buynow' => [
			'code' => 'buynow',
			'name' => 'text_buy_now'
		],
		'paypal' => [
			'code' => 'paypal',
			'name' => 'text_pay_pal'
		],
		'installment' => [
			'code' => 'installment',
			'name' => 'text_installment'
		]
	],
	'button_tagline' => [
		'true' => [
			'code' => 'true',
			'name' => 'text_yes'
		],
		'false' => [
			'code' => 'false',
			'name' => 'text_no'
		],
	],
	'button_width' => [
		'small'      => '200px',
		'medium'     => '250px',
		'large'      => '350px',
		'responsive' => ''
	],
	'button_funding' => [
		'paylater' => [
			'code' => 'paylater',
			'name' => 'text_paylater'
		],
		'card' => [
			'code' => 'card',
			'name' => 'text_card'
		],
		'bancontact' => [
			'code' => 'bancontact',
			'name' => 'text_bancontact'
		],
		'bancontact' => [
			'code' => 'bancontact',
			'name' => 'text_bancontact'
		],
		'blik' => [
			'code' => 'blik',
			'name' => 'text_blik'
		],
		'eps' => [
			'code' => 'eps',
			'name' => 'text_eps'
		],
		'giropay' => [
			'code' => 'giropay',
			'name' => 'text_giropay'
		],
		'ideal' => [
			'code' => 'ideal',
			'name' => 'text_ideal'
		],
		'mercadopago' => [
			'code' => 'mercadopago',
			'name' => 'text_mercadopago'
		],
		'mybank' => [
			'code' => 'mybank',
			'name' => 'text_mybank'
		],
		'p24' => [
			'code' => 'p24',
			'name' => 'text_p24'
		],
		'sepa' => [
			'code' => 'sepa',
			'name' => 'text_sepa'
		],
		'sofort' => [
			'code' => 'sofort',
			'name' => 'text_sofort'
		],
		'venmo' => [
			'code' => 'venmo',
			'name' => 'text_venmo'
		]
	],
	'googlepay_button_align' => [
		'left' => [
			'code' => 'left',
			'name' => 'text_align_left'
		],
		'center' => [
			'code' => 'center',
			'name' => 'text_align_center'
		],
		'right' => [
			'code' => 'right',
			'name' => 'text_align_right'
		]
	],
	'googlepay_button_size' => [
		'small' => [
			'code' => 'small',
			'name' => 'text_small'
		],
		'medium' => [
			'code' => 'medium',
			'name' => 'text_medium'
		],
		'large' => [
			'code' => 'large',
			'name' => 'text_large'
		],
		'responsive' => [
			'code' => 'responsive',
			'name' => 'text_responsive'
		]
	],
	'googlepay_button_color' => [
		'black' => [
			'code' => 'black',
			'name' => 'text_black'
		],
		'white' => [
			'code' => 'white',
			'name' => 'text_white'
		]
	],
	'googlepay_button_shape' => [
		'pill' => [
			'code' => 'pill',
			'name' => 'text_pill'
		],
		'rect' => [
			'code' => 'rect',
			'name' => 'text_rect'
		]
	],
	'googlepay_button_type' => [
		'buy' => [
			'code' => 'buy',
			'name' => 'text_buy'
		],
		'donate' => [
			'code' => 'donate',
			'name' => 'text_donate'
		],
		'plain' => [
			'code' => 'plain',
			'name' => 'text_plain'
		],
		'pay' => [
			'code' => 'pay',
			'name' => 'text_pay'
		],
		'checkout' => [
			'code' => 'checkout',
			'name' => 'text_checkout'
		]
	],
	'googlepay_button_width' => [
		'small'      => '200px',
		'medium'     => '250px',
		'large'      => '350px',
		'responsive' => ''
	],
	'applepay_button_align' => [
		'left' => [
			'code' => 'left',
			'name' => 'text_align_left'
		],
		'center' => [
			'code' => 'center',
			'name' => 'text_align_center'
		],
		'right' => [
			'code' => 'right',
			'name' => 'text_align_right'
		]
	],
	'applepay_button_size' => [
		'small' => [
			'code' => 'small',
			'name' => 'text_small'
		],
		'medium' => [
			'code' => 'medium',
			'name' => 'text_medium'
		],
		'large' => [
			'code' => 'large',
			'name' => 'text_large'
		],
		'responsive' => [
			'code' => 'responsive',
			'name' => 'text_responsive'
		]
	],
	'applepay_button_color' => [
		'black' => [
			'code' => 'black',
			'name' => 'text_black'
		],
		'white' => [
			'code' => 'white',
			'name' => 'text_white'
		],
		'white_outline' => [
			'code' => 'white-outline',
			'name' => 'text_white_outline'
		]
	],
	'applepay_button_shape' => [
		'pill' => [
			'code' => 'pill',
			'name' => 'text_pill'
		],
		'rect' => [
			'code' => 'rect',
			'name' => 'text_rect'
		]
	],
	'applepay_button_type' => [
		'buy' => [
			'code' => 'buy',
			'name' => 'text_buy'
		],
		'donate' => [
			'code' => 'donate',
			'name' => 'text_donate'
		],
		'plain' => [
			'code' => 'plain',
			'name' => 'text_plain'
		],
		'check-out' => [
			'code' => 'check-out',
			'name' => 'text_check_out'
		]
	],
	'applepay_button_width' => [
		'small'      => '200px',
		'medium'     => '250px',
		'large'      => '350px',
		'responsive' => ''
	],
	'card_align' => [
		'left' => [
			'code' => 'left',
			'name' => 'text_align_left'
		],
		'center' => [
			'code' => 'center',
			'name' => 'text_align_center'
		],
		'right' => [
			'code' => 'right',
			'name' => 'text_align_right'
		]
	],
	'card_size' => [
		'medium' => [
			'code' => 'medium',
			'name' => 'text_medium'
		],
		'large' => [
			'code' => 'large',
			'name' => 'text_large'
		],
		'responsive' => [
			'code' => 'responsive',
			'name' => 'text_responsive'
		]
	],
	'card_width' => [
		'medium'     => '250px',
		'large'      => '350px',
		'responsive' => ''
	],
	'card_secure_scenario' => [
		'failed_authentication' => [
			'code'        => 'failed_authentication',
			'name'        => 'text_3ds_failed_authentication',
			'error'       => 'error_3ds_failed_authentication',
			'recommended' => 0
		],
		'rejected_authentication' => [
			'code'        => 'rejected_authentication',
			'name'        => 'text_3ds_rejected_authentication',
			'error'       => 'error_3ds_rejected_authentication',
			'recommended' => 0
		],
		'attempted_authentication' => [
			'code'        => 'attempted_authentication',
			'name'        => 'text_3ds_attempted_authentication',
			'error'       => 'error_3ds_attempted_authentication',
			'recommended' => 1
		],
		'unable_authentication' => [
			'code'        => 'unable_authentication',
			'name'        => 'text_3ds_unable_authentication',
			'error'       => 'error_3ds_unable_authentication',
			'recommended' => 0
		],
		'challenge_authentication' => [
			'code'        => 'challenge_authentication',
			'name'        => 'text_3ds_challenge_authentication',
			'error'       => 'error_3ds_challenge_authentication',
			'recommended' => 0
		],
		'card_ineligible' => [
			'code'        => 'card_ineligible',
			'name'        => 'text_3ds_card_ineligible',
			'error'       => 'error_3ds_card_ineligible',
			'recommended' => 1
		],
		'system_unavailable' => [
			'code'        => 'system_unavailable',
			'name'        => 'text_3ds_system_unavailable',
			'error'       => 'error_3ds_system_unavailable',
			'recommended' => 0
		],
		'system_bypassed' => [
			'code'        => 'system_bypassed',
			'name'        => 'text_3ds_system_bypassed',
			'error'       => 'error_3ds_system_bypassed',
			'recommended' => 1
		]
	],
	'message_insert_type' => [
		'into_begin' => [
			'code' => 'prepend',
			'name' => 'text_insert_prepend'
		],
		'into_end' => [
			'code' => 'append',
			'name' => 'text_insert_append'
		],
		'before' => [
			'code' => 'before',
			'name' => 'text_insert_before'
		],
		'after' => [
			'code' => 'after',
			'name' => 'text_insert_after'
		]
	],
	'message_align' => [
		'left' => [
			'code' => 'left',
			'name' => 'text_align_left'
		],
		'center' => [
			'code' => 'center',
			'name' => 'text_align_center'
		],
		'right' => [
			'code' => 'right',
			'name' => 'text_align_right'
		]
	],
	'message_size' => [
		'small' => [
			'code' => 'small',
			'name' => 'text_small'
		],
		'medium' => [
			'code' => 'medium',
			'name' => 'text_medium'
		],
		'large' => [
			'code' => 'large',
			'name' => 'text_large'
		],
		'responsive' => [
			'code' => 'responsive',
			'name' => 'text_responsive'
		]
	],
	'message_width' => [
		'small'      => '200px',
		'medium'     => '250px',
		'large'      => '350px',
		'responsive' => ''
	],
	'message_layout' => [
		'text' => [
			'code' => 'text',
			'name' => 'text_text'
		],
		'flex' => [
			'code' => 'flex',
			'name' => 'text_flex'
		]
	],
	'message_text_color' => [
		'black' => [
			'code' => 'black',
			'name' => 'text_black'
		],
		'white' => [
			'code' => 'white',
			'name' => 'text_white'
		]
	],
	'message_text_size'  => ['10', '11', '12', '13', '14', '15', '16'],
	'message_flex_color' => [
		'blue' => [
			'code' => 'blue',
			'name' => 'text_blue'
		],
		'black' => [
			'code' => 'black',
			'name' => 'text_black'
		],
		'white' => [
			'code' => 'white',
			'name' => 'text_white'
		]
	],
	'message_flex_ratio' => ['1x1', '1x4', '8x1', '20x1'],
	'contact_sales'      => ['100k - 250k', '250k - 2m', '2m - 10m', '10m - 20m', '20m - 50m', '50m +'],
	'contact_product'    => [
		[
			'code' => 'BT DCC',
			'name' => 'text_bt_dcc'
		],
		[
			'code' => 'Express Checkout (EC]',
			'name' => 'text_express_checkout'
		],
		[
			'code' => 'Credit - Installments',
			'name' => 'text_credit_installments'
		],
		[
			'code' => 'Point of Sale',
			'name' => 'text_point_of_sale'
		],
		[
			'code' => 'Invoicing API',
			'name' => 'text_invoicing_api'
		],
		[
			'code' => 'PayPal Working Capital',
			'name' => 'text_paypal_working_capital'
		],
		[
			'code' => 'Risk servicing',
			'name' => 'text_risk_servicing'
		],
		[
			'code' => 'PayPal Here',
			'name' => 'text_paypal_here'
		],
		[
			'code' => 'Payouts',
			'name' => 'text_payouts'
		],
		[
			'code' => 'Marketing solutions',
			'name' => 'text_marketing_solutions'
		],
	]
];
