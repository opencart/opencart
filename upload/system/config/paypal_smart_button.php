<?php 
$_['paypal_smart_button_setting'] = array(
	'page' => array(
		'product' => array(
			'code' => 'product',
			'name' => 'text_product_page',
			'status' => true,
			'insert_tag' => '#content #product #button-cart',
			'insert_type' => 'after',
			'button_align' => 'center',
			'button_size' => 'responsive',
			'button_color' => 'gold',
			'button_shape' => 'rect',
			'button_label' => 'paypal',
			'button_tagline' => false,
			'message_status' => true,
			'message_align' => 'center',
			'message_size' => 'responsive',
			'message_layout' => 'text',
			'message_text_color' => 'black',
			'message_text_size' => '12',
			'message_flex_color' => 'blue',
			'message_flex_ratio' => '8x1'
		),
		'cart' => array(
			'code' => 'cart',
			'name' => 'text_cart_page',
			'status' => true,
			'insert_tag' => '#content',
			'insert_type' => 'append',
			'button_align' => 'right',
			'button_size' => 'large',
			'button_color' => 'gold',
			'button_shape' => 'rect',
			'button_label' => 'paypal',
			'button_tagline' => false,
			'message_status' => true,
			'message_align' => 'right',
			'message_size' => 'large',
			'message_layout' => 'text',
			'message_text_color' => 'black',
			'message_text_size' => '12',
			'message_flex_color' => 'blue',
			'message_flex_ratio' => '8x1'
		)
	),
	'insert_type' => array(
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