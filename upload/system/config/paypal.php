<?php 
$_['paypal_setting'] = array(
	'version' => '2.0.2',
	'partner' => array(
		'production' => array(
			'partner_id' => 'TY2Q25KP2PX9L',
			'client_id' => 'AbjxI4a9fMnew8UOMoDFVwSh7h1aeOBaXpd2wcccAnuqecijKIylRnNguGRWDrEPrTYraBQApf_-O3_4',
			'partner_attribution_id' => 'OPENCARTLIMITED_Cart_OpenCartPCP'
		),
		'sandbox' => array(
			'partner_id' => 'EJNHWRJJNB38L',
			'client_id' => 'AfeIgIr-fIcEucsVXvdq21Ufu0wAALWhgJdVF4ItUK1IZFA9I4JIRdfyJ9vWrd9oi0B6mBGtJYDrlYsG',
			'partner_attribution_id' => 'OPENCARTLIMITED_Cart_OpenCartPCP'
		)
	),
	'general' => array(
		'debug' => false,
		'sale_analytics_range' => 'month',
		'checkout_mode' => 'multi_button',
		'transaction_method' => 'capture',
		'country_code' => 'US',
		'currency_code' => 'USD',
		'currency_value' => '1',
		'card_currency_code' => 'USD',
		'card_currency_value' => '1'
	),
	'button' => array(
		'checkout' => array(
			'page_code' => 'checkout',
			'page_name' => 'text_checkout',
			'status' => true,
			'align' => 'right',
			'size' => 'large',
			'color' => 'gold',
			'shape' => 'rect',
			'label' => 'paypal',
			'funding' => array(
				'paylater' => 1,
				'card' => 0,
				'bancontact' => 0,
				'blik' => 0,
				'eps' => 0,
				'giropay' => 0,
				'ideal' => 0,
				'mercadopago' => 0,
				'mybank' => 0,
				'p24' => 0,
				'sepa' => 0,
				'sofort' => 0,
				'venmo' => 0
			)
		),
		'product' => array(
			'page_code' => 'product',
			'page_name' => 'text_product',
			'status' => true,
			'insert_tag' => '#content #product #button-cart',
			'insert_type' => 'after',
			'align' => 'center',
			'size' => 'responsive',
			'color' => 'gold',
			'shape' => 'rect',
			'label' => 'paypal',
			'tagline' => 'false',
			'funding' => array(
				'paylater' => 0,
				'card' => 0,
				'credit' => 0,
				'bancontact' => 0,
				'blik' => 0,
				'eps' => 0,
				'giropay' => 0,
				'ideal' => 0,
				'mercadopago' => 0,
				'mybank' => 0,
				'p24' => 0,
				'sepa' => 0,
				'sofort' => 0,
				'venmo' => 0
			)
		),
		'cart' => array(
			'page_code' => 'cart',
			'page_name' => 'text_cart',
			'status' => true,
			'insert_tag' => '#content',
			'insert_type' => 'append',
			'align' => 'right',
			'size' => 'large',
			'color' => 'gold',
			'shape' => 'rect',
			'label' => 'paypal',
			'tagline' => 'false',
			'funding' => array(
				'paylater' => 0,
				'card' => 0,
				'bancontact' => 0,
				'blik' => 0,
				'eps' => 0,
				'giropay' => 0,
				'ideal' => 0,
				'mercadopago' => 0,
				'mybank' => 0,
				'p24' => 0,
				'sepa' => 0,
				'sofort' => 0,
				'venmo' => 0
			),
		)
	),
	'applepay_button' => array(
		'status' => true,
		'align' => 'right',
		'size' => 'large',
		'color' => 'black',
		'shape' => 'rect',
		'type' => 'buy'
	),
	'card' => array(
		'status' => true,
		'align' => 'right',
		'size' => 'large',
		'secure_status' => true,
		'secure_scenario' => array(
			'failed_authentication' => 0,
			'rejected_authentication' => 0,
			'attempted_authentication' => 1,
			'unable_authentication' => 0,
			'challenge_authentication' => 0,
			'card_ineligible' => 1,
			'system_unavailable' => 0,
			'system_bypassed' => 1
		)
	),
	'message' => array(
		'checkout' => array(
			'page_code' => 'checkout',
			'page_name' => 'text_checkout',
			'status' => true,
			'align' => 'right',
			'size' => 'large',
			'layout' => 'text',
			'text_color' => 'black',
			'text_size' => '12',
			'flex_color' => 'blue',
			'flex_ratio' => '8x1'
		),
		'home' => array(
			'page_code' => 'home',
			'page_name' => 'text_home',
			'status' => true,
			'insert_tag' => '#common-home',
			'insert_type' => 'prepend',
			'align' => 'center',
			'size' => 'responsive',
			'layout' => 'text',
			'text_color' => 'black',
			'text_size' => '12',
			'flex_color' => 'blue',
			'flex_ratio' => '8x1'
		),
		'product' => array(
			'page_code' => 'product',
			'page_name' => 'text_product',
			'status' => true,
			'insert_tag' => '#content #product',
			'insert_type' => 'before',
			'align' => 'center',
			'size' => 'responsive',
			'layout' => 'text',
			'text_color' => 'black',
			'text_size' => '12',
			'flex_color' => 'blue',
			'flex_ratio' => '8x1'
		),
		'cart' => array(
			'page_code' => 'cart',
			'page_name' => 'text_cart',
			'status' => true,
			'insert_tag' => '#content',
			'insert_type' => 'append',
			'align' => 'right',
			'size' => 'large',
			'layout' => 'text',
			'text_color' => 'black',
			'text_size' => '12',
			'flex_color' => 'blue',
			'flex_ratio' => '8x1'
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
	'contact' => array(
		'oid' => '00D300000000LaY',
		'retURL' => 'https://www.opencart.com/',
		'Vendor_Partner_ID_VPID_MAM__c' => '0018000000LjXtY',
		'Campaign_ID__c' => '7012E000001XNG7',
		'lead_source' => 'Partner',
		'recordType' => '0122E000000Qq4v',
		'company' => '',
		'first_name' => '',
		'last_name' => '',
		'email' => '',
		'url' => '',
		'phone' => '',
		'country' => '',
		'00N30000000gJEZ' => '',
		'00N2E00000II4xQ' => '',
		'00N2E00000II4xP' => false,
		'00N2E00000II4xO' => '',
		'00N80000004IGsC' => ''
	),
	'sale_analytics_range' => array(
		'day' => array(
			'code' => 'day',
			'name' => 'text_day'
		),
		'week' => array(
			'code' => 'week',
			'name' => 'text_week'
		),
		'month' => array(
			'code' => 'month',
			'name' => 'text_month'
		),
		'year' => array(
			'code' => 'year',
			'name' => 'text_year'
		)
	),
	'checkout_mode' => array(
		'multi_button' => array(
			'code' => 'multi_button',
			'name' => 'text_multi_button'
		),
		'one_button' => array(
			'code' => 'one_button',
			'name' => 'text_one_button'
		)
	),
	'transaction_method' => array(
		'authorize' => array(
			'code' => 'authorize',
			'name' => 'text_authorization'
		),
		'capture' => array(
			'code' => 'capture',
			'name' => 'text_sale'
		)
	),
	'paylater_country' => array(
		'US' => array(
			'code' => 'US'
		),
		'GB' => array(
			'code' => 'GB'
		),
		'FR' => array(
			'code' => 'FR'
		),
		'DE' => array(
			'code' => 'DE'
		),
		'IT' => array(
			'code' => 'IT'
		),
		'ES' => array(
			'code' => 'ES'
		),
		'AU' => array(
			'code' => 'AU'
		)
	),
	'currency' => array(
		'AUD' => array(
			'code' => 'AUD',
			'name' => 'text_currency_aud',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'BRL' => array(
			'code' => 'BRL',
			'name' => 'text_currency_brl',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'CAD' => array(
			'code' => 'CAD',
			'name' => 'text_currency_cad',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'CZK' => array(
			'code' => 'CZK',
			'name' => 'text_currency_czk',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'DKK' => array(
			'code' => 'DKK',
			'name' => 'text_currency_dkk',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'EUR' => array(
			'code' => 'EUR',
			'name' => 'text_currency_eur',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'HKD' => array(
			'code' => 'HKD',
			'name' => 'text_currency_hkd',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'HUF' => array(
			'code' => 'HUF',
			'name' => 'text_currency_huf',
			'decimal_place' => 0,
			'status' => true,
			'card_status' => true
		),
		'INR' => array(
			'code' => 'INR',
			'name' => 'text_currency_inr',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'ILS' => array(
			'code' => 'ILS',
			'name' => 'text_currency_ils',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'JPY' => array(
			'code' => 'JPY',
			'name' => 'text_currency_jpy',
			'decimal_place' => 0,
			'status' => true,
			'card_status' => true
		),
		'MYR' => array(
			'code' => 'MYR',
			'name' => 'text_currency_myr',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'MXN' => array(
			'code' => 'MXN',
			'name' => 'text_currency_mxn',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'TWD' => array(
			'code' => 'TWD',
			'name' => 'text_currency_twd',
			'decimal_place' => 0,
			'status' => true,
			'card_status' => false
		),
		'NZD' => array(
			'code' => 'NZD',
			'name' => 'text_currency_nzd',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'NOK' => array(
			'code' => 'NOK',
			'name' => 'text_currency_nok',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'PHP' => array(
			'code' => 'PHP',
			'name' => 'text_currency_php',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'PLN' => array(
			'code' => 'PLN',
			'name' => 'text_currency_pln',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'GBP' => array(
			'code' => 'GBP',
			'name' => 'text_currency_gbp',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'RUB' => array(
			'code' => 'RUB',
			'name' => 'text_currency_rub',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'SGD' => array(
			'code' => 'SGD',
			'name' => 'text_currency_sgd',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'SEK' => array(
			'code' => 'SEK',
			'name' => 'text_currency_sek',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'CHF' => array(
			'code' => 'CHF',
			'name' => 'text_currency_chf',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		),
		'THB' => array(
			'code' => 'THB',
			'name' => 'text_currency_thb',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => false
		),
		'USD' => array(
			'code' => 'USD',
			'name' => 'text_currency_usd',
			'decimal_place' => 2,
			'status' => true,
			'card_status' => true
		)
	),
	'button_insert_type' => array(
		'into_begin' => array(
			'code'	=> 'prepend',
			'name'	=> 'text_insert_prepend'
		),
		'into_end' => array(
			'code'	=> 'append',
			'name'	=> 'text_insert_append'
		),
		'before' => array(
			'code'	=> 'before',
			'name'	=> 'text_insert_before'
		),
		'after' => array(
			'code'	=> 'after',
			'name'	=> 'text_insert_after'
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
	'button_tagline' => array(
		'true' => array(
			'code' => 'true',
			'name' => 'text_yes'
		),
		'false' => array(
			'code' => 'false',
			'name' => 'text_no'
		),
	),
	'button_width' => array(
		'small' => '200px',
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'button_funding' => array(
		'paylater' => array(
			'code' => 'paylater',
			'name' => 'text_paylater'
		),
		'card' => array(
			'code' => 'card',
			'name' => 'text_card'
		),
		'bancontact' => array(
			'code' => 'bancontact',
			'name' => 'text_bancontact'
		),
		'bancontact' => array(
			'code' => 'bancontact',
			'name' => 'text_bancontact'
		),
		'blik' => array(
			'code' => 'blik',
			'name' => 'text_blik'
		),
		'eps' => array(
			'code' => 'eps',
			'name' => 'text_eps'
		),
		'giropay' => array(
			'code' => 'giropay',
			'name' => 'text_giropay'
		),
		'ideal' => array(
			'code' => 'ideal',
			'name' => 'text_ideal'
		),
		'mercadopago' => array(
			'code' => 'mercadopago',
			'name' => 'text_mercadopago'
		),
		'mybank' => array(
			'code' => 'mybank',
			'name' => 'text_mybank'
		),
		'p24' => array(
			'code' => 'p24',
			'name' => 'text_p24'
		),
		'sepa' => array(
			'code' => 'sepa',
			'name' => 'text_sepa'
		),
		'sofort' => array(
			'code' => 'sofort',
			'name' => 'text_sofort'
		),
		'venmo' => array(
			'code' => 'venmo',
			'name' => 'text_venmo'
		)
	),
	'applepay_button_align' => array(
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
	'applepay_button_size' => array(
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
	'applepay_button_color' => array(
		'black' => array(
			'code' => 'black',
			'name' => 'text_black'
		),
		'white' => array(
			'code' => 'white',
			'name' => 'text_white'
		),
		'white_outline' => array(
			'code' => 'white-outline',
			'name' => 'text_white_outline'
		)
	),
	'applepay_button_shape' => array(
		'pill' => array(
			'code' => 'pill',
			'name' => 'text_pill'
		),
		'rect' => array(
			'code' => 'rect',
			'name' => 'text_rect'
		)
	),
	'applepay_button_type' => array(
		'buy' => array(
			'code' => 'buy',
			'name' => 'text_buy'
		),
		'donate' => array(
			'code' => 'donate',
			'name' => 'text_donate'
		),
		'plain' => array(
			'code' => 'plain',
			'name' => 'text_plain'
		),
		'check-out' => array(
			'code' => 'check-out',
			'name' => 'text_check_out'
		)
	),
	'applepay_button_width' => array(
		'small' => '200px',
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'card_align' => array(
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
	'card_size' => array(
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
	'card_width' => array(
		'medium' => '250px',
		'large' => '350px',
		'responsive' => ''
	),
	'card_secure_scenario' => array(
		'failed_authentication' => array(
			'code' => 'failed_authentication',
			'name' => 'text_3ds_failed_authentication',
			'error' => 'error_3ds_failed_authentication',
			'recommended' => 0
		),
		'rejected_authentication' => array(
			'code' => 'rejected_authentication',
			'name' => 'text_3ds_rejected_authentication',
			'error' => 'error_3ds_rejected_authentication',
			'recommended' => 0
		),
		'attempted_authentication' => array(
			'code' => 'attempted_authentication',
			'name' => 'text_3ds_attempted_authentication',
			'error' => 'error_3ds_attempted_authentication',
			'recommended' => 1
		),
		'unable_authentication' => array(
			'code' => 'unable_authentication',
			'name' => 'text_3ds_unable_authentication',
			'error' => 'error_3ds_unable_authentication',
			'recommended' => 0
		),
		'challenge_authentication' => array(
			'code' => 'challenge_authentication',
			'name' => 'text_3ds_challenge_authentication',
			'error' => 'error_3ds_challenge_authentication',
			'recommended' => 0
		),
		'card_ineligible' => array(
			'code' => 'card_ineligible',
			'name' => 'text_3ds_card_ineligible',
			'error' => 'error_3ds_card_ineligible',
			'recommended' => 1
		),
		'system_unavailable' => array(
			'code' => 'system_unavailable',
			'name' => 'text_3ds_system_unavailable',
			'error' => 'error_3ds_system_unavailable',
			'recommended' => 0
		),
		'system_bypassed' => array(
			'code' => 'system_bypassed',
			'name' => 'text_3ds_system_bypassed',
			'error' => 'error_3ds_system_bypassed',
			'recommended' => 1
		)
	),
	'message_insert_type' => array(
		'into_begin' => array(
			'code'	=> 'prepend',
			'name'	=> 'text_insert_prepend'
		),
		'into_end' => array(
			'code'	=> 'append',
			'name'	=> 'text_insert_append'
		),
		'before' => array(
			'code'	=> 'before',
			'name'	=> 'text_insert_before'
		),
		'after' => array(
			'code'	=> 'after',
			'name'	=> 'text_insert_after'
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
	'message_flex_ratio' => array('1x1', '1x4', '8x1', '20x1'),
	'contact_sales' => array('100k - 250k', '250k - 2m', '2m - 10m', '10m - 20m', '20m - 50m', '50m +'),
	'contact_product' => array(
		array(
			'code' => 'BT DCC',
			'name' => 'text_bt_dcc'
		),
		array(
			'code' => 'Express Checkout (EC)',
			'name' => 'text_express_checkout'
		),
		array(
			'code' => 'Credit - Installments',
			'name' => 'text_credit_installments'
		),
		array(
			'code' => 'Point of Sale',
			'name' => 'text_point_of_sale'
		),
		array(
			'code' => 'Invoicing API',
			'name' => 'text_invoicing_api'
		),
		array(
			'code' => 'PayPal Working Capital',
			'name' => 'text_paypal_working_capital'
		),
		array(
			'code' => 'Risk servicing',
			'name' => 'text_risk_servicing'
		),
		array(
			'code' => 'PayPal Here',
			'name' => 'text_paypal_here'
		),
		array(
			'code' => 'Payouts',
			'name' => 'text_payouts'
		),
		array(
			'code' => 'Marketing solutions',
			'name' => 'text_marketing_solutions'
		),
	)
);
?>