<?php
/**
 * DB Create
 *
 * @param string $db_driver
 * @param string $db_hostname
 * @param string $db_username
 * @param string $db_password
 * @param string $db_database
 * @param string $db_port
 * @param string $db_prefix
 * @param string $db_ssl_key
 * @param string $db_ssl_cert
 * @param string $db_ssl_ca
 *
 * @return bool
 */
function oc_db_create(string $db_driver, string $db_hostname, string $db_username, string $db_password, string $db_database, string $db_port, string $db_prefix, string $db_ssl_key, string $db_ssl_cert, string $db_ssl_ca): bool {
	try {
		// Database
		$db = new \Opencart\System\Library\DB($db_driver, $db_hostname, $db_username, $db_password, $db_database, $db_port, $db_ssl_key, $db_ssl_cert, $db_ssl_ca);
	} catch (\Exception $e) {
		return false;
	}

	// Set up Database structure
	$tables = oc_db_schema();

	foreach ($tables as $table) {
		$table_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $db_database . "' AND TABLE_NAME = '" . $db_prefix . $table['name'] . "'");

		if ($table_query->num_rows) {
			$db->query("DROP TABLE `" . $db_prefix . $table['name'] . "`");
		}

		$sql = "CREATE TABLE `" . $db_prefix . $table['name'] . "` (" . "\n";

		foreach ($table['field'] as $field) {
			$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
		}

		if (isset($table['primary'])) {
			$primary_data = [];

			foreach ($table['primary'] as $primary) {
				$primary_data[] = "`" . $primary . "`";
			}

			$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
		}

		if (isset($table['index'])) {
			foreach ($table['index'] as $index) {
				$index_data = [];

				foreach ($index['key'] as $key) {
					$index_data[] = "`" . $key . "`";
				}

				$sql .= "  KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
			}
		}

		$sql = rtrim($sql, ",\n") . "\n";
		$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";

		$db->query($sql);
	}

	return true;
}

/**
 * DB Schema
 *
 * @return array<int, array<string, mixed>>
 */
