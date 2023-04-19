<?php
function oc_db_schema() {
	$tables = [];

	$tables[] = [
		'name' => 'address',
		'field' => [
			[
				'name' => 'address_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'company',
				'type' => 'varchar(60)',
				'not_null' => true
			],
			[
				'name' => 'address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'city',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'default',
				'type' => 'tinyint(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'address_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'index' => [
			[
				'name' => 'customer_id',
				'key' => [
					'customer_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'address_format',
		'field' => [
			[
				'name' => 'address_format_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'address_format',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'address_format_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'api',
		'field' => [
			[
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'username',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'key',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'api_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'api_ip',
		'field' => [
			[
				'name' => 'api_ip_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			]
		],
		'primary' => [
			'api_ip_id'
		],
		'foreign' => [
			[
				'key'   => 'api_id',
				'table' => 'api',
				'field' => 'api_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'api_session',
		'field' => [
			[
				'name' => 'api_session_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'api_session_id'
		],
		'foreign' => [
			[
				'key'   => 'api_id',
				'table' => 'api',
				'field' => 'api_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'attribute',
		'field' => [
			[
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'attribute_id'
		],
		'foreign' => [
			[
				'key'   => 'attribute_group_id',
				'table' => 'attribute_group',
				'field' => 'attribute_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'attribute_description',
		'field' => [
			[
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'attribute_id',
			'language_id'
		],
		'foreign' =>  [
			[
				'key'   => 'attribute_id',
				'table' => 'attribute',
				'field' => 'attribute_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'attribute_group',
		'field' => [
			[
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'attribute_group_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'attribute_group_description',
		'field' => [
			[
				'name' => 'attribute_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'attribute_group_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'attribute_group_id',
				'table' => 'attribute_group',
				'field' => 'attribute_group_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'banner',
		'field' => [
			[
				'name' => 'banner_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'banner_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'banner_image',
		'field' => [
			[
				'name' => 'banner_image_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'banner_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'link',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			]
		],
		'primary' => [
			'banner_image_id'
		],
		'foreign' => [
			[
				'key'   => 'banner_id',
				'table' => 'banner',
				'field' => 'banner_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'cart',
		'field' => [
			[
				'name' => 'cart_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'api_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'option',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(5)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'cart_id'
		],
		'foreign' => [
			[
				'key'   => 'api_id',
				'table' => 'api',
				'field' => 'api_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'session_id',
				'table' => 'session',
				'field' => 'session_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'subscription_plan_id',
				'table' => 'subscription_plan',
				'field' => 'subscription_plan_id'
			]
		],
		'index' => [
			[
				'name' => 'cart_id',
				'key' => [
					'api_id',
					'customer_id',
					'session_id',
					'product_id',
					'subscription_plan_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'parent_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'top',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'column',
				'type' => 'int(3)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'category_id'
		],
		'index' => [
			[
				'name' => 'parent_id',
				'key' => [
					'parent_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category_description',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'category_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'index' => [
			[
				'name' => 'name',
				'key' => [
					'name'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category_filter',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'category_id',
			'filter_id'
		],
		'foreign' => [
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			],
			[
				'key'   => 'filter_id',
				'table' => 'filter',
				'field' => 'filter_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category_path',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'path_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'level',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'category_id',
			'path_id'
		],
		'foreign' => [
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category_to_layout',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'category_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'category_to_store',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			]
		],
		'primary' => [
			'category_id'
		],
		'foreign' => [
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'country',
		'field' => [
			[
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'iso_code_2',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'iso_code_3',
				'type' => 'varchar(3)',
				'not_null' => true
			],
			[
				'name' => 'address_format_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'postcode_required',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			]
		],
		'primary' => [
			'country_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'coupon',
		'field' => [
			[
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(20)',
				'not_null' => true
			],
			[
				'name' => 'type',
				'type' => 'char(1)',
				'not_null' => true
			],
			[
				'name' => 'discount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'logged',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'shipping',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'uses_total',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'uses_customer',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name'     => 'status',
				'type'     => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name'     => 'date_added',
				'type'     => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'coupon_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'coupon_category',
		'field' => [
			[
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'coupon_id',
			'category_id'
		],
		'foreign' => [
			[
				'key'   => 'coupon_id',
				'table' => 'coupon',
				'field' => 'coupon_id'
			],
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'coupon_history',
		'field' => [
			[
				'name' => 'coupon_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'coupon_history_id'
		],
		'foreign' => [
			[
				'key'   => 'coupon_id',
				'table' => 'coupon',
				'field' => 'coupon_id'
			],
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'coupon_product',
		'field' => [
			[
				'name' => 'coupon_product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'coupon_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'coupon_product_id'
		],
		'foreign' => [
			[
				'key'   => 'coupon_id',
				'table' => 'coupon',
				'field' => 'coupon_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'cron',
		'field' => [
			[
				'name' => 'cron_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'cycle',
				'type' => 'varchar(12)',
				'not_null' => true
			],
			[
				'name' => 'action',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'cron_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'currency',
		'field' => [
			[
				'name' => 'currency_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(3)',
				'not_null' => true
			],
			[
				'name' => 'symbol_left',
				'type' => 'varchar(12)',
				'not_null' => true
			],
			[
				'name' => 'symbol_right',
				'type' => 'varchar(12)',
				'not_null' => true
			],
			[
				'name' => 'decimal_place',
				'type' => 'int(1)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'double(15,8)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'currency_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer',
		'field' => [
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'password',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'newsletter',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'safe',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'token',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_activity',
		'field' => [
			[
				'name' => 'customer_activity_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'data',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_activity_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_affiliate',
		'field' => [
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'company',
				'type' => 'varchar(60)',
				'not_null' => true
			],
			[
				'name' => 'website',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'tracking',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'commission',
				'type' => 'decimal(4,2)',
				'not_null' => true,
				'default' => '0.00'
			],
			[
				'name' => 'tax',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'payment',
				'type' => 'varchar(6)',
				'not_null' => true
			],
			[
				'name' => 'cheque',
				'type' => 'varchar(100)',
				'not_null' => true
			],
			[
				'name' => 'paypal',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'bank_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'bank_branch_number',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'bank_swift_code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'bank_account_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'bank_account_number',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_affiliate_report',
		'field' => [
			[
				'name' => 'customer_affiliate_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_affiliate_report_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_approval',
		'field' => [
			[
				'name' => 'customer_approval_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(9)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_approval_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_group',
		'field' => [
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'approval',
				'type' => 'int(1)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_group_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_group_description',
		'field' => [
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_group_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_history',
		'field' => [
			[
				'name' => 'customer_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_history_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_login',
		'field' => [
			[
				'name' => 'customer_login_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'total',
				'type' => 'int(4)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_login_id'
		],
		'index' => [
			[
				'name' => 'email',
				'key' => [
					'email'
				]
			],
			[
				'name' => 'ip',
				'key' => [
					'ip'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_ip',
		'field' => [
			[
				'name' => 'customer_ip_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_ip_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'index' => [
			[
				'name' => 'ip',
				'key' => [
					'ip'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_online',
		'field' => [
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'url',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'referer',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'ip'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_reward',
		'field' => [
			[
				'name' => 'customer_reward_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_reward_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_transaction',
		'field' => [
			[
				'name' => 'customer_transaction_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_transaction_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_search',
		'field' => [
			[
				'name' => 'customer_search_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'sub_category',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'products',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_search_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'customer_wishlist',
		'field' => [
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'customer_id',
			'product_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'custom_field',
		'field' => [
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'validation',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'location',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'custom_field_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'custom_field_customer_group',
		'field' => [
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'required',
				'type' => 'tinyint(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'custom_field_id',
			'customer_group_id'
		],
		'foreign' => [
			[
				'key'   => 'custom_field_id',
				'table' => 'custom_field',
				'field' => 'custom_field_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'custom_field_description',
		'field' => [
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'custom_field_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'custom_field_id',
				'table' => 'custom_field',
				'field' => 'custom_field_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'custom_field_value',
		'field' => [
			[
				'name' => 'custom_field_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'custom_field_value_id'
		],
		'foreign' => [
			[
				'key'   => 'custom_field_id',
				'table' => 'custom_field',
				'field' => 'custom_field_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'custom_field_value_description',
		'field' => [
			[
				'name' => 'custom_field_value_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'custom_field_value_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			],
			[
				'key'   => 'custom_field_id',
				'table' => 'custom_field',
				'field' => 'custom_field_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'download',
		'field' => [
			[
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'filename',
				'type' => 'varchar(160)',
				'not_null' => true
			],
			[
				'name' => 'mask',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'download_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'download_description',
		'field' => [
			[
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'download_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'download_report',
		'field' => [
			[
				'name' => 'download_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'download_report_id'
		],
		'foreign' => [
			[
				'key'   => 'download_id',
				'table' => 'download',
				'field' => 'download_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'event',
		'field' => [
			[
				'name' => 'event_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'trigger',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'action',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '1'
			]
		],
		'primary' => [
			'event_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'extension',
		'field' => [
			[
				'name' => 'extension_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'extension_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'extension_install',
		'field' => [
			[
				'name' => 'extension_install_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'extension_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'extension_download_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'version',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'author',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'link',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'extension_install_id'
		],
		'foreign' => [
			[
				'key'   => 'extension_id',
				'table' => 'extension',
				'field' => 'extension_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'extension_path',
		'field' => [
			[
				'name' => 'extension_path_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'extension_install_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'path',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'extension_path_id'
		],
		'foreign' => [
			[
				'key'   => 'extension_install_id',
				'table' => 'extension_install',
				'field' => 'extension_install_id'
			]
		],
		'index' => [
			[
				'name' => 'path',
				'key' => [
					'path'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'filter',
		'field' => [
			[
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'filter_id'
		],
		'foreign' => [
			[
				'key'   => 'filter_group_id',
				'table' => 'filter_group',
				'field' => 'filter_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'filter_description',
		'field' => [
			[
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'filter_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			],
			[
				'key'   => 'filter_group_id',
				'table' => 'filter_group',
				'field' => 'filter_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'filter_group',
		'field' => [
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'filter_group_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'filter_group_description',
		'field' => [
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'filter_group_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'filter_group_id',
				'table' => 'filter_group',
				'field' => 'filter_group_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'gdpr',
		'field' => [
			[
				'name' => 'gdpr_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'action',
				'type' => 'varchar(6)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'gdpr_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'geo_zone',
		'field' => [
			[
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'geo_zone_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'information',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'bottom',
				'type' => 'int(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			]
		],
		'primary' => [
			'information_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'information_description',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'mediumtext',
				'not_null' => true
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'information_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'information_to_layout',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'information_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'information_id',
				'table' => 'information',
				'field' => 'information_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'information_to_store',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'information_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'information_id',
				'table' => 'information',
				'field' => 'information_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'language',
		'field' => [
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(5)',
				'not_null' => true
			],
			[
				'name' => 'locale',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'language_id'
		],
		'index' => [
			[
				'name' => 'name',
				'key' => [
					'name'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'layout',
		'field' => [
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'layout_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'layout_module',
		'field' => [
			[
				'name' => 'layout_module_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'position',
				'type' => 'varchar(14)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'layout_module_id'
		],
		'foreign' => [
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'layout_route',
		'field' => [
			[
				'name' => 'layout_route_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'layout_route_id'
		],
		'foreign' => [
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'length_class',
		'field' => [
			[
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,8)',
				'not_null' => true
			]
		],
		'primary' => [
			'length_class_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'length_class_description',
		'field' => [
			[
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'unit',
				'type' => 'varchar(4)',
				'not_null' => true
			]
		],
		'primary' => [
			'length_class_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'length_class_id',
				'table' => 'length_class',
				'field' => 'length_class_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'location',
		'field' => [
			[
				'name' => 'location_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'address',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'geocode',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'open',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'location_id'
		],
		'index' => [
			[
				'name' => 'name',
				'key' => [
					'name'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'manufacturer',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'manufacturer_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'manufacturer_to_layout',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'manufacturer_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'manufacturer_id',
				'table' => 'manufacturer',
				'field' => 'manufacturer_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'manufacturer_to_store',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'manufacturer_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'manufacturer_id',
				'table' => 'manufacturer',
				'field' => 'manufacturer_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'marketing',
		'field' => [
			[
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'clicks',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'marketing_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'marketing_report',
		'field' => [
			[
				'name' => 'marketing_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'marketing_report_id'
		],
		'foreign' => [
			[
				'key'   => 'marketing_id',
				'table' => 'marketing',
				'field' => 'marketing_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'module',
		'field' => [
			[
				'name' => 'module_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'setting',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'module_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'notification',
		'field' => [
			[
				'name' => 'notification_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'text',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(11)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'notification_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'option',
		'field' => [
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'option_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'option_description',
		'field' => [
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'option_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'option_value',
		'field' => [
			[
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'option_value_id'
		],
		'foreign' => [
			[
				'key'   => 'option_id',
				'table' => 'option',
				'field' => 'option_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'option_value_description',
		'field' => [
			[
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'option_value_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			],
			[
				'key'   => 'option_id',
				'table' => 'option',
				'field' => 'option_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order',
		'field' => [
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'invoice_no',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'invoice_prefix',
				'type' => 'varchar(26)',
				'not_null' => true
			],
			[
				'name' => 'transaction_id',
				'type' => 'varchar(100)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'store_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'store_url',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'payment_address_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'payment_firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'payment_lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'payment_company',
				'type' => 'varchar(60)',
				'not_null' => true
			],
			[
				'name' => 'payment_address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'payment_address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'payment_city',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'payment_postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'payment_country',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'payment_country_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'payment_zone',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'payment_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'payment_address_format',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'payment_custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'payment_method',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'shipping_address_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'shipping_firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'shipping_lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'shipping_company',
				'type' => 'varchar(60)',
				'not_null' => true
			],
			[
				'name' => 'shipping_address_1',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'shipping_address_2',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'shipping_city',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'shipping_postcode',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'shipping_country',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'shipping_country_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'shipping_zone',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'shipping_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'shipping_address_format',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'shipping_custom_field',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'shipping_method',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'affiliate_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'commission',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'tracking',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_code',
				'type' => 'varchar(5)',
				'not_null' => true
			],
			[
				'name' => 'currency_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'currency_code',
				'type' => 'varchar(3)',
				'not_null' => true
			],
			[
				'name' => 'currency_value',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '1.00000000'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'forwarded_ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'accept_language',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'order_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			],
			[
				'key'   => 'payment_country_id',
				'table' => 'country',
				'field' => 'country_id'
			],
			[
				'key'   => 'payment_zone_id',
				'table' => 'zone',
				'field' => 'zone_id'
			],
			[
				'key'   => 'shipping_country_id',
				'table' => 'country',
				'field' => 'country_id'
			],
			[
				'key'   => 'shipping_zone_id',
				'table' => 'zone',
				'field' => 'zone_id'
			],
			[
				'key'   => 'order_status_id',
				'table' => 'order_status',
				'field' => 'order_status_id'
			],
			[
				'key'   => 'affiliate_id',
				'table' => 'customer_affiliate',
				'field' => 'customer_id'
			],
			[
				'key'   => 'marketing_id',
				'table' => 'marketing',
				'field' => 'marketing_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			],
			[
				'key'   => 'currency_id',
				'table' => 'currency',
				'field' => 'currency_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_history',
		'field' => [
			[
				'name' => 'order_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'notify',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'order_history_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'order_status_id',
				'table' => 'order_status',
				'field' => 'order_status_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_option',
		'field' => [
			[
				'name' => 'order_option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_option_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'order_product_id',
				'table' => 'order_product',
				'field' => 'order_product_id'
			],
			[
				'key'   => 'product_option_id',
				'table' => 'product_option',
				'field' => 'product_option_id'
			],
			[
				'key'   => 'product_option_value_id',
				'table' => 'product_option_value',
				'field' => 'product_option_value_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_product',
		'field' => [
			[
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'master_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'total',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'tax',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'reward',
				'type' => 'int(8)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_product_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'master_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'index' => [
			[
				'name' => 'order_id',
				'key' => [
					'order_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_subscription',
		'field' => [
			[
				'name' => 'order_subscription_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'trial_cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_duration',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_remaining',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'duration',
				'type' => 'smallint(6)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_subscription_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'order_product_id',
				'table' => 'order_product',
				'field' => 'order_product_id'
			],
			[
				'key'   => 'subscription_plan_id',
				'table' => 'subscription_plan',
				'field' => 'subscription_plan_id'
			],
			[
				'key'   => 'subscription_status_id',
				'table' => 'subscription_status',
				'field' => 'subscription_status_id'
			]
		],
		'index' => [
			[
				'name' => 'order_id',
				'key' => [
					'order_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_status',
		'field' => [
			[
				'name' => 'order_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_status_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_total',
		'field' => [
			[
				'name' => 'order_total_id',
				'type' => 'int(10)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_total_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			]
		],
		'index' => [
			[
				'name' => 'order_id',
				'key' => [
					'order_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'order_voucher',
		'field' => [
			[
				'name' => 'order_voucher_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'from_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'from_email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'to_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'to_email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'message',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			]
		],
		'primary' => [
			'order_voucher_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'voucher_id',
				'table' => 'voucher',
				'field' => 'voucher_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'master_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'sku',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'upc',
				'type' => 'varchar(12)',
				'not_null' => true
			],
			[
				'name' => 'ean',
				'type' => 'varchar(14)',
				'not_null' => true
			],
			[
				'name' => 'jan',
				'type' => 'varchar(13)',
				'not_null' => true
			],
			[
				'name' => 'isbn',
				'type' => 'varchar(17)',
				'not_null' => true
			],
			[
				'name' => 'mpn',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'location',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'variant',
				'type' => 'text',
				'not_null' => true,
				'default' => ''
			],
			[
				'name' => 'override',
				'type' => 'text',
				'not_null' => true,
				'default' => ''
			],
			[
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'stock_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'shipping',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'date_available',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'weight',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			],
			[
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'length',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			],
			[
				'name' => 'width',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			],
			[
				'name' => 'height',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			],
			[
				'name' => 'length_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'subtract',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			],
			[
				'name' => 'minimum',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '1'
			],
			[
				'name' => 'sort_order',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id'
		],
		'foreign' => [
			[
				'key'   => 'master_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'stock_status_id',
				'table' => 'stock_status',
				'field' => 'stock_status_id'
			],
			[
				'key'   => 'manufacturer_id',
				'table' => 'manufacturer',
				'field' => 'manufacturer_id'
			],
			[
				'key'   => 'tax_class_id',
				'table' => 'tax_class',
				'field' => 'tax_class_id'
			],
			[
				'key'   => 'weight_class_id',
				'table' => 'weight_class',
				'field' => 'weight_class_id'
			],
			[
				'key'   => 'length_class_id',
				'table' => 'length_class',
				'field' => 'length_class_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_attribute',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'attribute_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'text',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'attribute_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'attribute_id',
				'table' => 'attribute',
				'field' => 'attribute_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_description',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'tag',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'index' => [
			[
				'name' => 'name',
				'key' => [
					'name'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_discount',
		'field' => [
			[
				'name' => 'product_discount_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true
			]
		],
		'primary' => [
			'product_discount_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'index' => [
			[
				'name' => 'product_id',
				'key' => [
					'product_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_filter',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'filter_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'filter_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'filter_id',
				'table' => 'filter',
				'field' => 'filter_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_image',
		'field' => [
			[
				'name' => 'product_image_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true,
				'default' => '0'
			]
		],
		'primary' => [
			'product_image_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'index' => [
			[
				'name' => 'product_id',
				'key' => [
					'product_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_option',
		'field' => [
			[
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'required',
				'type' => 'tinyint(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_option_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'option_id',
				'table' => 'option',
				'field' => 'option_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name'  => 'product_option_value',
		'field' => [
			[
				'name' => 'product_option_value_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'option_value_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(3)',
				'not_null' => true
			],
			[
				'name' => 'subtract',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'price_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			],
			[
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true
			],
			[
				'name' => 'points_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			],
			[
				'name' => 'weight',
				'type' => 'decimal(15,8)',
				'not_null' => true
			],
			[
				'name' => 'weight_prefix',
				'type' => 'varchar(1)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_option_value_id'
		],
		'foreign' => [
			[
				'key'   => 'product_option_id',
				'table' => 'product_option',
				'field' => 'product_option_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'option_id',
				'table' => 'option',
				'field' => 'option_id'
			],
			[
				'key'   => 'option_value_id',
				'table' => 'option_value',
				'field' => 'option_value_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_subscription',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'subscription_plan_id',
			'customer_group_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'subscription_plan_id',
				'table' => 'subscription_plan',
				'field' => 'subscription_plan_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_related',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'related_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'related_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'related_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_report',
		'field' => [
			[
				'name' => 'product_report_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default'  => 0
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'product_report_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_reward',
		'field' => [
			[
				'name' => 'product_reward_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => 0
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'points',
				'type' => 'int(8)',
				'not_null' => true,
				'default' => '0'
			]
		],
		'primary' => [
			'product_reward_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_special',
		'field' => [
			[
				'name' => 'product_special_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'date_start',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'date_end',
				'type' => 'date',
				'not_null' => true
			]
		],
		'primary' => [
			'product_special_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'index' => [
			[
				'name' => 'product_id',
				'key' => [
					'product_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_to_category',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'category_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'category_id',
				'table' => 'category',
				'field' => 'category_id'
			]
		],
		'index' => [
			[
				'name' => 'category_id',
				'key' => [
					'category_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_to_download',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'download_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'download_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'download_id',
				'table' => 'download',
				'field' => 'download_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_to_layout',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_to_store',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			]
		],
		'primary' => [
			'product_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'product_viewed',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'viewed',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'product_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'return',
		'field' => [
			[
				'name' => 'return_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'product',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true
			],
			[
				'name' => 'opened',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'return_reason_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'return_action_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_ordered',
				'type' => 'date',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'return_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'return_reason_id',
				'table' => 'return_reason',
				'field' => 'return_reason_id'
			],
			[
				'key'   => 'return_action_id',
				'table' => 'return_action',
				'field' => 'return_action_id'
			],
			[
				'key'   => 'return_status_id',
				'table' => 'return_status',
				'field' => 'return_status_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'return_action',
		'field' => [
			[
				'name' => 'return_action_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			]
		],
		'primary' => [
			'return_action_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'return_history',
		'field' => [
			[
				'name' => 'return_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'return_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'notify',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'return_history_id'
		],
		'foreign' => [
			[
				'key'   => 'return_id',
				'table' => 'return',
				'field' => 'return_id'
			],
			[
				'key'   => 'return_status_id',
				'table' => 'return_status',
				'field' => 'return_status_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'return_reason',
		'field' => [
			[
				'name' => 'return_reason_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			]
		],
		'primary' => [
			'return_reason_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'return_status',
		'field' => [
			[
				'name' => 'return_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'return_status_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'review',
		'field' => [
			[
				'name' => 'review_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'author',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'text',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'rating',
				'type' => 'int(1)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'review_id'
		],
		'foreign' => [
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'index' => [
			[
				'name' => 'product_id',
				'key' => [
					'product_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'startup',
		'field' => [
			[
				'name' => 'startup_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'action',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'startup_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'statistics',
		'field' => [
			[
				'name' => 'statistics_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,4)',
				'not_null' => true
			]
		],
		'primary' => [
			'statistics_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'session',
		'field' => [
			[
				'name' => 'session_id',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'data',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'expire',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'session_id'
		],
		'index' => [
			[
				'name' => 'expire',
				'key' => [
					'expire'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'setting',
		'field' => [
			[
				'name' => 'setting_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'key',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'serialized',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => 0
			]
		],
		'primary' => [
			'setting_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'stock_status',
		'field' => [
			[
				'name' => 'stock_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'stock_status_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'store',
		'field' => [
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'url',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'store_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'subscription',
		'field' => [
			[
				'name' => 'subscription_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'payment_address_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'payment_method',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'shipping_address_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'shipping_method',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'quantity',
				'type' => 'int(4)',
				'not_null' => true
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'trial_cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_duration',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_remaining',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'trial_status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'cycle',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'duration',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'remaining',
				'type' => 'smallint(6)',
				'not_null' => true
			],
			[
				'name' => 'date_next',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'subscription_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'affiliate_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'commission',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'marketing_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'tracking',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'currency_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'forwarded_ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'accept_language',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'subscription_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			],
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			],
			[
				'key'   => 'order_product_id',
				'table' => 'order_product',
				'field' => 'order_product_id'
			],
			[
				'key'   => 'subscription_plan_id',
				'table' => 'subscription_plan',
				'field' => 'subscription_plan_id'
			],
			[
				'key'   => 'subscription_status_id',
				'table' => 'subscription_status',
				'field' => 'subscription_status_id'
			]
		],
		'index' => [
			[
				'name' => 'order_id',
				'key' => [
					'order_id'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'subscription_history',
		'field' => [
			[
				'name' => 'subscription_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'subscription_status_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'notify',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'comment',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'subscription_history_id'
		],
		'foreign' => [
			[
				'key'   => 'subscription_id',
				'table' => 'subscription',
				'field' => 'subscription_id'
			],
			[
				'key'   => 'subscription_status_id',
				'table' => 'subscription_status',
				'field' => 'subscription_status_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'subscription_plan',
		'field' => [
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)',
				'not_null' => true
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'trial_duration',
				'type' => 'int(10)',
				'not_null' => true
			],
			[
				'name' => 'trial_cycle',
				'type' => 'int(10)',
				'not_null' => true
			],
			[
				'name' => 'trial_status',
				'type' => 'tinyint(4)',
				'not_null' => true
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')',
				'not_null' => true
			],
			[
				'name' => 'duration',
				'type' => 'int(10)',
				'not_null' => true
			],
			[
				'name' => 'cycle',
				'type' => 'int(10)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'subscription_plan_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'subscription_plan_description',
		'field' => [
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'subscription_plan_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'subscription_status',
		'field' => [
			[
				'name' => 'subscription_status_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'subscription_status_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'tax_class',
		'field' => [
			[
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'description',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'tax_class_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'tax_rate',
		'field' => [
			[
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'rate',
				'type' => 'decimal(15,4)',
				'not_null' => true,
				'default' => '0.0000'
			],
			[
				'name' => 'type',
				'type' => 'char(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'tax_rate_id'
		],
		'foreign' => [
			[
				'key'   => 'geo_zone_id',
				'table' => 'geo_zone',
				'field' => 'geo_zone_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'tax_rate_to_customer_group',
		'field' => [
			[
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)',
				'not_null' => true
			]
		],
		'primary' => [
			'tax_rate_id',
			'customer_group_id'
		],
		'foreign' => [
			[
				'key'   => 'tax_rate_id',
				'table' => 'tax_rate',
				'field' => 'tax_rate_id'
			],
			[
				'key'   => 'customer_group_id',
				'table' => 'customer_group',
				'field' => 'customer_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'tax_rule',
		'field' => [
			[
				'name' => 'tax_rule_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'tax_class_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'tax_rate_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'based',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'priority',
				'type' => 'int(5)',
				'not_null' => true,
				'default' => '1'
			]
		],
		'primary' => [
			'tax_rule_id'
		],
		'foreign' => [
			[
				'key'   => 'tax_class_id',
				'table' => 'tax_class',
				'field' => 'tax_class_id'
			],
			[
				'key'   => 'tax_rate_id',
				'table' => 'tax_rate',
				'field' => 'tax_rate_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'theme',
		'field' => [
			[
				'name' => 'theme_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'mediumtext',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'theme_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'translation',
		'field' => [
			[
				'name' => 'translation_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'translation_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'upload',
		'field' => [
			[
				'name' => 'upload_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'filename',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'upload_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'seo_url',
		'field' => [
			[
				'name' => 'seo_url_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'store_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'value',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)',
				'not_null' => true
			]
		],
		'primary' => [
			'seo_url_id'
		],
		'foreign' => [
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'index' => [
			[
				'name' => 'keyword',
				'key' => [
					'keyword'
				]
			],
			[
				'name' => 'query',
				'key' => [
					'key',
					'value'
				]
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'user',
		'field' => [
			[
				'name' => 'user_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'user_group_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'username',
				'type' => 'varchar(20)',
				'not_null' => true
			],
			[
				'name' => 'password',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true,
				'default' => ''
			],
			[
				'name' => 'code',
				'type' => 'varchar(40)',
				'not_null' => true,
				'default' => ''
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true,
				'default' => ''
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'user_id'
		],
		'foreign' => [
			[
				'key'   => 'user_group_id',
				'table' => 'user_group',
				'field' => 'user_group_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'user_authorize',
		'field' => [
			[
				'name' => 'user_authorize_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'user_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'token',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'total',
				'type' => 'int(1)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'user_authorize_id'
		],
		'foreign' => [
			[
				'key'   => 'user_id',
				'table' => 'user',
				'field' => 'user_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'user_group',
		'field' => [
			[
				'name' => 'user_group_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'permission',
				'type' => 'text',
				'not_null' => true
			]
		],
		'primary' => [
			'user_group_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'user_login',
		'field' => [
			[
				'name' => 'user_login_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'user_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)',
				'not_null' => true
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'user_login_id'
		],
		'foreign' => [
			[
				'key'   => 'user_id',
				'table' => 'user',
				'field' => 'user_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'voucher',
		'field' => [
			[
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(10)',
				'not_null' => true
			],
			[
				'name' => 'from_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'from_email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'to_name',
				'type' => 'varchar(64)',
				'not_null' => true
			],
			[
				'name' => 'to_email',
				'type' => 'varchar(96)',
				'not_null' => true
			],
			[
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'message',
				'type' => 'text',
				'not_null' => true
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'voucher_id'
		],
		'foreign' => [
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'voucher_history',
		'field' => [
			[
				'name' => 'voucher_history_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'voucher_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'voucher_history_id'
		],
		'foreign' => [
			[
				'key'   => 'voucher_id',
				'table' => 'voucher',
				'field' => 'voucher_id'
			],
			[
				'key'   => 'order_id',
				'table' => 'order',
				'field' => 'order_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'voucher_theme',
		'field' => [
			[
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)',
				'not_null' => true
			]
		],
		'primary' => [
			'voucher_theme_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'voucher_theme_description',
		'field' => [
			[
				'name' => 'voucher_theme_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)',
				'not_null' => true
			]
		],
		'primary' => [
			'voucher_theme_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'weight_class',
		'field' => [
			[
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,8)',
				'not_null' => true,
				'default' => '0.00000000'
			]
		],
		'primary' => [
			'weight_class_id'
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'weight_class_description',
		'field' => [
			[
				'name' => 'weight_class_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'unit',
				'type' => 'varchar(4)',
				'not_null' => true
			]
		],
		'primary' => [
			'weight_class_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'zone',
		'field' => [
			[
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)',
				'not_null' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)',
				'not_null' => true
			],
			[
				'name' => 'status',
				'type' => 'tinyint(1)',
				'not_null' => true,
				'default' => '1'
			]
		],
		'primary' => [
			'zone_id'
		],
		'foreign' => [
			[
				'key'   => 'country_id',
				'table' => 'country',
				'field' => 'country_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	$tables[] = [
		'name' => 'zone_to_geo_zone',
		'field' => [
			[
				'name' => 'zone_to_geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'auto_increment' => true
			],
			[
				'name' => 'country_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'zone_id',
				'type' => 'int(11)',
				'not_null' => true,
				'default' => '0'
			],
			[
				'name' => 'geo_zone_id',
				'type' => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'date_added',
				'type' => 'datetime',
				'not_null' => true
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime',
				'not_null' => true
			]
		],
		'primary' => [
			'zone_to_geo_zone_id'
		],
		'foreign' => [
			[
				'key'   => 'country_id',
				'table' => 'country',
				'field' => 'country_id'
			],
			[
				'key'   => 'zone_id',
				'table' => 'zone',
				'field' => 'zone_id'
			],
			[
				'key'   => 'geo_zone_id',
				'table' => 'geo_zone',
				'field' => 'geo_zone_id'
			]
		],
		'engine' => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_general_ci'
	];

	return $tables;
}
