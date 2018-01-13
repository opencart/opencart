<?php
function db_schema() {
	$tables = array();

	$tables[] = array(
		'name' => 'address',
		'field' => array(
			array(
				'name' => 'address_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'company',
				'type' => 'varchar(60)',
				'not_null' => true
			),
			array(
				'name' => 'address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'city',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'address_id'
		),
		'index' => array(
			array(
				'name' => 'customer_id',
				'key' => array(
					'customer_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'api',
		'field' => array(
			array(
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'username',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'key',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'api_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'api_ip',
		'field' => array(
			array(
				'name' => 'api_ip_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			)
		),
		'primary' => array(
			'api_ip_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'api_session',
		'field' => array(
			array(
				'name' => 'api_session_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'api_session_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'attribute',
		'field' => array(
			array(
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'attribute_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'attribute_description',
		'field' => array(
			array(
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'attribute_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'attribute_group',
		'field' => array(
			array(
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'attribute_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'attribute_group_description',
		'field' => array(
			array(
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'attribute_group_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'banner',
		'field' => array(
			array(
				'name' => 'banner_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'banner_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'banner_image',
		'field' => array(
			array(
				'name' => 'banner_image_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'banner_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'link',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			)
		),
		'primary' => array(
			'banner_image_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'cart',
		'field' => array(
			array(
				'name' => 'cart_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'option',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(5)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'cart_id'
		),
		'index' => array(
			array(
				'name' => 'cart_id',
				'key' => array(
					'api_id',
					'customer_id',
					'session_id',
					'product_id',
					'recurring_id'
				)
			)
		),
		'engine' => 'InnoDB',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'parent_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'top',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'column',
				'type' => 'int(3)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id'
		),
		'index' => array(
			array(
				'name' => 'parent_id',
				'key' => array(
					'parent_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category_description',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id',
			'language_id'
		),
		'index' => array(
			array(
				'name' => 'name',
				'key' => array(
					'name'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category_filter',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id',
			'filter_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category_path',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'path_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'level',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id',
			'path_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category_to_layout',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'category_to_store',
		'field' => array(
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'category_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'country',
		'field' => array(
			array(
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'iso_code_2',
				'type' => 'varchar(2)',
				'not_null' => true
			),
			array(
				'name' => 'iso_code_3',
				'type' => 'varchar(3)',
				'not_null' => true
			),
			array(
				'name' => 'address_format',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'postcode_required',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			)
		),
		'primary' => array(
			'country_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'coupon',
		'field' => array(
			array(
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(20)',
				'not_null' => true
			),
			array(
				'name' => 'type',
				'type' => 'char(1)',
				'not_null' => true
			),
			array(
				'name' => 'discount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'logged',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'shipping',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'uses_total',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'uses_customer',
				'type' => 'varchar(11)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'coupon_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'coupon_category',
		'field' => array(
			array(
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'coupon_id',
			'category_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'coupon_history',
		'field' => array(
			array(
				'name' => 'coupon_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'coupon_history_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'coupon_product',
		'field' => array(
			array(
				'name' => 'coupon_product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'coupon_product_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'cron',
		'field' => array(
			array(
				'name' => 'cron_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'cycle',
				'type' => 'varchar(12)',
				'not_null' => true
			),
			array(
				'name' => 'action',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'cron_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'currency',
		'field' => array(
			array(
				'name' => 'currency_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(3)',
				'not_null' => true
			),
			array(
				'name' => 'symbol_left',
				'type' => 'varchar(12)',
				'not_null' => true
			),
			array(
				'name' => 'symbol_right',
				'type' => 'varchar(12)',
				'not_null' => true
			),
			array(
				'name' => 'decimal_place',
				'type' => 'char(1)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'double(15,8)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'currency_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer',
		'field' => array(
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'fax',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'password',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'salt',
				'type' => 'varchar(9)',
				'not_null' => true
			),
			array(
				'name' => 'cart',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'wishlist',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'newsletter',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'address_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'safe',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'token',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_activity',
		'field' => array(
			array(
				'name' => 'customer_activity_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'key',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'data',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_activity_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_affiliate',
		'field' => array(
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'company',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'website',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'tracking',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'commission',
				'type' => 'decimal(4,2)',
				'not_null' => true,
				'default' => '0.00'
			),
			array(
				'name' => 'tax',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'payment',
				'type' => 'varchar(6)',
				'not_null' => true
			),
			array(
				'name' => 'cheque',
				'type' => 'varchar(100)',
				'not_null' => true
			),
			array(
				'name' => 'paypal',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'bank_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'bank_branch_number',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'bank_swift_code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'bank_account_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'bank_account_number',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_affiliate_report',
		'field' => array(
			array(
				'name' => 'customer_affiliate_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_affiliate_report_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_approval',
		'field' => array(
			array(
				'name' => 'customer_approval_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'type',
				'type' => 'varchar(9)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_approval_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_group',
		'field' => array(
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'approval',
				'type' => 'int(1)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_group_description',
		'field' => array(
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_group_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_history',
		'field' => array(
			array(
				'name' => 'customer_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_history_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_login',
		'field' => array(
			array(
				'name' => 'customer_login_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'total',
				'type' => 'int(4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_login_id'
		),
		'index' => array(
			array(
				'name' => 'email',
				'key' => array(
					'email'
				)
			),
			array(
				'name' => 'ip',
				'key' => array(
					'ip'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_ip',
		'field' => array(
			array(
				'name' => 'customer_ip_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_ip_id'
		),
		'index' => array(
			array(
				'name' => 'ip',
				'key' => array(
					'ip'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_online',
		'field' => array(
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'url',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'referer',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'ip'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_reward',
		'field' => array(
			array(
				'name' => 'customer_reward_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_reward_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_transaction',
		'field' => array(
			array(
				'name' => 'customer_transaction_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_transaction_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_search',
		'field' => array(
			array(
				'name' => 'customer_search_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'sub_category',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'products',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_search_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'customer_wishlist',
		'field' => array(
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'customer_id',
			'product_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'custom_field',
		'field' => array(
			array(
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'validation',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'location',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'custom_field_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'custom_field_customer_group',
		'field' => array(
			array(
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'required',
				'type' => 'tinyint(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'custom_field_id',
			'customer_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'custom_field_description',
		'field' => array(
			array(
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			)
		),
		'primary' => array(
			'custom_field_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'custom_field_value',
		'field' => array(
			array(
				'name' => 'custom_field_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'custom_field_value_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'custom_field_value_description',
		'field' => array(
			array(
				'name' => 'custom_field_value_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			)
		),
		'primary' => array(
			'custom_field_value_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'download',
		'field' => array(
			array(
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'filename',
				'type' => 'varchar(160)',
				'not_null' => true
			),
			array(
				'name' => 'mask',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'download_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'download_description',
		'field' => array(
			array(
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'download_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'download_report',
		'field' => array(
			array(
				'name' => 'download_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'download_report_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'event',
		'field' => array(
			array(
				'name' => 'event_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'trigger',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'action',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'event_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'extension',
		'field' => array(
			array(
				'name' => 'extension_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'extension_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'extension_install',
		'field' => array(
			array(
				'name' => 'extension_install_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'extension_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'extension_download_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'filename',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'extension_install_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'extension_path',
		'field' => array(
			array(
				'name' => 'extension_path_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'extension_install_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'path',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'extension_path_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'filter',
		'field' => array(
			array(
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'filter_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'filter_description',
		'field' => array(
			array(
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'filter_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'filter_group',
		'field' => array(
			array(
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'filter_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'filter_group_description',
		'field' => array(
			array(
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'filter_group_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'geo_zone',
		'field' => array(
			array(
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'geo_zone_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'information',
		'field' => array(
			array(
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'bottom',
				'type' => 'int(1)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			)
		),
		'primary' => array(
			'information_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'information_description',
		'field' => array(
			array(
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'mediumtext',
				'not_null' => true
			),
			array(
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'information_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'information_to_layout',
		'field' => array(
			array(
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'information_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'information_to_store',
		'field' => array(
			array(
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'information_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'language',
		'field' => array(
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(5)',
				'not_null' => true
			),
			array(
				'name' => 'locale',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'language_id'
		),
		'index' => array(
			array(
				'name' => 'name',
				'key' => array(
					'name'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'layout',
		'field' => array(
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'layout_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'layout_module',
		'field' => array(
			array(
				'name' => 'layout_module_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'position',
				'type' => 'varchar(14)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'layout_module_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'layout_route',
		'field' => array(
			array(
				'name' => 'layout_route_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'layout_route_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'length_class',
		'field' => array(
			array(
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'value',
				'type' => 'decimal(15,8)',
				'not_null' => true
			)
		),
		'primary' => array(
			'length_class_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'length_class_description',
		'field' => array(
			array(
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'unit',
				'type' => 'varchar(4)',
				'not_null' => true
			)
		),
		'primary' => array(
			'length_class_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'location',
		'field' => array(
			array(
				'name' => 'location_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'address',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'fax',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'geocode',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'open',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'location_id'
		),
		'index' => array(
			array(
				'name' => 'name',
				'key' => array(
					'name'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'manufacturer',
		'field' => array(
			array(
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'manufacturer_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'manufacturer_to_store',
		'field' => array(
			array(
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'manufacturer_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'marketing',
		'field' => array(
			array(
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'clicks',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'marketing_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'marketing_report',
		'field' => array(
			array(
				'name' => 'marketing_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'marketing_report_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'modification',
		'field' => array(
			array(
				'name' => 'modification_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'extension_install_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'extension_download_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'author',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'version',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'link',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'xml',
				'type' => 'mediumtext',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'modification_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'module',
		'field' => array(
			array(
				'name' => 'module_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'setting',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'module_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'option',
		'field' => array(
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'option_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'option_description',
		'field' => array(
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			)
		),
		'primary' => array(
			'option_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'option_value',
		'field' => array(
			array(
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'option_value_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'option_value_description',
		'field' => array(
			array(
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			)
		),
		'primary' => array(
			'option_value_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order',
		'field' => array(
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'invoice_no',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'invoice_prefix',
				'type' => 'varchar(26)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'store_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'store_url',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'fax',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'payment_firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'payment_lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'payment_company',
				'type' => 'varchar(60)',
				'not_null' => true
			),
			array(
				'name' => 'payment_address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_city',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'payment_country',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_country_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'payment_zone',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'payment_address_format',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'payment_custom_field',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'payment_method',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'payment_code',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_company',
				'type' => 'varchar(60)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_city',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_country',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_country_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_zone',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_address_format',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'shipping_custom_field',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'shipping_method',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_code',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'affiliate_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'commission',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'tracking',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'currency_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'currency_code',
				'type' => 'varchar(3)',
				'not_null' => true
			),
			array(
				'name' => 'currency_value',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '1.00000000'
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'forwarded_ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'user_agent',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'accept_language',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_history',
		'field' => array(
			array(
				'name' => 'order_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'notify',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_history_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_option',
		'field' => array(
			array(
				'name' => 'order_option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_option_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_product',
		'field' => array(
			array(
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true
			),
			array(
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'tax',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'reward',
				'type' => 'int(8)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_product_id'
		),
		'index' => array(
			array(
				'name' => 'order_id',
				'key' => array(
					'order_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_recurring',
		'field' => array(
			array(
				'name' => 'order_recurring_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'reference',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'product_quantity',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_frequency',
				'type' => 'varchar(25)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_duration',
				'type' => 'smallint(6)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			),
			array(
				'name' => 'trial',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'trial_frequency',
				'type' => 'varchar(25)',
				'not_null' => true
			),
			array(
				'name' => 'trial_cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			),
			array(
				'name' => 'trial_duration',
				'type' => 'smallint(6)',
				'not_null' => true
			),
			array(
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_recurring_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_recurring_transaction',
		'field' => array(
			array(
				'name' => 'order_recurring_transaction_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_recurring_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'reference',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'type',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(10,4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_recurring_transaction_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_shipment',
		'field' => array(
			array(
				'name' => 'order_shipment_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'shipping_courier_id',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'tracking_number',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_shipment_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'shipping_courier',
		'field' => array(
			array(
				'name' => 'shipping_courier_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_courier_code',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'shipping_courier_name',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'shipping_courier_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_status',
		'field' => array(
			array(
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_status_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_total',
		'field' => array(
			array(
				'name' => 'order_total_id',
				'type' => 'int(10)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_total_id'
		),
		'index' => array(
			array(
				'name' => 'order_id',
				'key' => array(
					'order_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'order_voucher',
		'field' => array(
			array(
				'name' => 'order_voucher_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'from_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'from_email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'to_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'to_email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'message',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			)
		),
		'primary' => array(
			'order_voucher_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'sku',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'upc',
				'type' => 'varchar(12)',
				'not_null' => true
			),
			array(
				'name' => 'ean',
				'type' => 'varchar(14)',
				'not_null' => true
			),
			array(
				'name' => 'jan',
				'type' => 'varchar(13)',
				'not_null' => true
			),
			array(
				'name' => 'isbn',
				'type' => 'varchar(17)',
				'not_null' => true
			),
			array(
				'name' => 'mpn',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'location',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'stock_status_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'shipping',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			),
			array(
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'date_available',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'weight',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			),
			array(
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'length',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			),
			array(
				'name' => 'width',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			),
			array(
				'name' => 'height',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			),
			array(
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'subtract',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			),
			array(
				'name' => 'minimum',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '1'
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'viewed',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_attribute',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'text',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'attribute_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_description',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'tag',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'language_id'
		),
		'index' => array(
			array(
				'name' => 'name',
				'key' => array(
					'name'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_discount',
		'field' => array(
			array(
				'name' => 'product_discount_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			),
			array(
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			)
		),
		'primary' => array(
			'product_discount_id'
		),
		'index' => array(
			array(
				'name' => 'product_id',
				'key' => array(
					'product_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_filter',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'filter_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_image',
		'field' => array(
			array(
				'name' => 'product_image_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			)
		),
		'primary' => array(
			'product_image_id'
		),
		'index' => array(
			array(
				'name' => 'product_id',
				'key' => array(
					'product_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_option',
		'field' => array(
			array(
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'required',
				'type' => 'tinyint(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_option_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_option_value',
		'field' => array(
			array(
				'name' => 'product_option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(3)',
				'not_null' => true
			),
			array(
				'name' => 'subtract',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'price_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			),
			array(
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true
			),
			array(
				'name' => 'points_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			),
			array(
				'name' => 'weight',
				'type' => 'decimal(15,8)',
				'not_null' => true
			),
			array(
				'name' => 'weight_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_option_value_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_recurring',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'recurring_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'recurring_id',
			'customer_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_related',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'related_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'related_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_reward',
		'field' => array(
			array(
				'name' => 'product_reward_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => 0
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			)
		),
		'primary' => array(
			'product_reward_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_special',
		'field' => array(
			array(
				'name' => 'product_special_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			),
			array(
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			)
		),
		'primary' => array(
			'product_special_id'
		),
		'index' => array(
			array(
				'name' => 'product_id',
				'key' => array(
					'product_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_to_category',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'category_id'
		),
		'index' => array(
			array(
				'name' => 'category_id',
				'key' => array(
					'category_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_to_download',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'download_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_to_layout',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'product_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'product_to_store',
		'field' => array(
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			)
		),
		'primary' => array(
			'product_id',
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'recurring',
		'field' => array(
			array(
				'name' => 'recurring_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			),
			array(
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			),
			array(
				'name' => 'duration',
				'type' => 'int(10)',
				'not_null' => true
			),
			array(
				'name' => 'cycle',
				'type' => 'int(10)',
				'not_null' => true
			),
			array(
				'name' => 'trial_status',
				'type' => 'tinyint(4)',
				'not_null' => true
			),
			array(
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			),
			array(
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			),
			array(
				'name' => 'trial_duration',
				'type' => 'int(10)',
				'not_null' => true
			),
			array(
				'name' => 'trial_cycle',
				'type' => 'int(10)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(4)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'recurring_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'recurring_description',
		'field' => array(
			array(
				'name' => 'recurring_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'recurring_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'return',
		'field' => array(
			array(
				'name' => 'return_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'product',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true
			),
			array(
				'name' => 'opened',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'return_reason_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'return_action_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_ordered',
				'type' => 'date',
				'not_null' => true,
				'default' => '0000-00-00'
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'return_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'return_action',
		'field' => array(
			array(
				'name' => 'return_action_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			)
		),
		'primary' => array(
			'return_action_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'return_history',
		'field' => array(
			array(
				'name' => 'return_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'return_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'notify',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'return_history_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'return_reason',
		'field' => array(
			array(
				'name' => 'return_reason_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			)
		),
		'primary' => array(
			'return_reason_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'return_status',
		'field' => array(
			array(
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'return_status_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'review',
		'field' => array(
			array(
				'name' => 'review_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'author',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'text',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'rating',
				'type' => 'int(1)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'review_id'
		),
		'index' => array(
			array(
				'name' => 'product_id',
				'key' => array(
					'product_id'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'statistics',
		'field' => array(
			array(
				'name' => 'statistics_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'decimal(15,4)',
				'not_null' => true
			)
		),
		'primary' => array(
			'statistics_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'session',
		'field' => array(
			array(
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'data',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'expire',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'session_id'
		),
		'engine' => 'InnoDB',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'setting',
		'field' => array(
			array(
				'name' => 'setting_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'code',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'key',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'serialized',
				'type' => 'tinyint(1)',
				'not_null' => true
			)
		),
		'primary' => array(
			'setting_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'stock_status',
		'field' => array(
			array(
				'name' => 'stock_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'stock_status_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'store',
		'field' => array(
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'url',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'ssl',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'store_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'tax_class',
		'field' => array(
			array(
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'tax_class_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'tax_rate',
		'field' => array(
			array(
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'rate',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			),
			array(
				'name' => 'type',
				'type' => 'char(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'tax_rate_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'tax_rate_to_customer_group',
		'field' => array(
			array(
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			)
		),
		'primary' => array(
			'tax_rate_id',
			'customer_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'tax_rule',
		'field' => array(
			array(
				'name' => 'tax_rule_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'based',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			)
		),
		'primary' => array(
			'tax_rule_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'theme',
		'field' => array(
			array(
				'name' => 'theme_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'theme',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'mediumtext',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'theme_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'translation',
		'field' => array(
			array(
				'name' => 'translation_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'key',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'translation_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'upload',
		'field' => array(
			array(
				'name' => 'upload_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'filename',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'upload_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'seo_regex',
		'field' => array(
			array(
				'name' => 'seo_regex_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'regex',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			)
		),
		'primary' => array(
			'seo_regex_id'
		),
		'index' => array(
			array(
				'name' => 'regex',
				'key' => array(
					'regex'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'seo_url',
		'field' => array(
			array(
				'name' => 'seo_url_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'query',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'push',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'seo_url_id'
		),
		'index' => array(
			array(
				'name' => 'query',
				'key' => array(
					'query'
				)
			),
			array(
				'name' => 'keyword',
				'key' => array(
					'keyword'
				)
			)
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'user',
		'field' => array(
			array(
				'name' => 'user_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'user_group_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'username',
				'type' => 'varchar(20)',
				'not_null' => true
			),
			array(
				'name' => 'password',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'salt',
				'type' => 'varchar(9)',
				'not_null' => true
			),
			array(
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'user_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'user_group',
		'field' => array(
			array(
				'name' => 'user_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'permission',
				'type' => 'text',
				'not_null' => true
			)
		),
		'primary' => array(
			'user_group_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'voucher',
		'field' => array(
			array(
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(10)',
				'not_null' => true
			),
			array(
				'name' => 'from_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'from_email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'to_name',
				'type' => 'varchar(64)',
				'not_null' => true
			),
			array(
				'name' => 'to_email',
				'type' => 'varchar(96)',
				'not_null' => true
			),
			array(
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'message',
				'type' => 'text',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'voucher_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'voucher_history',
		'field' => array(
			array(
				'name' => 'voucher_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'voucher_history_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'voucher_theme',
		'field' => array(
			array(
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			)
		),
		'primary' => array(
			'voucher_theme_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'voucher_theme_description',
		'field' => array(
			array(
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			)
		),
		'primary' => array(
			'voucher_theme_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'weight_class',
		'field' => array(
			array(
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'value',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			)
		),
		'primary' => array(
			'weight_class_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'weight_class_description',
		'field' => array(
			array(
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'unit',
				'type' => 'varchar(4)',
				'not_null' => true
			)
		),
		'primary' => array(
			'weight_class_id',
			'language_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'zone',
		'field' => array(
			array(
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			),
			array(
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			),
			array(
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			)
		),
		'primary' => array(
			'zone_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	$tables[] = array(
		'name' => 'zone_to_geo_zone',
		'field' => array(
			array(
				'name' => 'zone_to_geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			),
			array(
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			),
			array(
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			),
			array(
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			),
			array(
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			)
		),
		'primary' => array(
			'zone_to_geo_zone_id'
		),
		'engine' => 'MyISAM',
		'charset' => 'utf8',
		'collate' => 'utf8_general_ci'
	);

	return $tables;
}
