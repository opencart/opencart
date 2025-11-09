<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade18
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade18 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		try {
			$menu_query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "menu`");

			if (!$menu_query->row['total']) {
				$results = [];

				// Catalog
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Categories'
						]
					],
					'code'             => 'category',
					'type'             => 'link',
					'route'            => 'catalog/category',
					'parent'           => 'catalog',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Products'
						]
					],
					'code'             => 'product',
					'type'             => 'link',
					'route'            => 'catalog/product',
					'parent'           => 'catalog',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Subscription Plans'
						]
					],
					'code'             => 'subscription_plan',
					'type'             => 'link',
					'route'            => 'catalog/subscription_plan',
					'parent'           => 'catalog',
					'sort_order'       => 3
				];

				// Filters
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Filters'
						]
					],
					'code'             => 'filter',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'catalog',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Filters'
						]
					],
					'code'             => 'filter_2',
					'type'             => 'link',
					'route'            => 'catalog/filter',
					'parent'           => 'filter',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Filter Groups'
						]
					],
					'code'             => 'filter_group',
					'type'             => 'link',
					'route'            => 'catalog/filter_group',
					'parent'           => 'filter',
					'sort_order'       => 2
				];

				// Attributes
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Attributes'
						]
					],
					'code'             => 'attribute',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'catalog',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Attributes'
						]
					],
					'code'             => 'attribute_2',
					'type'             => 'link',
					'route'            => 'catalog/attribute',
					'parent'           => 'attribute',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Attribute Group'
						]
					],
					'code'             => 'attribute_group',
					'type'             => 'link',
					'route'            => 'catalog/attribute_group',
					'parent'           => 'attribute',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Options'
						]
					],
					'code'             => 'option',
					'type'             => 'link',
					'route'            => 'catalog/option',
					'parent'           => 'catalog',
					'sort_order'       => 6
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Manufacturers'
						]
					],
					'code'             => 'manufacturer',
					'type'             => 'link',
					'route'            => 'catalog/manufacturer',
					'parent'           => 'catalog',
					'sort_order'       => 7
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Downloads'
						]
					],
					'code'             => 'manufacturer',
					'type'             => 'link',
					'route'            => 'catalog/download',
					'parent'           => 'catalog',
					'sort_order'       => 8
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Reviews'
						]
					],
					'code'             => 'review',
					'type'             => 'link',
					'route'            => 'catalog/review',
					'parent'           => 'catalog',
					'sort_order'       => 9
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Informations'
						]
					],
					'code'             => 'information',
					'type'             => 'link',
					'route'            => 'catalog/information',
					'parent'           => 'catalog',
					'sort_order'       => 10
				];

				// CMS
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Topics'
						]
					],
					'code'             => 'topic',
					'type'             => 'link',
					'route'            => 'cms/topic',
					'parent'           => 'cms',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Articles'
						]
					],
					'code'             => 'article',
					'type'             => 'link',
					'route'            => 'cms/article',
					'parent'           => 'cms',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Comments'
						]
					],
					'code'             => 'comment',
					'type'             => 'link',
					'route'            => 'cms/comment',
					'parent'           => 'cms',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Anti-Spam'
						]
					],
					'code'             => 'antispam',
					'type'             => 'link',
					'route'            => 'cms/antispam',
					'parent'           => 'cms',
					'sort_order'       => 4
				];

				// Extensions
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Marketplace'
						]
					],
					'code'             => 'marketplace',
					'type'             => 'link',
					'route'            => 'marketplace/marketplace',
					'parent'           => 'extension',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Installer'
						]
					],
					'code'             => 'installer',
					'type'             => 'link',
					'route'            => 'marketplace/marketplace',
					'parent'           => 'extension',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Extensions'
						]
					],
					'code'             => 'extension_2',
					'type'             => 'link',
					'route'            => 'marketplace/extension',
					'parent'           => 'extension',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Modifications'
						]
					],
					'code'             => 'modification',
					'type'             => 'link',
					'route'            => 'marketplace/modification',
					'parent'           => 'extension',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Task'
						]
					],
					'code'             => 'task',
					'type'             => 'link',
					'route'            => 'marketplace/task',
					'parent'           => 'extension',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Static Site Rendering'
						]
					],
					'code'             => 'ssr',
					'type'             => 'link',
					'route'            => 'marketplace/task',
					'parent'           => 'extension',
					'sort_order'       => 6
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Startup'
						]
					],
					'code'             => 'startup',
					'type'             => 'link',
					'route'            => 'marketplace/startup',
					'parent'           => 'extension',
					'sort_order'       => 7
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Events'
						]
					],
					'code'             => 'event',
					'type'             => 'link',
					'route'            => 'marketplace/event',
					'parent'           => 'extension',
					'sort_order'       => 8
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'CRON Jobs'
						]
					],
					'code'             => 'cron',
					'type'             => 'link',
					'route'            => 'marketplace/cron',
					'parent'           => 'extension',
					'sort_order'       => 9
				];

				// Design
				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Layouts'
						]
					],
					'code'             => 'layout',
					'type'             => 'link',
					'route'            => 'design/layout',
					'parent'           => 'design',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Theme Editor'
						]
					],
					'code'             => 'theme',
					'type'             => 'link',
					'route'            => 'design/theme',
					'parent'           => 'design',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Translation'
						]
					],
					'code'             => 'translation',
					'type'             => 'link',
					'route'            => 'design/translation',
					'parent'           => 'design',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Banner'
						]
					],
					'code'             => 'banner',
					'type'             => 'link',
					'route'            => 'design/banner',
					'parent'           => 'design',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'SEO URL'
						]
					],
					'code'             => 'seo_url',
					'type'             => 'link',
					'route'            => 'design/seo_url',
					'parent'           => 'design',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Orders'
						]
					],
					'code'             => 'order',
					'type'             => 'link',
					'route'            => 'sale/order',
					'parent'           => 'sale',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Subscription'
						]
					],
					'code'             => 'subscription',
					'type'             => 'link',
					'route'            => 'sale/subscription',
					'parent'           => 'sale',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Returns'
						]
					],
					'code'             => 'return',
					'type'             => 'link',
					'route'            => 'sale/returns',
					'parent'           => 'sale',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Customers'
						]
					],
					'code'             => 'customer',
					'type'             => 'link',
					'route'            => 'customer/customer',
					'parent'           => 'customer',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Customer Groups'
						]
					],
					'code'             => 'customer_group',
					'type'             => 'link',
					'route'            => 'customer/customer_group',
					'parent'           => 'customer',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Customer Approvals'
						]
					],
					'code'             => 'customer_approval',
					'type'             => 'link',
					'route'            => 'customer/customer_approval',
					'parent'           => 'customer',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'GDPR'
						]
					],
					'code'             => 'gdpr',
					'type'             => 'link',
					'route'            => 'customer/gdpr',
					'parent'           => 'customer',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Custom Field'
						]
					],
					'code'             => 'custom_field',
					'type'             => 'link',
					'route'            => 'customer/custom_field',
					'parent'           => 'customer',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Affiliate'
						]
					],
					'code'             => 'affiliate',
					'type'             => 'link',
					'route'            => 'marketing/affiliate',
					'parent'           => 'marketing',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Marketing'
						]
					],
					'code'             => 'marketing_2',
					'type'             => 'link',
					'route'            => 'marketing/marketing',
					'parent'           => 'marketing',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Coupons'
						]
					],
					'code'             => 'coupon',
					'type'             => 'link',
					'route'            => 'marketing/coupon',
					'parent'           => 'marketing',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Mail'
						]
					],
					'code'             => 'mail',
					'type'             => 'link',
					'route'            => 'marketing/mail',
					'parent'           => 'marketing',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Settings'
						]
					],
					'code'             => 'setting',
					'type'             => 'link',
					'route'            => 'setting/store',
					'parent'           => 'system',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Users'
						]
					],
					'code'             => 'user',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'system',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Users'
						]
					],
					'code'             => 'user_2',
					'type'             => 'link',
					'route'            => 'user/user_2',
					'parent'           => 'user',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'User Groups'
						]
					],
					'code'             => 'user_group',
					'type'             => 'link',
					'route'            => 'user/user_group',
					'parent'           => 'user',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'API'
						]
					],
					'code'             => 'api',
					'type'             => 'link',
					'route'            => 'user/api',
					'parent'           => 'user',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Localisation'
						]
					],
					'code'             => 'localisation',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'system',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Store Locations'
						]
					],
					'code'             => 'location',
					'type'             => 'link',
					'route'            => 'localisation/location',
					'parent'           => 'localisation',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Languages'
						]
					],
					'code'             => 'language',
					'type'             => 'link',
					'route'            => 'localisation/language',
					'parent'           => 'localisation',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Currencies'
						]
					],
					'code'             => 'currency',
					'type'             => 'link',
					'route'            => 'localisation/currency',
					'parent'           => 'localisation',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Identifier'
						]
					],
					'code'             => 'identifier',
					'type'             => 'link',
					'route'            => 'localisation/identifier',
					'parent'           => 'localisation',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Stock Status'
						]
					],
					'code'             => 'stock_status',
					'type'             => 'link',
					'route'            => 'localisation/stock_status',
					'parent'           => 'localisation',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Order Status'
						]
					],
					'code'             => 'order_status',
					'type'             => 'link',
					'route'            => 'localisation/order_status',
					'parent'           => 'localisation',
					'sort_order'       => 6
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Subscription Status'
						]
					],
					'code'             => 'subscription_status',
					'type'             => 'link',
					'route'            => 'localisation/subscription_status',
					'parent'           => 'localisation',
					'sort_order'       => 7
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Returns'
						]
					],
					'code'             => 'return_2',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'localisation',
					'sort_order'       => 8
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Return Statuses'
						]
					],
					'code'             => 'return_status',
					'type'             => 'link',
					'route'            => 'localisation/return_status',
					'parent'           => 'return_2',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Return Actions'
						]
					],
					'code'             => 'return_action',
					'type'             => 'link',
					'route'            => 'localisation/return_action',
					'parent'           => 'return_2',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Return Reasons'
						]
					],
					'code'             => 'return_reason',
					'type'             => 'link',
					'route'            => 'localisation/return_reason',
					'parent'           => 'return_2',
					'sort_order'       => 3
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Countries'
						]
					],
					'code'             => 'country',
					'type'             => 'link',
					'route'            => 'localisation/country',
					'parent'           => 'localisation',
					'sort_order'       => 9
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Zones'
						]
					],
					'code'             => 'zone',
					'type'             => 'link',
					'route'            => 'localisation/zone',
					'parent'           => 'localisation',
					'sort_order'       => 10
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Geo Zones'
						]
					],
					'code'             => 'geo_zone',
					'type'             => 'link',
					'route'            => 'localisation/geo_zone',
					'parent'           => 'localisation',
					'sort_order'       => 11
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Taxes'
						]
					],
					'code'             => 'tax',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'localisation',
					'sort_order'       => 12
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Tax Classes'
						]
					],
					'code'             => 'tax_class',
					'type'             => 'link',
					'route'            => 'localisation/tax_class',
					'parent'           => 'tax',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Tax Rates'
						]
					],
					'code'             => 'tax_rate',
					'type'             => 'link',
					'route'            => 'localisation/tax_rate',
					'parent'           => 'tax',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Length Classes'
						]
					],
					'code'             => 'length_class',
					'type'             => 'link',
					'route'            => 'localisation/length_class',
					'parent'           => 'localisation',
					'sort_order'       => 13
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Weight Classes'
						]
					],
					'code'             => 'weight_class',
					'type'             => 'link',
					'route'            => 'localisation/weight_class',
					'parent'           => 'localisation',
					'sort_order'       => 14
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Address Format'
						]
					],
					'code'             => 'address_format',
					'type'             => 'link',
					'route'            => 'localisation/address_format',
					'parent'           => 'localisation',
					'sort_order'       => 15
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Maintenance'
						]
					],
					'code'             => 'maintenance',
					'type'             => 'dropdown',
					'route'            => '',
					'parent'           => 'system',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Admin Menu'
						]
					],
					'code'             => 'menu',
					'type'             => 'link',
					'route'            => 'tool/menu',
					'parent'           => 'maintenance',
					'sort_order'       => 1
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Upgrade'
						]
					],
					'code'             => 'upgrade',
					'type'             => 'link',
					'route'            => 'tool/upgrade',
					'parent'           => 'maintenance',
					'sort_order'       => 2
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Backup &amp; Restore'
						]
					],
					'code'             => 'backup',
					'type'             => 'link',
					'route'            => 'tool/backup',
					'parent'           => 'maintenance',
					'sort_order'       => 4
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Uploads'
						]
					],
					'code'             => 'upload',
					'type'             => 'link',
					'route'            => 'tool/upload',
					'parent'           => 'maintenance',
					'sort_order'       => 5
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Error Log'
						]
					],
					'code'             => 'log',
					'type'             => 'link',
					'route'            => 'tool/log',
					'parent'           => 'maintenance',
					'sort_order'       => 6
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Reports'
						]
					],
					'code'             => 'report_2',
					'type'             => 'link',
					'route'            => 'tool/report',
					'parent'           => 'report',
					'sort_order'       => 7
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Who\'s Online'
						]
					],
					'code'             => 'online',
					'type'             => 'link',
					'route'            => 'tool/online',
					'parent'           => 'report',
					'sort_order'       => 8
				];

				$results[] = [
					'menu_description' => [
						1 => [
							'name' => 'Statistics'
						]
					],
					'code'             => 'statistics',
					'type'             => 'link',
					'route'            => 'tool/statistics',
					'parent'           => 'report',
					'sort_order'       => 9
				];

				$this->load->model('upgrade/upgrade');

				foreach ($results as $result) {
					$menu_description = $result['menu_description'];

					unset($result['menu_description']);

					$menu_id = $this->model_upgrade_upgrade->addRecord('menu', $result);

					foreach ($menu_description as $key => $value) {
						$menu_description_data = [
							'menu_id'     => $menu_id,
							'language_id' => $key,
							'name'        => $value['name']
						];

						$this->model_upgrade_upgrade->addRecord('menu_description', $menu_description_data);
					}
				}
			}
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 18, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_19', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
