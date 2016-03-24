<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		$navigations = array();

		$navigations[] = array(
			'title'		=> $this->language->get('text_dashboard'),
			'url'		=> $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'id'		=> 'dashboard',
			'icon'		=> 'dashboard'
		);
		$navigations['catalog'] = array(
			'title'		=> $this->language->get('text_catalog'),
			'url'		=> '',
			'id'		=> 'catalog',
			'class'		=> '',
			'icon'		=> 'tags',
			'child'		=> array()
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_category'),
			'url'		=> $this->url->link('catalog/category', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_product'),
			'url'		=> $this->url->link('catalog/product', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_recurring'),
			'url'		=> $this->url->link('catalog/recurring', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_filter'),
			'url'		=> $this->url->link('catalog/filter', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child']['attribute'] = array(
			'title'		=> $this->language->get('text_attribute'),
			'child'		=> array()
		);
		$navigations['catalog']['child']['attribute']['child'][] = array(
			'title'		=> $this->language->get('text_attribute'),
			'url'		=> $this->url->link('catalog/attribute', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child']['attribute']['child'][] = array(
			'title'		=> $this->language->get('text_attribute_group'),
			'url'		=> $this->url->link('catalog/attribute_group', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_option'),
			'url'		=> $this->url->link('catalog/option', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_manufacturer'),
			'url'		=> $this->url->link('catalog/manufacturer', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_download'),
			'url'		=> $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_review'),
			'url'		=> $this->url->link('catalog/review', 'token=' . $this->session->data['token'], true)
		);
		$navigations['catalog']['child'][] = array(
			'title'		=> $this->language->get('text_information'),
			'url'		=> $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true)
		);

		$navigations['extension'] = array(
			'title'		=> $this->language->get('text_extension'),
			'id'		=> 'catalog',
			'icon'		=> 'puzzle-piece',
			'child'		=> array()
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_installer'),
			'url'		=> $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_modification'),
			'url'		=> $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_theme'),
			'url'		=> $this->url->link('extension/theme', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_module'),
			'url'		=> $this->url->link('extension/module', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_payment'),
			'url'		=> $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_shipping'),
			'url'		=> $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_total'),
			'url'		=> $this->url->link('extension/total', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_fraud'),
			'url'		=> $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_analytics'),
			'url'		=> $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_captcha'),
			'url'		=> $this->url->link('extension/captcha', 'token=' . $this->session->data['token'], true)
		);
		$navigations['extension']['child'][] = array(
			'title'		=> $this->language->get('text_feed'),
			'url'		=> $this->url->link('extension/feed', 'token=' . $this->session->data['token'], true)
		);
		if ($this->config->get('openbaypro_menu')) {
			$navigations['extension']['child']['openbay'] = array(
				'title'		=> $this->language->get('text_openbay_extension'),
				'child'		=> array()
			);
			$navigations['extension']['child']['openbay']['child'][] = array(
				'title'		=> $this->language->get('text_openbay_dashboard'),
				'url'		=> $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], true)
			);
			$navigations['extension']['child']['openbay']['child'][] = array(
				'title'		=> $this->language->get('text_openbay_orders'),
				'url'		=> $this->url->link('extension/openbay/orderlist', 'token=' . $this->session->data['token'], true)
			);
			$navigations['extension']['child']['openbay']['child'][] = array(
				'title'		=> $this->language->get('text_openbay_items'),
				'url'		=> $this->url->link('extension/openbay/items', 'token=' . $this->session->data['token'], true)
			);
			if ($this->config->get('ebay_status')) {
				$navigations['extension']['child']['openbay']['child']['ebay'] = array(
					'title'		=> $this->language->get('text_openbay_ebay'),
					'child'		=> array()
				);
				$navigations['extension']['child']['openbay']['child']['ebay']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_dashboard'),
					'url'		=> $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['ebay']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_settings'),
					'url'		=> $this->url->link('openbay/ebay/settings', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['ebay']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_links'),
					'url'		=> $this->url->link('openbay/ebay/viewitemlinks', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['ebay']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_order_import'),
					'url'		=> $this->url->link('openbay/ebay/vieworderimport', 'token=' . $this->session->data['token'], true)
				);
			}
			if ($this->config->get('openbay_amazon_status')) {
				$navigations['extension']['child']['openbay']['child']['amazon'] = array(
					'title'		=> $this->language->get('text_openbay_amazon'),
					'child'		=> array()
				);
				$navigations['extension']['child']['openbay']['child']['amazon']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_dashboard'),
					'url'		=> $this->url->link('openbay/amazon', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['amazon']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_settings'),
					'url'		=> $this->url->link('openbay/amazon/settings', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['amazon']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_links'),
					'url'		=> $this->url->link('openbay/amazon/itemlinks', 'token=' . $this->session->data['token'], true)
				);
			}
			if ($this->config->get('openbay_amazonus_status')) {
				$navigations['extension']['child']['openbay']['child']['amazonus'] = array(
					'title'		=> $this->language->get('text_openbay_amazonus'),
					'child'		=> array()
				);
				$navigations['extension']['child']['openbay']['child']['amazonus']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_dashboard'),
					'url'		=> $this->url->link('openbay/amazonus', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['amazonus']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_settings'),
					'url'		=> $this->url->link('openbay/amazonus/settings', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['amazonus']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_links'),
					'url'		=> $this->url->link('openbay/amazonus/itemlinks', 'token=' . $this->session->data['token'], true)
				);
			}
			if ($this->config->get('etsy_status')) {
				$navigations['extension']['child']['openbay']['child']['etsy'] = array(
					'title'		=> $this->language->get('text_openbay_etsy'),
					'child'		=> array()
				);
				$navigations['extension']['child']['openbay']['child']['etsy']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_dashboard'),
					'url'		=> $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['etsy']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_settings'),
					'url'		=> $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], true)
				);
				$navigations['extension']['child']['openbay']['child']['etsy']['child'][] = array(
					'title'		=> $this->language->get('text_openbay_links'),
					'url'		=> $this->url->link('openbay/etsy/itemlinks', 'token=' . $this->session->data['token'], true)
				);
			}
		}

		$navigations['design'] = array(
			'title'		=> $this->language->get('text_design'),
			'id'		=> 'design',
			'icon'		=> 'television',
			'child'		=> array()
		);
		$navigations['design']['child'][] = array(
			'title'		=> $this->language->get('text_layout'),
			'url'		=> $this->url->link('design/layout', 'token=' . $this->session->data['token'], true)
		);
		$navigations['design']['child'][] = array(
			'title'		=> $this->language->get('text_banner'),
			'url'		=> $this->url->link('design/banner', 'token=' . $this->session->data['token'], true)
		);

		$navigations['sale'] = array(
			'title'		=> $this->language->get('text_sale'),
			'id'		=> 'sale',
			'icon'		=> 'shopping-cart',
			'child'		=> array()
		);
		$navigations['sale']['child'][] = array(
			'title'		=> $this->language->get('text_order'),
			'url'		=> $this->url->link('sale/order', 'token=' . $this->session->data['token'], true)
		);
		$navigations['sale']['child'][] = array(
			'title'		=> $this->language->get('text_recurring'),
			'url'		=> $this->url->link('sale/recurring', 'token=' . $this->session->data['token'], true)
		);
		$navigations['sale']['child'][] = array(
			'title'		=> $this->language->get('text_return'),
			'url'		=> $this->url->link('sale/return', 'token=' . $this->session->data['token'], true)
		);
		$navigations['sale']['child']['voucher'] = array(
			'title'		=> $this->language->get('text_return'),
			'child'		=> array()
		);
		$navigations['sale']['child']['voucher']['child'][] = array(
			'title'		=> $this->language->get('text_voucher'),
			'url'		=> $this->url->link('sale/voucher', 'token=' . $this->session->data['token'], true)
		);
		$navigations['sale']['child']['voucher']['child'][] = array(
			'title'		=> $this->language->get('text_voucher_theme'),
			'url'		=> $this->url->link('sale/voucher_theme', 'token=' . $this->session->data['token'], true)
		);
		$navigations['sale']['child']['paypal'] = array(
			'title'		=> $this->language->get('text_paypal'),
			'child'		=> array()
		);
		$navigations['sale']['child']['paypal']['child'][] = array(
			'title'		=> $this->language->get('text_paypal_search'),
			'url'		=> $this->url->link('payment/pp_express/search', 'token=' . $this->session->data['token'], true)
		);

		$navigations['customer'] = array(
			'title'		=> $this->language->get('text_customer'),
			'id'		=> 'customer',
			'icon'		=> 'user',
			'child'		=> array()
		);
		$navigations['customer']['child'][] = array(
			'title'		=> $this->language->get('text_customer'),
			'url'		=> $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true)
		);
		$navigations['customer']['child'][] = array(
			'title'		=> $this->language->get('text_customer_group'),
			'url'		=> $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true)
		);
		$navigations['customer']['child'][] = array(
			'title'		=> $this->language->get('text_custom_field'),
			'url'		=> $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], true)
		);

		$navigations['marketing'] = array(
			'title'		=> $this->language->get('text_marketing'),
			'id'		=> 'marketing',
			'icon'		=> 'share-alt',
			'child'		=> array()
		);
		$navigations['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_marketing'),
			'url'		=> $this->url->link('marketing/marketing', 'token=' . $this->session->data['token'], true)
		);
		$navigations['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_affiliate'),
			'url'		=> $this->url->link('marketing/affiliate', 'token=' . $this->session->data['token'], true)
		);
		$navigations['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_coupon'),
			'url'		=> $this->url->link('marketing/coupon', 'token=' . $this->session->data['token'], true)
		);
		$navigations['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_contact'),
			'url'		=> $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true)
		);

		$navigations['system'] = array(
			'title'		=> $this->language->get('text_system'),
			'id'		=> 'system',
			'icon'		=> 'cog',
			'child'		=> array()
		);
		$navigations['system']['child'][] = array(
			'title'		=> $this->language->get('text_setting'),
			'url'		=> $this->url->link('setting/store', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['users'] = array(
			'title'		=> $this->language->get('text_users'),
			'child'		=> array()
		);
		$navigations['system']['child']['users']['child'][] = array(
			'title'		=> $this->language->get('text_user'),
			'url'		=> $this->url->link('user/user', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['users']['child'][] = array(
			'title'		=> $this->language->get('text_user_group'),
			'url'		=> $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['users']['child'][] = array(
			'title'		=> $this->language->get('text_api'),
			'url'		=> $this->url->link('user/api', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation'] = array(
			'title'		=> $this->language->get('text_localisation'),
			'child'		=> array()
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_location'),
			'url'		=> $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_language'),
			'url'		=> $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_currency'),
			'url'		=> $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_stock_status'),
			'url'		=> $this->url->link('localisation/stock_status', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_order_status'),
			'url'		=> $this->url->link('localisation/order_status', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child']['return'] = array(
			'title'		=> $this->language->get('text_return'),
			'child'		=> array()
		);
		$navigations['system']['child']['localisation']['child']['return']['child'][] = array(
			'title'		=> $this->language->get('text_return_status'),
			'url'		=> $this->url->link('localisation/return_status', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child']['return']['child'][] = array(
			'title'		=> $this->language->get('text_return_action'),
			'url'		=> $this->url->link('localisation/return_action', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child']['return']['child'][] = array(
			'title'		=> $this->language->get('text_return_reason'),
			'url'		=> $this->url->link('localisation/return_reason', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_country'),
			'url'		=> $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_zone'),
			'url'		=> $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_geo_zone'),
			'url'		=> $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child']['tax'] = array(
			'title'		=> $this->language->get('text_tax'),
			'child'		=> array()
		);
		$navigations['system']['child']['localisation']['child']['tax']['child'][] = array(
			'title'		=> $this->language->get('text_tax_class'),
			'url'		=> $this->url->link('localisation/tax_class', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child']['tax']['child'][] = array(
			'title'		=> $this->language->get('text_tax_rate'),
			'url'		=> $this->url->link('localisation/tax_rate', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_length_class'),
			'url'		=> $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['localisation']['child'][] = array(
			'title'		=> $this->language->get('text_weight_class'),
			'url'		=> $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['tools'] = array(
			'title'		=> $this->language->get('text_tools'),
			'child'		=> array()
		);
		$navigations['system']['child']['tools']['child'][] = array(
			'title'		=> $this->language->get('text_upload'),
			'url'		=> $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['tools']['child'][] = array(
			'title'		=> $this->language->get('text_backup'),
			'url'		=> $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true)
		);
		$navigations['system']['child']['tools']['child'][] = array(
			'title'		=> $this->language->get('text_error_log'),
			'url'		=> $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report'] = array(
			'title'		=> $this->language->get('text_reports'),
			'id'		=> 'reports',
			'icon'		=> 'bar-chart',
			'child'		=> array()
		);
		$navigations['report']['child']['sale'] = array(
			'title'		=> $this->language->get('text_sale'),
			'child'		=> array()
		);
		$navigations['report']['child']['sale']['child'][] = array(
			'title'		=> $this->language->get('text_report_sale_order'),
			'url'		=> $this->url->link('report/sale_order', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['sale']['child'][] = array(
			'title'		=> $this->language->get('text_report_sale_tax'),
			'url'		=> $this->url->link('report/sale_tax', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['sale']['child'][] = array(
			'title'		=> $this->language->get('text_report_sale_shipping'),
			'url'		=> $this->url->link('report/sale_shipping', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['sale']['child'][] = array(
			'title'		=> $this->language->get('text_report_sale_return'),
			'url'		=> $this->url->link('report/sale_return', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['sale']['child'][] = array(
			'title'		=> $this->language->get('text_report_sale_coupon'),
			'url'		=> $this->url->link('report/sale_coupon', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['product'] = array(
			'title'		=> $this->language->get('text_product'),
			'child'		=> array()
		);
		$navigations['report']['child']['product']['child'][] = array(
			'title'		=> $this->language->get('text_report_product_viewed'),
			'url'		=> $this->url->link('report/product_viewed', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['product']['child'][] = array(
			'title'		=> $this->language->get('text_report_product_purchased'),
			'url'		=> $this->url->link('report/product_purchased', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['customer'] = array(
			'title'		=> $this->language->get('text_customer'),
			'child'		=> array()
		);
		$navigations['report']['child']['customer']['child'][] = array(
			'title'		=> $this->language->get('text_report_customer_online'),
			'url'		=> $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['customer']['child'][] = array(
			'title'		=> $this->language->get('text_report_customer_activity'),
			'url'		=> $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['customer']['child'][] = array(
			'title'		=> $this->language->get('text_report_customer_order'),
			'url'		=> $this->url->link('report/customer_order', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['customer']['child'][] = array(
			'title'		=> $this->language->get('text_report_customer_reward'),
			'url'		=> $this->url->link('report/customer_reward', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['customer']['child'][] = array(
			'title'		=> $this->language->get('text_report_customer_credit'),
			'url'		=> $this->url->link('report/customer_credit', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['marketing'] = array(
			'title'		=> $this->language->get('text_marketing'),
			'child'		=> array()
		);
		$navigations['report']['child']['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_marketing'),
			'url'		=> $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_report_affiliate'),
			'url'		=> $this->url->link('report/affiliate', 'token=' . $this->session->data['token'], true)
		);
		$navigations['report']['child']['marketing']['child'][] = array(
			'title'		=> $this->language->get('text_report_affiliate_activity'),
			'url'		=> $this->url->link('report/affiliate_activity', 'token=' . $this->session->data['token'], true)
		);

		$data['navigations'] = $navigations;

		return $this->load->view('common/menu', $data);
	}
}
