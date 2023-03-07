<?php
namespace Opencart\Admin\Controller\Setting;
class Setting extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_stores'),
			'href' => $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/setting', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('setting/setting.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token']);

		// General
		$data['config_meta_title'] = $this->config->get('config_meta_title');
		$data['config_meta_description'] = $this->config->get('config_meta_description');
		$data['config_meta_keyword'] = $this->config->get('config_meta_keyword');

		$data['store_url'] = HTTP_CATALOG;

		$data['themes'] = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('theme');

		foreach ($extensions as $extension) {
			$this->load->language('extension/' . $extension['extension'] . '/theme/' . $extension['code'], 'extension');

			$data['themes'][] = [
				'text'  => $this->language->get('extension_heading_title'),
				'value' => $extension['code']
			];
		}

		$data['config_theme'] = $this->config->get('config_theme');

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['config_layout_id'] = $this->config->get('config_layout_id');

		// Store Details
		$data['config_name'] = $this->config->get('config_name');
		$data['config_owner'] = $this->config->get('config_owner');
		$data['config_address'] = $this->config->get('config_address');
		$data['config_geocode'] = $this->config->get('config_geocode');
		$data['config_email'] = $this->config->get('config_email');
		$data['config_telephone'] = $this->config->get('config_telephone');
		$data['config_image'] = $this->config->get('config_image');

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		$data['config_open'] = $this->config->get('config_open');
		$data['config_comment'] = $this->config->get('config_comment');

		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if ($this->config->get('config_location')) {
			$data['config_location'] = $this->config->get('config_location');
		} else {
			$data['config_location'] = [];
		}

		// Localisation
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$data['config_country_id'] = $this->config->get('config_country_id');

		$data['config_zone_id'] = $this->config->get('config_zone_id');

		if ($this->config->has('config_timezone')) {
			$data['config_timezone'] = $this->config->get('config_timezone');
		} else {
			$data['config_timezone'] = 'UTC';
		}

		$data['timezones'] = [];

		$timestamp = date_create('now');

		$timezones = timezone_identifiers_list();

		foreach ($timezones as $timezone) {
			date_timezone_set($timestamp, timezone_open($timezone));

			$hour = ' (' . date_format($timestamp, 'P') . ')';

			$data['timezones'][] = [
				'text'  => $timezone . $hour,
				'value' => $timezone
			];
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language'] = $this->config->get('config_language');

		$data['config_language_admin'] = $this->config->get('config_language_admin');

		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['config_currency'] = $this->config->get('config_currency');

		$data['currency_engines'] = [];

		$this->load->model('setting/extension');

		$extensions = $this->model_setting_extension->getExtensionsByType('currency');

		foreach ($extensions as $extension) {
			if ($this->config->get('currency_' . $extension['code'] . '_status')) {
				$this->load->language('extension/' . $extension['extension'] . '/currency/' . $extension['code'], 'extension');

				$data['currency_engines'][] = [
					'text'  => $this->language->get('extension_heading_title'),
					'value' => $extension['code']
				];
			}
		}

		$data['config_currency_engine'] = $this->config->get('config_currency_engine');
		$data['config_currency_auto'] = $this->config->get('config_currency_auto');

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		$data['config_length_class_id'] = $this->config->get('config_length_class_id');

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$data['config_weight_class_id'] = $this->config->get('config_weight_class_id');

		// Options
		if ($this->config->get('config_product_description_length')) {
			$data['config_product_description_length'] = $this->config->get('config_product_description_length');
		} else {
			$data['config_product_description_length'] = 100;
		}

		if ($this->config->get('config_pagination')) {
			$data['config_pagination'] = $this->config->get('config_pagination');
		} else {
			$data['config_pagination'] = 15;
		}

		$data['config_product_count'] = $this->config->get('config_product_count');

		if ($this->config->get('config_pagination_admin')) {
			$data['config_pagination_admin'] = $this->config->get('config_pagination_admin');
		} else {
			$data['config_pagination_admin'] = 10;
		}

		$data['config_product_report_status'] = $this->config->get('config_product_report_status');

		$data['config_review_status'] = $this->config->get('config_review_status');
		$data['config_review_purchased'] = $this->config->get('config_review_purchased');
		$data['config_review_guest'] = $this->config->get('config_review_guest');

		$data['config_voucher_min'] = $this->config->get('config_voucher_min');
		$data['config_voucher_max'] = $this->config->get('config_voucher_max');

		$data['config_cookie_id'] = $this->config->get('config_cookie_id');

		$data['config_gdpr_id'] = $this->config->get('config_gdpr_id');
		$data['config_gdpr_limit'] = $this->config->get('config_gdpr_limit');

		$data['config_tax'] = $this->config->get('config_tax');
		$data['config_tax_default'] = $this->config->get('config_tax_default');
		$data['config_tax_customer'] = $this->config->get('config_tax_customer');

		$data['config_customer_online'] = $this->config->get('config_customer_online');

		if ($this->config->has('config_customer_online_expire')) {
			$data['config_customer_online_expire'] = $this->config->get('config_customer_online_expire');
		} else {
			$data['config_customer_online_expire'] = 1;
		}

		$data['config_customer_activity'] = $this->config->get('config_customer_activity');
		$data['config_customer_search'] = $this->config->get('config_customer_search');

		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['config_customer_group_id'] = $this->config->get('config_customer_group_id');

		if ($this->config->get('config_customer_group_display')) {
			$data['config_customer_group_display'] = $this->config->get('config_customer_group_display');
		} else {
			$data['config_customer_group_display'] = [];
		}

		$data['config_customer_price'] = $this->config->get('config_customer_price');
		$data['config_telephone_display'] = $this->config->get('config_telephone_display');
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		if ($this->config->has('config_login_attempts')) {
			$data['config_login_attempts'] = $this->config->get('config_login_attempts');
		} else {
			$data['config_login_attempts'] = 5;
		}

		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		$data['config_account_id'] = $this->config->get('config_account_id');

		$data['config_cart_weight'] = $this->config->get('config_cart_weight');
		$data['config_checkout_guest'] = $this->config->get('config_checkout_guest');
		$data['config_checkout_payment_address'] = $this->config->get('config_checkout_payment_address');
		$data['config_checkout_shipping_address'] = $this->config->get('config_checkout_shipping_address');
		$data['config_checkout_id'] = $this->config->get('config_checkout_id');

		if ($this->config->get('config_invoice_prefix')) {
			$data['config_invoice_prefix'] = $this->config->get('config_invoice_prefix');
		} else {
			$data['config_invoice_prefix'] = 'INV-' . date('Y') . '-00';
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['config_order_status_id'] = $this->config->get('config_order_status_id');

		if ($this->config->get('config_processing_status')) {
			$data['config_processing_status'] = $this->config->get('config_processing_status');
		} else {
			$data['config_processing_status'] = [];
		}

		if ($this->config->get('config_complete_status')) {
			$data['config_complete_status'] = $this->config->get('config_complete_status');
		} else {
			$data['config_complete_status'] = [];
		}

		$data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');

		// Subscription
		$this->load->model('localisation/subscription_status');

		$data['subscription_statuses'] = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		$data['config_subscription_status_id'] = $this->config->get('config_subscription_status_id');
		$data['config_subscription_active_status_id'] = $this->config->get('config_subscription_active_status_id');
		$data['config_subscription_suspended_status_id'] = $this->config->get('config_subscription_suspended_status_id');
		$data['config_subscription_expired_status_id'] = $this->config->get('config_subscription_expired_status_id');
		$data['config_subscription_canceled_status_id'] = $this->config->get('config_subscription_canceled_status_id');
		$data['config_subscription_failed_status_id'] = $this->config->get('config_subscription_failed_status_id');
		$data['config_subscription_denied_status_id'] = $this->config->get('config_subscription_denied_status_id');

		// Api
		$this->load->model('user/api');

		$data['apis'] = $this->model_user_api->getApis();

		$data['config_api_id'] = $this->config->get('config_api_id');

		$data['config_stock_display'] = $this->config->get('config_stock_display');
		$data['config_stock_warning'] = $this->config->get('config_stock_warning');
		$data['config_stock_checkout'] = $this->config->get('config_stock_checkout');

		if ($this->config->has('config_affiliate_status')) {
			$data['config_affiliate_status'] = $this->config->get('config_affiliate_status');
		} else {
			$data['config_affiliate_status'] = '';
		}

		$data['config_affiliate_group_id'] = $this->config->get('config_affiliate_group_id');

		if ($this->config->has('config_affiliate_approval')) {
			$data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
		} else {
			$data['config_affiliate_approval'] = '';
		}

		if ($this->config->has('config_affiliate_auto')) {
			$data['config_affiliate_auto'] = $this->config->get('config_affiliate_auto');
		} else {
			$data['config_affiliate_auto'] = '';
		}

		if ($this->config->has('config_affiliate_commission')) {
			$data['config_affiliate_commission'] = $this->config->get('config_affiliate_commission');
		} else {
			$data['config_affiliate_commission'] = '5.00';
		}

		if ($this->config->has('config_affiliate_expire')) {
			$data['config_affiliate_expire'] = $this->config->get('config_affiliate_expire');
		} else {
			$data['config_affiliate_expire'] = 0;
		}

		// Affiliate terms
		$data['config_affiliate_id'] = $this->config->get('config_affiliate_id');

		$this->load->model('localisation/return_status');

		$data['return_statuses'] = $this->model_localisation_return_status->getReturnStatuses();

		$data['config_return_status_id'] = $this->config->get('config_return_status_id');

		// Return terms
		$data['config_return_id'] = $this->config->get('config_return_id');

		// Captcha
		$data['config_captcha'] = $this->config->get('config_captcha');

		$this->load->model('setting/extension');

		$data['captchas'] = [];

		// Get a list of installed captchas
		$extensions = $this->model_setting_extension->getExtensionsByType('captcha');

		foreach ($extensions as $extension) {
			$this->load->language('extension/' . $extension['extension'] . '/captcha/' . $extension['code'], 'extension');

			if ($this->config->get('captcha_' . $extension['code'] . '_status')) {
				$data['captchas'][] = [
					'text'  => $this->language->get('extension_heading_title'),
					'value' => $extension['code']
				];
			}
		}

		if ($this->config->has('config_captcha_page')) {
		   	$data['config_captcha_page'] = $this->config->get('config_captcha_page');
		} else {
			$data['config_captcha_page'] = [];
		}

		$data['captcha_pages'] = [];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_register'),
			'value' => 'register'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_guest'),
			'value' => 'guest'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_review'),
			'value' => 'review'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_return'),
			'value' => 'returns'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_contact'),
			'value' => 'contact'
		];

		// Images
		$data['config_logo'] = $this->config->get('config_logo');

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'))) {
			$data['logo'] = $this->model_tool_image->resize(html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['logo'] = $data['placeholder'];
		}

		if ($this->config->get('config_image_category_width')) {
			$data['config_image_category_width'] = $this->config->get('config_image_category_width');
		} else {
			$data['config_image_category_width'] = 80;
		}

		if ($this->config->get('config_image_category_height')) {
			$data['config_image_category_height'] = $this->config->get('config_image_category_height');
		} else {
			$data['config_image_category_height'] = 80;
		}

		if ($this->config->get('config_image_thumb_width')) {
			$data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		} else {
			$data['config_image_thumb_width'] = 228;
		}

		if ($this->config->get('config_image_thumb_height')) {
			$data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		} else {
			$data['config_image_thumb_height'] = 228;
		}

		if ($this->config->get('config_image_popup_width')) {
			$data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		} else {
			$data['config_image_popup_width'] = 500;
		}

		if ($this->config->get('config_image_popup_height')) {
			$data['config_image_popup_height'] = $this->config->get('config_image_popup_height');
		} else {
			$data['config_image_popup_height'] = 500;
		}

		if ($this->config->get('config_image_product_width')) {
			$data['config_image_product_width'] = $this->config->get('config_image_product_width');
		} else {
			$data['config_image_product_width'] = 228;
		}

		if ($this->config->get('config_image_product_height')) {
			$data['config_image_product_height'] = $this->config->get('config_image_product_height');
		} else {
			$data['config_image_product_height'] = 228;
		}

		if ($this->config->get('config_image_additional_width')) {
			$data['config_image_additional_width'] = $this->config->get('config_image_additional_width');
		} else {
			$data['config_image_additional_width'] = 74;
		}

		if ($this->config->get('config_image_additional_height')) {
			$data['config_image_additional_height'] = $this->config->get('config_image_additional_height');
		} else {
			$data['config_image_additional_height'] = 74;
		}

		if ($this->config->get('config_image_related_width')) {
			$data['config_image_related_width'] = $this->config->get('config_image_related_width');
		} else {
			$data['config_image_related_width'] = 80;
		}

		if ($this->config->get('config_image_related_height')) {
			$data['config_image_related_height'] = $this->config->get('config_image_related_height');
		} else {
			$data['config_image_related_height'] = 80;
		}

		if ($this->config->get('config_image_compare_width')) {
			$data['config_image_compare_width'] = $this->config->get('config_image_compare_width');
		} else {
			$data['config_image_compare_width'] = 90;
		}

		if ($this->config->get('config_image_compare_height')) {
			$data['config_image_compare_height'] = $this->config->get('config_image_compare_height');
		} else {
			$data['config_image_compare_height'] = 90;
		}

		if ($this->config->get('config_image_wishlist_width')) {
			$data['config_image_wishlist_width'] = $this->config->get('config_image_wishlist_width');
		} else {
			$data['config_image_wishlist_width'] = 47;
		}

		if ($this->config->get('config_image_wishlist_height')) {
			$data['config_image_wishlist_height'] = $this->config->get('config_image_wishlist_height');
		} else {
			$data['config_image_wishlist_height'] = 47;
		}

		if ($this->config->get('config_image_cart_width')) {
			$data['config_image_cart_width'] = $this->config->get('config_image_cart_width');
		} else {
			$data['config_image_cart_width'] = 47;
		}

		if ($this->config->get('config_image_cart_height')) {
			$data['config_image_cart_height'] =$this->config->get('config_image_cart_height');
		} else {
			$data['config_image_cart_height'] = 47;
		}

		if ($this->config->get('config_image_location_width')) {
			$data['config_image_location_width'] = $this->config->get('config_image_location_width');
		} else {
			$data['config_image_location_width'] = 268;
		}

		if ($this->config->get('config_image_location_height')) {
			$data['config_image_location_height'] = $this->config->get('config_image_location_height');
		} else {
			$data['config_image_location_height'] = 50;
		}

		// Mail
		$data['config_mail_engine'] = $this->config->get('config_mail_engine');
		$data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
		$data['config_mail_smtp_hostname'] = $this->config->get('config_mail_smtp_hostname');
		$data['config_mail_smtp_username'] = $this->config->get('config_mail_smtp_username');
		$data['config_mail_smtp_password'] = $this->config->get('config_mail_smtp_password');

		if ($this->config->has('config_mail_smtp_port')) {
			$data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
		} else {
			$data['config_mail_smtp_port'] = 25;
		}

		if ($this->config->has('config_mail_smtp_timeout')) {
			$data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
		} else {
			$data['config_mail_smtp_timeout'] = 5;
		}

		if ($this->config->has('config_mail_alert')) {
		   	$data['config_mail_alert'] = $this->config->get('config_mail_alert');
		} else {
			$data['config_mail_alert'] = [];
		}

		$data['mail_alerts'] = [];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_account'),
			'value' => 'account'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_affiliate'),
			'value' => 'affiliate'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_order'),
			'value' => 'order'
		];

		$data['mail_alerts'][] = [
			'text'  => $this->language->get('text_mail_review'),
			'value' => 'review'
		];

		$data['config_mail_alert_email'] = $this->config->get('config_mail_alert_email');

		// Server
		$data['config_maintenance'] = $this->config->get('config_maintenance');

		if ($this->config->has('config_session_expire')) {
			$data['config_session_expire'] = $this->config->get('config_session_expire');
		} else {
			$data['config_session_expire'] = 3600;
		}

		$data['config_session_samesite'] = $this->config->get('config_session_samesite');
		$data['config_seo_url'] = $this->config->get('config_seo_url');
		$data['config_robots'] = $this->config->get('config_robots');
		$data['config_compression'] = $this->config->get('config_compression');

		// Security
		$data['config_security'] = $this->config->get('config_security');
		$data['config_shared'] = $this->config->get('config_shared');
		$data['config_encryption'] = $this->config->get('config_encryption');

		// Uploads
		if ($this->config->get('config_file_max_size')) {
			$data['config_file_max_size'] = $this->config->get('config_file_max_size');
		} else {
			$data['config_file_max_size'] = 20;
		}

		$data['config_file_ext_allowed'] = $this->config->get('config_file_ext_allowed');
		$data['config_file_mime_allowed'] = $this->config->get('config_file_mime_allowed');

		// Errors
		$data['config_error_display'] = $this->config->get('config_error_display');
		$data['config_error_log'] = $this->config->get('config_error_log');
		$data['config_error_filename'] = $this->config->get('config_error_filename');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting', $data));
	}

	public function save(): void {
		$this->load->language('setting/setting');

		$json = [];

		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_meta_title']) {
			$json['error']['meta_title'] = $this->language->get('error_meta_title');
		}

		if (!$this->request->post['config_name']) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if ((oc_strlen($this->request->post['config_owner']) < 3) || (oc_strlen($this->request->post['config_owner']) > 64)) {
			$json['error']['owner'] = $this->language->get('error_owner');
		}

		if ((oc_strlen($this->request->post['config_address']) < 3) || (oc_strlen($this->request->post['config_address']) > 256)) {
			$json['error']['address'] = $this->language->get('error_address');
		}

		if ((oc_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		if ((oc_strlen($this->request->post['config_telephone']) < 3) || (oc_strlen($this->request->post['config_telephone']) > 32)) {
			$json['error']['telephone'] = $this->language->get('error_telephone');
		}

		if (!$this->request->post['config_product_description_length']) {
			$json['error']['product_description_length'] = $this->language->get('error_product_description_length');
		}

		if (!$this->request->post['config_pagination']) {
			$json['error']['pagination'] = $this->language->get('error_pagination');
		}

		if (!$this->request->post['config_pagination_admin']) {
			$json['error']['pagination_admin'] = $this->language->get('error_pagination');
		}

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$json['error']['customer_group_display'] = $this->language->get('error_customer_group_display');
		}

		if ($this->request->post['config_login_attempts'] < 1) {
			$json['error']['login_attempts'] = $this->language->get('error_login_attempts');
		}

		if (!$this->request->post['config_voucher_min']) {
			$json['error']['voucher_min'] = $this->language->get('error_voucher_min');
		}

		if (!$this->request->post['config_voucher_max']) {
			$json['error']['voucher_max'] = $this->language->get('error_voucher_max');
		}

		if (!$this->request->post['config_customer_online_expire']) {
			$json['error']['customer_online_expire'] = $this->language->get('error_customer_online_expire');
		}

		if (!isset($this->request->post['config_processing_status'])) {
			$json['error']['processing_status'] = $this->language->get('error_processing_status');
		}

		if (!isset($this->request->post['config_complete_status'])) {
			$json['error']['complete_status'] = $this->language->get('error_complete_status');
		}

		if (!$this->request->post['config_image_category_width'] || !$this->request->post['config_image_category_height']) {
			$json['error']['image_category'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$json['error']['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$json['error']['image_popup'] = $this->language->get('error_image_popup');
		}

		if (!$this->request->post['config_image_product_width'] || !$this->request->post['config_image_product_height']) {
			$json['error']['image_product'] = $this->language->get('error_image_product');
		}

		if (!$this->request->post['config_image_additional_width'] || !$this->request->post['config_image_additional_height']) {
			$json['error']['image_additional'] = $this->language->get('error_image_additional');
		}

		if (!$this->request->post['config_image_related_width'] || !$this->request->post['config_image_related_height']) {
			$json['error']['image_related'] = $this->language->get('error_image_related');
		}

		if (!$this->request->post['config_image_compare_width'] || !$this->request->post['config_image_compare_height']) {
			$json['error']['image_compare'] = $this->language->get('error_image_compare');
		}

		if (!$this->request->post['config_image_wishlist_width'] || !$this->request->post['config_image_wishlist_height']) {
			$json['error']['image_wishlist'] = $this->language->get('error_image_wishlist');
		}

		if (!$this->request->post['config_image_cart_width'] || !$this->request->post['config_image_cart_height']) {
			$json['error']['image_cart'] = $this->language->get('error_image_cart');
		}

		if (!$this->request->post['config_image_location_width'] || !$this->request->post['config_image_location_height']) {
			$json['error']['image_location'] = $this->language->get('error_image_location');
		}

		if ($this->request->post['config_security'] && !$this->request->post['config_mail_engine']) {
			$json['error']['warning'] = $this->language->get('error_security');
		}

		if ((oc_strlen($this->request->post['config_encryption']) < 32) || (oc_strlen($this->request->post['config_encryption']) > 1024)) {
			$json['error']['encryption'] = $this->language->get('error_encryption');
		}

		if (!$this->request->post['config_file_max_size']) {
			$json['error']['file_max_size'] = $this->language->get('error_file_max_size');
		}

		$disallowed = [
			'php',
			'php4',
			'php3'
		];

		$extensions = explode("\n", $this->request->post['config_file_ext_allowed']);

		foreach ($extensions as $extension) {
			if (in_array(trim($extension), $disallowed)) {
				$json['error']['file_ext_allowed'] = $this->language->get('error_extension');

				break;
			}
		}

		$disallowed = [
			'php',
			'php4',
			'php3'
		];

		$mimes = explode("\n", $this->request->post['config_file_mime_allowed']);

		foreach ($mimes as $mime) {
			if (in_array(trim($mime), $disallowed)) {
				$json['error']['file_mime_allowed'] = $this->language->get('error_mime');

				break;
			}
		}

		if (!$this->request->post['config_error_filename']) {
			$json['error']['error_filename'] = $this->language->get('error_log_required');
		} elseif (preg_match('/\.\.[\/\\\]?/', $this->request->post['config_error_filename'])) {
			$json['error']['error_filename'] = $this->language->get('error_log_invalid');
		} elseif (substr($this->request->post['config_error_filename'], strrpos($this->request->post['config_error_filename'], '.')) != '.log') {
			$json['error']['error_filename'] = $this->language->get('error_log_extension');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function theme(): void {
		if (isset($this->request->get['theme'])) {
			$theme = basename($this->request->get['theme']);
		} else {
			$theme = '';
		}

		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('theme', $theme);

		if ($extension_info) {
			$this->response->setOutput(HTTP_CATALOG . 'extension/' . $extension_info['extension'] . '/admin/view/image/' . $extension_info['code'] . '.png');
		} else {
			$this->response->setOutput(HTTP_CATALOG . 'image/no_image.png');
		}
	}
}