function oc_db_schema() {
	$tables = [];

	$tables[] = [
		'name'  => 'address',
		'field' => [
			[
				'name'           => 'address_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'company',
				'type' => 'varchar(60)'
			],
			[
				'name' => 'address_1',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'address_2',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'city',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'postcode',
				'type' => 'varchar(10)'
			],
			[
				'name'    => 'country_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'zone_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'custom_field',
				'type' => 'text'
			],
			[
				'name'    => 'default',
				'type'    => 'tinyint(1)',
				'default' => '0'
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
				'key'  => [
					'customer_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'address_format',
		'field' => [
			[
				'name'           => 'address_format_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'address_format',
				'type' => 'text'
			]
		],
		'primary' => [
			'address_format_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'api',
		'field' => [
			[
				'name'           => 'api_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'username',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'key',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'api_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'api_ip',
		'field' => [
			[
				'name'           => 'api_ip_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'api_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'api_history',
		'field' => [
			[
				'name'           => 'api_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'api_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'call',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'api_history_id'
		],
		'foreign' => [
			[
				'key'   => 'api_id',
				'table' => 'api',
				'field' => 'api_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'attribute',
		'field' => [
			[
				'name'           => 'attribute_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'attribute_group_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'attribute_description',
		'field' => [
			[
				'name' => 'attribute_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			]
		],
		'primary' => [
			'attribute_id',
			'language_id'
		],
		'foreign' => [
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'attribute_group',
		'field' => [
			[
				'name'           => 'attribute_group_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'attribute_group_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'attribute_group_description',
		'field' => [
			[
				'name' => 'attribute_group_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'banner',
		'field' => [
			[
				'name'           => 'banner_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			]
		],
		'primary' => [
			'banner_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'banner_image',
		'field' => [
			[
				'name'           => 'banner_image_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'banner_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'link',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'antispam',
		'field' => [
			[
				'name'           => 'antispam_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(64)'
			]
		],
		'primary' => [
			'antispam_id'
		],
		'index' => [
			[
				'name' => 'keyword',
				'key'  => [
					'keyword'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article',
		'field' => [
			[
				'name'           => 'article_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'topic_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'author',
				'type' => 'varchar(64)'
			],
			[
				'name'    => 'rating',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'article_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article_comment',
		'field' => [
			[
				'name'           => 'article_comment_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'article_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'parent_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'author',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name'    => 'rating',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'article_comment_id'
		],
		'foreign' => [
			[
				'key'   => 'article_id',
				'table' => 'article',
				'field' => 'article_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'index' => [
			[
				'name' => 'article_id',
				'key'  => [
					'article_id'
				]
			],
			[
				'name' => 'customer_id',
				'key'  => [
					'customer_id'
				]
			],
			[
				'name' => 'parent_id',
				'key'  => [
					'parent_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article_description',
		'field' => [
			[
				'name' => 'article_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'tag',
				'type' => 'text'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'article_id',
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article_rating',
		'field' => [
			[
				'name'           => 'article_rating_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'article_comment_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'article_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'rating',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'article_rating_id'
		],
		'foreign' => [
			[
				'key'   => 'article_comment_id',
				'table' => 'article_comment',
				'field' => 'article_comment_id'
			],
			[
				'key'   => 'article_id',
				'table' => 'article',
				'field' => 'article_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			],
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'index' => [
			[
				'name' => 'article_comment_id',
				'key'  => [
					'article_comment_id'
				]
			],
			[
				'name' => 'article_id',
				'key'  => [
					'article_id'
				]
			],
			[
				'name' => 'store_id',
				'key'  => [
					'store_id'
				]
			],
			[
				'name' => 'customer_id',
				'key'  => [
					'customer_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article_to_layout',
		'field' => [
			[
				'name' => 'article_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'article_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'article_id',
				'table' => 'article',
				'field' => 'article_id'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'article_to_store',
		'field' => [
			[
				'name' => 'article_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'article_id',
			'store_id',
		],
		'foreign' => [
			[
				'key'   => 'article_id',
				'table' => 'article',
				'field' => 'article_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'topic',
		'field' => [
			[
				'name'           => 'topic_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'topic_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'topic_description',
		'field' => [
			[
				'name' => 'topic_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'topic_id',
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'topic_to_layout',
		'field' => [
			[
				'name' => 'topic_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'topic_id',
			'store_id'
		],
		'foreign' => [
			[
				'key'   => 'topic_id',
				'table' => 'topic',
				'field' => 'topic_id'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'topic_to_store',
		'field' => [
			[
				'name' => 'topic_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'topic_id',
			'store_id',
		],
		'foreign' => [
			[
				'key'   => 'topic_id',
				'table' => 'topic',
				'field' => 'topic_id'
			],
			[
				'key'   => 'store_id',
				'table' => 'store',
				'field' => 'store_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'cart',
		'field' => [
			[
				'name'           => 'cart_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'session_id',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'subscription_plan_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'option',
				'type' => 'text'
			],
			[
				'name' => 'quantity',
				'type' => 'int(5)'
			],
			[
				'name' => 'override',
				'type' => 'text'
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'cart_id'
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
				'key'  => [
					'customer_id',
					'session_id',
					'product_id',
					'subscription_plan_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category',
		'field' => [
			[
				'name'           => 'category_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'parent_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'category_id'
		],
		'index' => [
			[
				'name' => 'parent_id',
				'key'  => [
					'parent_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category_description',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category_filter',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'filter_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category_path',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'path_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'level',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category_to_layout',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'category_to_store',
		'field' => [
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'category_id',
			'store_id',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'country',
		'field' => [
			[
				'name'           => 'country_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'iso_code_2',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'iso_code_3',
				'type' => 'varchar(3)'
			],
			[
				'name'    => 'address_format_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'postcode_required',
				'type' => 'tinyint(1)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '1'
			]
		],
		'primary' => [
			'country_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'country_description',
		'field' => [
			[
				'name' => 'country_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'country_id',
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'coupon',
		'field' => [
			[
				'name'           => 'coupon_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(20)'
			],
			[
				'name' => 'type',
				'type' => 'char(1)'
			],
			[
				'name' => 'discount',
				'type' => 'decimal(15,4)'
			],
			[
				'name'    => 'logged',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'shipping',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'total',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'date_start',
				'type' => 'date'
			],
			[
				'name' => 'date_end',
				'type' => 'date'
			],
			[
				'name'    => 'uses_total',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'uses_customer',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'coupon_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'coupon_category',
		'field' => [
			[
				'name' => 'coupon_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'coupon_history',
		'field' => [
			[
				'name'           => 'coupon_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'coupon_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'coupon_product',
		'field' => [
			[
				'name'           => 'coupon_product_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'coupon_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'cron',
		'field' => [
			[
				'name'           => 'cron_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'cycle',
				'type' => 'varchar(12)'
			],
			[
				'name' => 'action',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'cron_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'currency',
		'field' => [
			[
				'name'           => 'currency_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(3)'
			],
			[
				'name' => 'symbol_left',
				'type' => 'varchar(12)'
			],
			[
				'name' => 'symbol_right',
				'type' => 'varchar(12)'
			],
			[
				'name'    => 'decimal_place',
				'type'    => 'int(1)',
				'default' => '2'
			],
			[
				'name' => 'value',
				'type' => 'double(15,8)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'currency_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer',
		'field' => [
			[
				'name'           => 'customer_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'customer_group_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'language_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'password',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'custom_field',
				'type' => 'text'
			],
			[
				'name'    => 'newsletter',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'safe',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'commenter',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'token',
				'type' => 'text'
			],
			[
				'name' => 'code',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'index' => [
			[
				'name' => 'email',
				'key'  => [
					'email'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_activity',
		'field' => [
			[
				'name'           => 'customer_activity_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'data',
				'type' => 'text'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_affiliate',
		'field' => [
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'company',
				'type' => 'varchar(60)'
			],
			[
				'name' => 'website',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'tracking',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'balance',
				'type' => 'decimal(15,4)'
			],
			[
				'name'    => 'commission',
				'type'    => 'decimal(4,2)',
				'default' => '0.00'
			],
			[
				'name' => 'tax',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'payment_method',
				'type' => 'varchar(6)'
			],
			[
				'name' => 'cheque',
				'type' => 'varchar(100)'
			],
			[
				'name' => 'paypal',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'bank_name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'bank_branch_number',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'bank_swift_code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'bank_account_name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'bank_account_number',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'custom_field',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_affiliate_report',
		'field' => [
			[
				'name'           => 'customer_affiliate_report_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_approval',
		'field' => [
			[
				'name'           => 'customer_approval_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'type',
				'type' => 'varchar(9)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_authorize',
		'field' => [
			[
				'name'           => 'customer_authorize_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'token',
				'type' => 'varchar(96)'
			],
			[
				'name'    => 'total',
				'type'    => 'int(1)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_expire',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'customer_authorize_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_group',
		'field' => [
			[
				'name'           => 'customer_group_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'approval',
				'type'    => 'int(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'customer_group_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_group_description',
		'field' => [
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'description',
				'type' => 'text'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_history',
		'field' => [
			[
				'name'           => 'customer_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'customer_history_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_login',
		'field' => [
			[
				'name'           => 'customer_login_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name'    => 'total',
				'type'    => 'int(4)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'customer_login_id'
		],
		'index' => [
			[
				'name' => 'email',
				'key'  => [
					'email'
				]
			],
			[
				'name' => 'ip',
				'key'  => [
					'ip'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_ip',
		'field' => [
			[
				'name'           => 'customer_ip_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
				'key'  => [
					'ip'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_online',
		'field' => [
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'url',
				'type' => 'text'
			],
			[
				'name' => 'referer',
				'type' => 'text'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'ip'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_reward',
		'field' => [
			[
				'name'           => 'customer_reward_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name'    => 'points',
				'type'    => 'int(8)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_token',
		'field' => [
			[
				'name'           => 'customer_token_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'code',
				'type' => 'text'
			],
			[
				'name' => 'type',
				'type' => 'varchar(10)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'customer_token_id'
		],
		'foreign' => [
			[
				'key'   => 'customer_id',
				'table' => 'customer',
				'field' => 'customer_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_transaction',
		'field' => [
			[
				'name'           => 'customer_transaction_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'amount',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_search',
		'field' => [
			[
				'name'           => 'customer_search_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'sub_category',
				'type' => 'tinyint(1)'
			],
			[
				'name' => 'description',
				'type' => 'tinyint(1)'
			],
			[
				'name' => 'products',
				'type' => 'int(11)'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'customer_wishlist',
		'field' => [
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'customer_id',
			'store_id',
			'product_id'
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
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'custom_field',
		'field' => [
			[
				'name'           => 'custom_field_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name' => 'validation',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'location',
				'type' => 'varchar(10)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'custom_field_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'custom_field_customer_group',
		'field' => [
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'required',
				'type'    => 'tinyint(1)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'custom_field_description',
		'field' => [
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'custom_field_value',
		'field' => [
			[
				'name'           => 'custom_field_value_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'custom_field_value_description',
		'field' => [
			[
				'name' => 'custom_field_value_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'custom_field_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'download',
		'field' => [
			[
				'name'           => 'download_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'filename',
				'type' => 'varchar(160)'
			],
			[
				'name' => 'mask',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'download_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'download_description',
		'field' => [
			[
				'name' => 'download_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'download_report',
		'field' => [
			[
				'name'           => 'download_report_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'download_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'event',
		'field' => [
			[
				'name'           => 'event_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'trigger',
				'type' => 'text'
			],
			[
				'name' => 'action',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '1'
			]
		],
		'primary' => [
			'event_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'extension',
		'field' => [
			[
				'name'           => 'extension_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)'
			]
		],
		'primary' => [
			'extension_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'extension_install',
		'field' => [
			[
				'name'           => 'extension_install_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'extension_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'extension_download_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'code',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'version',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'author',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'link',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'extension_path',
		'field' => [
			[
				'name'           => 'extension_path_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'extension_install_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'path',
				'type' => 'varchar(255)'
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
				'key'  => [
					'path'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'filter',
		'field' => [
			[
				'name'           => 'filter_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'filter_description',
		'field' => [
			[
				'name' => 'filter_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
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
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'filter_group',
		'field' => [
			[
				'name'           => 'filter_group_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'filter_group_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'filter_group_description',
		'field' => [
			[
				'name' => 'filter_group_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'gdpr',
		'field' => [
			[
				'name'           => 'gdpr_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name' => 'action',
				'type' => 'varchar(6)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'geo_zone',
		'field' => [
			[
				'name'           => 'geo_zone_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'description',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'geo_zone_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'identifier',
		'field' => [
			[
				'name'           => 'identifier_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(48)'
			],
			[
				'name' => 'validation',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			]
		],
		'primary' => [
			'identifier_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'information',
		'field' => [
			[
				'name'           => 'information_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '1'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'information_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'information_description',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'description',
				'type' => 'mediumtext'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'information_to_layout',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'information_to_store',
		'field' => [
			[
				'name' => 'information_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'language',
		'field' => [
			[
				'name'           => 'language_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(5)'
			],
			[
				'name' => 'locale',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'language_id'
		],
		'index' => [
			[
				'name' => 'name',
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'layout',
		'field' => [
			[
				'name'           => 'layout_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			]
		],
		'primary' => [
			'layout_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'layout_module',
		'field' => [
			[
				'name'           => 'layout_module_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'position',
				'type' => 'varchar(14)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'layout_route',
		'field' => [
			[
				'name'           => 'layout_route_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'layout_id',
				'type' => 'int(11)',
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'length_class',
		'field' => [
			[
				'name'           => 'length_class_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,8)'
			]
		],
		'primary' => [
			'length_class_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'length_class_description',
		'field' => [
			[
				'name' => 'length_class_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'unit',
				'type' => 'varchar(4)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'location',
		'field' => [
			[
				'name'           => 'location_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'address',
				'type' => 'text'
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'open',
				'type' => 'text'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			]
		],
		'primary' => [
			'location_id'
		],
		'index' => [
			[
				'name' => 'name',
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'manufacturer',
		'field' => [
			[
				'name'           => 'manufacturer_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'manufacturer_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'manufacturer_description',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'manufacturer_id',
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'manufacturer_to_layout',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'manufacturer_to_store',
		'field' => [
			[
				'name' => 'manufacturer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'marketing',
		'field' => [
			[
				'name'           => 'marketing_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name'    => 'clicks',
				'type'    => 'int(5)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'marketing_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'marketing_report',
		'field' => [
			[
				'name'           => 'marketing_report_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'marketing_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'menu',
		'field' => [
			[
				'name'           => 'menu_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'type',
				'type' => 'varchar(8)'
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'parent',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'sort_order',
				'type' => 'int(3)'
			]
		],
		'primary' => [
			'menu_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'menu_description',
		'field' => [
			[
				'name' => 'menu_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			]
		],
		'primary' => [
			'menu_id',
			'language_id'
		],
		'foreign' => [
			[
				'key'   => 'language_id',
				'table' => 'language',
				'field' => 'language_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'modification',
		'field' => [
			[
				'name'           => 'modification_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'     => 'extension_install_id',
				'type'     => 'int(11)',
				'not_null' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'author',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'version',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'link',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'xml',
				'type' => 'mediumtext'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'modification_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'module',
		'field' => [
			[
				'name'           => 'module_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'setting',
				'type' => 'text'
			]
		],
		'primary' => [
			'module_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'notification',
		'field' => [
			[
				'name'           => 'notification_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'text',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(11)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'notification_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'option',
		'field' => [
			[
				'name'           => 'option_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'validation',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'option_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'option_description',
		'field' => [
			[
				'name' => 'option_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'option_value',
		'field' => [
			[
				'name'           => 'option_value_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'option_value_description',
		'field' => [
			[
				'name' => 'option_value_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order',
		'field' => [
			[
				'name'           => 'order_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'subscription_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'invoice_no',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'invoice_prefix',
				'type' => 'varchar(26)'
			],
			[
				'name' => 'transaction_id',
				'type' => 'varchar(100)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'store_name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'store_url',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_group_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'custom_field',
				'type' => 'text'
			],
			[
				'name'    => 'payment_address_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'payment_firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'payment_lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'payment_company',
				'type' => 'varchar(60)'
			],
			[
				'name' => 'payment_address_1',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'payment_address_2',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'payment_city',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'payment_postcode',
				'type' => 'varchar(10)'
			],
			[
				'name' => 'payment_country',
				'type' => 'varchar(128)'
			],
			[
				'name'    => 'payment_country_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'payment_zone',
				'type' => 'varchar(128)'
			],
			[
				'name'    => 'payment_zone_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'payment_address_format',
				'type' => 'text'
			],
			[
				'name' => 'payment_custom_field',
				'type' => 'text'
			],
			[
				'name' => 'payment_method',
				'type' => 'text'
			],
			[
				'name' => 'shipping_address_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'shipping_firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'shipping_lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'shipping_company',
				'type' => 'varchar(60)'
			],
			[
				'name' => 'shipping_address_1',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'shipping_address_2',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'shipping_city',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'shipping_postcode',
				'type' => 'varchar(10)'
			],
			[
				'name' => 'shipping_country',
				'type' => 'varchar(128)'
			],
			[
				'name'    => 'shipping_country_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'shipping_zone',
				'type' => 'varchar(128)'
			],
			[
				'name'    => 'shipping_zone_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'shipping_address_format',
				'type' => 'text'
			],
			[
				'name' => 'shipping_custom_field',
				'type' => 'text'
			],
			[
				'name' => 'shipping_method',
				'type' => 'text'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name'    => 'total',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'order_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'affiliate_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'commission',
				'type' => 'decimal(15,4)'
			],
			[
				'name'    => 'marketing_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'tracking',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_code',
				'type' => 'varchar(5)'
			],
			[
				'name' => 'currency_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'currency_code',
				'type' => 'varchar(3)'
			],
			[
				'name'    => 'currency_value',
				'type'    => 'decimal(15,8)',
				'default' => '1.00000000'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'forwarded_ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'accept_language',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
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
		'index' => [
			[
				'name' => 'email',
				'key'  => [
					'email'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_history',
		'field' => [
			[
				'name'           => 'order_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'order_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'notify',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_option',
		'field' => [
			[
				'name'           => 'order_option_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'order_product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_option_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'product_option_value_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_product',
		'field' => [
			[
				'name'           => 'order_product_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'master_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '1'
			],
			[
				'name'    => 'price',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'total',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'tax',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'reward',
				'type'    => 'int(8)',
				'default' => '0'
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
				'key'  => [
					'order_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_subscription',
		'field' => [
			[
				'name'           => 'order_subscription_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'order_product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '1'
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'trial_tax',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name' => 'trial_cycle',
				'type' => 'smallint(6)'
			],
			[
				'name' => 'trial_duration',
				'type' => 'smallint(6)'
			],
			[
				'name'    => 'trial_status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'tax',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name'    => 'cycle',
				'type'    => 'smallint(6)',
				'default' => '1'
			],
			[
				'name'    => 'duration',
				'type'    => 'smallint(6)',
				'default' => '0'
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
				'key'  => [
					'order_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_status',
		'field' => [
			[
				'name'           => 'order_status_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'order_total',
		'field' => [
			[
				'name'           => 'order_total_id',
				'type'           => 'int(10)',
				'auto_increment' => true
			],
			[
				'name' => 'order_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'extension',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'title',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'value',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
				'key'  => [
					'order_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product',
		'field' => [
			[
				'name'           => 'product_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'master_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'location',
				'type' => 'varchar(128)'
			],
			[
				'name'    => 'variant',
				'type'    => 'text',
				'default' => ''
			],
			[
				'name'    => 'override',
				'type'    => 'text',
				'default' => ''
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '0'
			],
			[
				'name'    => 'stock_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'manufacturer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'shipping',
				'type'    => 'tinyint(1)',
				'default' => '1'
			],
			[
				'name'    => 'price',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'points',
				'type'    => 'int(8)',
				'default' => '0'
			],
			[
				'name'    => 'tax_class_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'date_available',
				'type' => 'date'
			],
			[
				'name'    => 'weight',
				'type'    => 'decimal(15,8)',
				'default' => '0.00000000'
			],
			[
				'name'    => 'weight_class_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'length',
				'type'    => 'decimal(15,8)',
				'default' => '0.00000000'
			],
			[
				'name'    => 'width',
				'type'    => 'decimal(15,8)',
				'default' => '0.00000000'
			],
			[
				'name'    => 'height',
				'type'    => 'decimal(15,8)',
				'default' => '0.00000000'
			],
			[
				'name'    => 'length_class_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'subtract',
				'type'    => 'tinyint(1)',
				'default' => '1'
			],
			[
				'name'    => 'minimum',
				'type'    => 'int(11)',
				'default' => '1'
			],
			[
				'name'    => 'sales',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'rating',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_attribute',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'attribute_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'text',
				'type' => 'text'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_code',
		'field' => [
			[
				'name'           => 'product_code_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'identifier_id',
				'type' => 'varchar(11)'
			],
			[
				'name' => 'value',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'product_code_id'
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
				'name' => 'identifier_id',
				'key'  => [
					'identifier_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_description',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'tag',
				'type' => 'text'
			],
			[
				'name' => 'meta_title',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_description',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'meta_keyword',
				'type' => 'varchar(255)'
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_discount',
		'field' => [
			[
				'name'           => 'product_discount_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '0'
			],
			[
				'name'    => 'priority',
				'type'    => 'int(5)',
				'default' => '1'
			],
			[
				'name'    => 'price',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name'    => 'type',
				'type'    => 'char(1)',
				'default' => 'P'
			],
			[
				'name'    => 'special',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_start',
				'type' => 'date'
			],
			[
				'name' => 'date_end',
				'type' => 'date'
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
				'key'  => [
					'product_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_filter',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'filter_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_image',
		'field' => [
			[
				'name'           => 'product_image_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'image',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
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
				'key'  => [
					'product_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_option',
		'field' => [
			[
				'name'           => 'product_option_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name'    => 'required',
				'type'    => 'tinyint(1)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_option_value',
		'field' => [
			[
				'name'           => 'product_option_value_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_option_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'option_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'option_value_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(3)',
				'default' => '0'
			],
			[
				'name'    => 'subtract',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'price',
				'type' => 'decimal(15,4)'
			],
			[
				'name' => 'price_prefix',
				'type' => 'varchar(1)'
			],
			[
				'name'    => 'points',
				'type'    => 'int(8)',
				'default' => '0'
			],
			[
				'name' => 'points_prefix',
				'type' => 'varchar(1)'
			],
			[
				'name' => 'weight',
				'type' => 'decimal(15,8)'
			],
			[
				'name' => 'weight_prefix',
				'type' => 'varchar(1)'
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
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_subscription',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_related',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'related_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_report',
		'field' => [
			[
				'name'           => 'product_report_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'country',
				'type' => 'varchar(2)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_reward',
		'field' => [
			[
				'name'           => 'product_reward_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'product_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_group_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'points',
				'type'    => 'int(8)',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_to_category',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'category_id',
				'type' => 'int(11)'
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
				'key'  => [
					'category_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_to_download',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'download_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_to_layout',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'layout_id',
				'type'    => 'int(11)',
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
			],
			[
				'key'   => 'layout_id',
				'table' => 'layout',
				'field' => 'layout_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_to_store',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'product_viewed',
		'field' => [
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'viewed',
				'type'    => 'int(11)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'return',
		'field' => [
			[
				'name'           => 'return_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name' => 'telephone',
				'type' => 'varchar(32)'
			],
			[
				'name'    => 'product_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'product',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'model',
				'type' => 'varchar(64)'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '0'
			],
			[
				'name'    => 'opened',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'return_reason_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'return_action_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'return_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name' => 'date_ordered',
				'type' => 'date'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'return_action',
		'field' => [
			[
				'name'           => 'return_action_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'language_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'return_history',
		'field' => [
			[
				'name'           => 'return_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'return_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'return_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'notify',
				'type' => 'tinyint(1)'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'return_reason',
		'field' => [
			[
				'name'           => 'return_reason_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'language_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(128)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'return_status',
		'field' => [
			[
				'name'           => 'return_status_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'language_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'review',
		'field' => [
			[
				'name'           => 'review_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'product_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'customer_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'author',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'text',
				'type' => 'text'
			],
			[
				'name'    => 'rating',
				'type'    => 'int(1)',
				'default' => '0'

			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
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
				'key'  => [
					'product_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'startup',
		'field' => [
			[
				'name'           => 'startup_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'action',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'startup_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'statistics',
		'field' => [
			[
				'name'           => 'statistics_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'value',
				'type' => 'decimal(15,4)'
			]
		],
		'primary' => [
			'statistics_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'session',
		'field' => [
			[
				'name' => 'session_id',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'data',
				'type' => 'text'
			],
			[
				'name' => 'expire',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'session_id'
		],
		'index' => [
			[
				'name' => 'expire',
				'key'  => [
					'expire'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'setting',
		'field' => [
			[
				'name'           => 'setting_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'key',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name'    => 'serialized',
				'type'    => 'tinyint(1)',
				'default' => '0'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'stock_status',
		'field' => [
			[
				'name'           => 'stock_status_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'store',
		'field' => [
			[
				'name'           => 'store_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'url',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'store_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription',
		'field' => [
			[
				'name'           => 'subscription_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'customer_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'payment_address_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'payment_method',
				'type' => 'text'
			],
			[
				'name'    => 'shipping_address_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'shipping_method',
				'type' => 'text'
			],
			[
				'name'    => 'subscription_plan_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'trial_tax',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name'    => 'trial_cycle',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name'    => 'trial_duration',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name'    => 'trial_remaining',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name'    => 'trial_status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'tax',
				'type' => 'decimal(10,4)'
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name'    => 'cycle',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name'    => 'duration',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name'    => 'remaining',
				'type'    => 'smallint(6)',
				'default' => '0'
			],
			[
				'name' => 'date_next',
				'type' => 'datetime'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name'    => 'subscription_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'language',
				'type' => 'varchar(5)'
			],
			[
				'name' => 'currency_code',
				'type' => 'varchar(3)'
			],
			[
				'name'    => 'currency_value',
				'type'    => 'decimal(15,8)',
				'default' => '1.00000000'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
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
				'key'  => [
					'order_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_history',
		'field' => [
			[
				'name'           => 'subscription_history_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'subscription_status_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'notify',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_log',
		'field' => [
			[
				'name'           => 'subscription_log_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(128)'
			],
			[
				'name' => 'description',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'subscription_log_id'
		],
		'foreign' => [
			[
				'key'   => 'subscription_id',
				'table' => 'subscription',
				'field' => 'subscription_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_product',
		'field' => [
			[
				'name'           => 'subscription_product_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'order_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name'    => 'order_product_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'model',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'quantity',
				'type'    => 'int(4)',
				'default' => '0'
			],
			[
				'name' => 'trial_price',
				'type' => 'decimal(10,4)'
			],
			[
				'name'    => 'trial_tax',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name' => 'price',
				'type' => 'decimal(10,4)'
			],
			[
				'name'    => 'tax',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			]
		],
		'primary' => [
			'subscription_product_id'
		],
		'foreign' => [
			[
				'key'   => 'subscription_id',
				'table' => 'subscription',
				'field' => 'subscription_id'
			],
			[
				'key'   => 'product_id',
				'table' => 'product',
				'field' => 'product_id'
			]
		],
		'index' => [
			[
				'name' => 'subscription_id',
				'key'  => [
					'subscription_id'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_option',
		'field' => [
			[
				'name'           => 'subscription_option_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'subscription_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'subscription_product_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'product_option_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'product_option_value_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name' => 'type',
				'type' => 'varchar(32)'
			]
		],
		'primary' => [
			'subscription_option_id'
		],
		'foreign' => [
			[
				'key'   => 'subscription_id',
				'table' => 'subscription',
				'field' => 'subscription_id'
			],
			[
				'key'   => 'subscription_product_id',
				'table' => 'subscription_product',
				'field' => 'subscription_product_id'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_plan',
		'field' => [
			[
				'name'           => 'subscription_plan_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'trial_frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name'    => 'trial_duration',
				'type'    => 'int(10)',
				'default' => '0'
			],
			[
				'name'    => 'trial_cycle',
				'type'    => 'int(10)',
				'default' => '0'
			],
			[
				'name'    => 'trial_status',
				'type'    => 'tinyint(4)',
				'default' => '0'
			],
			[
				'name' => 'frequency',
				'type' => 'enum(\'day\',\'week\',\'semi_month\',\'month\',\'year\')'
			],
			[
				'name'    => 'duration',
				'type'    => 'int(10)',
				'default' => '0'
			],
			[
				'name'    => 'cycle',
				'type'    => 'int(10)',
				'default' => '0'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'subscription_plan_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_plan_description',
		'field' => [
			[
				'name' => 'subscription_plan_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'subscription_status',
		'field' => [
			[
				'name'           => 'subscription_status_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'task',
		'field' => [
			[
				'name'           => 'task_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'action',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'args',
				'type' => 'text'
			],
			[
				'name' => 'response',
				'type' => 'text'
			],
			[
				'name' => 'status',
				'type' => 'enum(\'pending\',\'processing\',\'paused\',\'complete\',\'failed\')'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_modified',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'task_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'task_log',
		'field' => [
			[
				'name'           => 'task_log_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'comment',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'task_log_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'tax_class',
		'field' => [
			[
				'name'           => 'tax_class_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'description',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'tax_class_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'tax_rate',
		'field' => [
			[
				'name'           => 'tax_rate_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'geo_zone_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'name',
				'type' => 'varchar(32)'
			],
			[
				'name'    => 'rate',
				'type'    => 'decimal(15,4)',
				'default' => '0.0000'
			],
			[
				'name' => 'type',
				'type' => 'char(1)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'tax_rate_to_customer_group',
		'field' => [
			[
				'name' => 'tax_rate_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'customer_group_id',
				'type' => 'int(11)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'tax_rule',
		'field' => [
			[
				'name'           => 'tax_rule_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'tax_class_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'tax_rate_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'based',
				'type' => 'varchar(10)'
			],
			[
				'name'    => 'priority',
				'type'    => 'int(5)',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'theme',
		'field' => [
			[
				'name'           => 'theme_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'code',
				'type' => 'mediumtext'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'translation',
		'field' => [
			[
				'name'           => 'translation_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'route',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'value',
				'type' => 'text'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'upload',
		'field' => [
			[
				'name'           => 'upload_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'filename',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'upload_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'seo_url',
		'field' => [
			[
				'name'           => 'seo_url_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'store_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'value',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(768)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
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
				'name' => 'store',
				'key'  => [
					'store_id'
				]
			],
			[
				'name' => 'language',
				'key'  => [
					'language_id'
				]
			],
			[
				'name' => 'keyword',
				'key'  => [
					'keyword'
				]
			],
			[
				'name' => 'query',
				'key'  => [
					'key',
					'value'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'seo_regex',
		'field' => [
			[
				'name'           => 'seo_regex_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'key',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'match',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'replace',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'keyword',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'value',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'sort_order',
				'type'    => 'int(3)',
				'default' => '0'
			]
		],
		'primary' => [
			'seo_regex_id'
		],
		'index' => [
			[
				'name' => 'sort_order',
				'key'  => [
					'sort_order'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'user',
		'field' => [
			[
				'name'           => 'user_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'user_group_id',
				'type'    => 'int(11)',
				'default' => '0'
			],
			[
				'name' => 'username',
				'type' => 'varchar(20)'
			],
			[
				'name' => 'password',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'firstname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'lastname',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'email',
				'type' => 'varchar(96)'
			],
			[
				'name'    => 'image',
				'type'    => 'varchar(255)',
				'default' => ''
			],
			[
				'name'    => 'ip',
				'type'    => 'varchar(40)',
				'default' => ''
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'user_authorize',
		'field' => [
			[
				'name'           => 'user_authorize_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'user_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'token',
				'type' => 'varchar(96)'
			],
			[
				'name'    => 'total',
				'type'    => 'int(1)',
				'default' => '0'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
				'default' => '0'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			],
			[
				'name' => 'date_expire',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'user_group',
		'field' => [
			[
				'name'           => 'user_group_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'name',
				'type' => 'varchar(64)'
			],
			[
				'name' => 'permission',
				'type' => 'text'
			]
		],
		'primary' => [
			'user_group_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'user_login',
		'field' => [
			[
				'name'           => 'user_login_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'user_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'ip',
				'type' => 'varchar(40)'
			],
			[
				'name' => 'user_agent',
				'type' => 'varchar(255)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'user_token',
		'field' => [
			[
				'name'           => 'user_token_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'user_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'code',
				'type' => 'text'
			],
			[
				'name' => 'type',
				'type' => 'varchar(10)'
			],
			[
				'name' => 'date_added',
				'type' => 'datetime'
			]
		],
		'primary' => [
			'user_token_id'
		],
		'foreign' => [
			[
				'key'   => 'user_id',
				'table' => 'user',
				'field' => 'user_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'weight_class',
		'field' => [
			[
				'name'           => 'weight_class_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name'    => 'value',
				'type'    => 'decimal(15,8)',
				'default' => '0.00000000'
			]
		],
		'primary' => [
			'weight_class_id'
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'weight_class_description',
		'field' => [
			[
				'name' => 'weight_class_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'title',
				'type' => 'varchar(32)'
			],
			[
				'name' => 'unit',
				'type' => 'varchar(4)'
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'zone',
		'field' => [
			[
				'name'           => 'zone_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'country_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'code',
				'type' => 'varchar(32)'
			],
			[
				'name'    => 'status',
				'type'    => 'tinyint(1)',
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
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'zone_description',
		'field' => [
			[
				'name' => 'zone_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'language_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'name',
				'type' => 'varchar(255)'
			]
		],
		'primary' => [
			'zone_id',
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
				'key'  => [
					'name'
				]
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	$tables[] = [
		'name'  => 'zone_to_geo_zone',
		'field' => [
			[
				'name'           => 'zone_to_geo_zone_id',
				'type'           => 'int(11)',
				'auto_increment' => true
			],
			[
				'name' => 'geo_zone_id',
				'type' => 'int(11)'
			],
			[
				'name' => 'country_id',
				'type' => 'int(11)'
			],
			[
				'name'    => 'zone_id',
				'type'    => 'int(11)',
				'default' => '0'
			]
		],
		'primary' => [
			'zone_to_geo_zone_id'
		],
		'foreign' => [
			[
				'key'   => 'geo_zone_id',
				'table' => 'geo_zone',
				'field' => 'geo_zone_id'
			],
			[
				'key'   => 'country_id',
				'table' => 'country',
				'field' => 'country_id'
			],
			[
				'key'   => 'zone_id',
				'table' => 'zone',
				'field' => 'zone_id'
			]
		],
		'engine'  => 'InnoDB',
		'charset' => 'utf8mb4',
		'collate' => 'utf8mb4_unicode_ci'
	];

	return $tables;
}
