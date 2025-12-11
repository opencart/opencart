<?php
namespace Opencart\Admin\Controller\Setting;
/**
 * Class Setting
 *
 * @package Opencart\Admin\Controller\Setting
 */
class Setting extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
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
		$data['config_description'] = (array)$this->config->get('config_description');

		// Store Details
		$data['config_name'] = $this->config->get('config_name');

		$data['store_url'] = HTTP_CATALOG;

		$data['themes'] = [];

		// Extensions
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

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		$data['config_layout_id'] = $this->config->get('config_layout_id');

		$data['config_owner'] = $this->config->get('config_owner');
		$data['config_address'] = $this->config->get('config_address');
		$data['config_email'] = $this->config->get('config_email');
		$data['config_telephone'] = $this->config->get('config_telephone');

		// Image
		$data['config_image'] = $this->config->get('config_image');

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_image'] && is_file(DIR_IMAGE . html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize($data['config_image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		$data['config_open'] = $this->config->get('config_open');
		$data['config_comment'] = $this->config->get('config_comment');

		// Locations
		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['config_location'] = (array)$this->config->get('config_location');

		// Country / Zone
		$data['config_country_id'] = $this->config->get('config_country_id');
		$data['config_country_list'] = $this->config->get('config_country_list');
		$data['config_zone_id'] = $this->config->get('config_zone_id');
		$data['config_timezone'] = $this->config->get('config_timezone');

		// Time
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

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['config_language_catalog'] = $this->config->get('config_language_catalog');
		$data['config_language_list'] = $this->config->get('config_language_list');
		$data['config_language_admin'] = $this->config->get('config_language_admin');

		// Currencies
		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		$data['config_currency'] = $this->config->get('config_currency');
		$data['config_currency_list'] = $this->config->get('config_currency_list');

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

		// Length Classes
		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		$data['config_length_class_id'] = $this->config->get('config_length_class_id');

		// Weight Classes
		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		$data['config_weight_class_id'] = $this->config->get('config_weight_class_id');

		// Options
		$data['config_product_description_length'] = $this->config->get('config_product_description_length');
		$data['config_pagination'] = $this->config->get('config_pagination');
		$data['config_product_count'] = $this->config->get('config_product_count');
		$data['config_pagination_admin'] = $this->config->get('config_pagination_admin');
		$data['config_autocomplete_limit'] = $this->config->get('config_autocomplete_limit');
		$data['config_product_report_status'] = $this->config->get('config_product_report_status');

		// Review
		$data['config_review_status'] = $this->config->get('config_review_status');
		$data['config_review_purchased'] = $this->config->get('config_review_purchased');
		$data['config_review_guest'] = $this->config->get('config_review_guest');

		// CMS
		$data['config_article_description_length'] = $this->config->get('config_article_description_length');
		$data['config_comment_status'] = $this->config->get('config_comment_status');
		$data['config_comment_approve'] = $this->config->get('config_comment_approve');
		$data['config_comment_interval'] = $this->config->get('config_comment_interval');

		// Legal
		$data['config_cookie_id'] = $this->config->get('config_cookie_id');
		$data['config_gdpr_id'] = $this->config->get('config_gdpr_id');
		$data['config_gdpr_limit'] = $this->config->get('config_gdpr_limit');

		// Tax
		$data['config_tax'] = $this->config->get('config_tax');
		$data['config_tax_default'] = $this->config->get('config_tax_default');
		$data['config_tax_customer'] = $this->config->get('config_tax_customer');

		// Customer
		$data['config_customer_online'] = $this->config->get('config_customer_online');
		$data['config_customer_online_expire'] = $this->config->get('config_customer_online_expire');
		$data['config_customer_activity'] = $this->config->get('config_customer_activity');
		$data['config_customer_search'] = $this->config->get('config_customer_search');

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['config_customer_group_id'] = $this->config->get('config_customer_group_id');
		$data['config_customer_group_list'] = (array)$this->config->get('config_customer_group_list');
		$data['config_customer_price'] = $this->config->get('config_customer_price');

		$data['config_telephone_display'] = $this->config->get('config_telephone_display');
		$data['config_telephone_required'] = $this->config->get('config_telephone_required');

		$data['config_2fa'] = $this->config->get('config_2fa');
		$data['config_login_attempts'] = $this->config->get('config_login_attempts');

		$data['config_password_uppercase'] = $this->config->get('config_password_uppercase');
		$data['config_password_lowercase'] = $this->config->get('config_password_lowercase');
		$data['config_password_number'] = $this->config->get('config_password_number');
		$data['config_password_symbol'] = $this->config->get('config_password_symbol');
		$data['config_password_length'] = $this->config->get('config_password_length');

		// Information
		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		$data['config_account_id'] = $this->config->get('config_account_id');

		// Checkout
		$data['config_shared'] = $this->config->get('config_shared');
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

		// Order Statuses
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['config_order_status_id'] = $this->config->get('config_order_status_id');
		$data['config_processing_status'] = (array)$this->config->get('config_processing_status');
		$data['config_complete_status'] = (array)$this->config->get('config_complete_status');
		$data['config_failed_status_id'] = $this->config->get('config_failed_status_id');
		$data['config_void_status_id'] = $this->config->get('config_void_status_id');
		$data['config_fraud_status_id'] = $this->config->get('config_fraud_status_id');

		// Subscription Statuses
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

		// Stock Statuses
		$this->load->model('localisation/stock_status');

		$data['stock_statuses'] = $this->model_localisation_stock_status->getStockStatuses();

		$data['config_stock_status_id'] = $this->config->get('config_stock_status_id');
		$data['config_stock_display'] = $this->config->get('config_stock_display');
		$data['config_stock_warning'] = $this->config->get('config_stock_warning');
		$data['config_stock_checkout'] = $this->config->get('config_stock_checkout');

		// Affiliate
		$data['config_affiliate_status'] = $this->config->get('config_affiliate_status');
		$data['config_affiliate_group_id'] = $this->config->get('config_affiliate_group_id');
		$data['config_affiliate_approval'] = $this->config->get('config_affiliate_approval');
		$data['config_affiliate_auto'] = (bool)$this->config->get('config_affiliate_auto');
		$data['config_affiliate_commission'] = (float)$this->config->get('config_affiliate_commission');

		// Affiliate terms
		$data['config_affiliate_id'] = $this->config->get('config_affiliate_id');

		// Return Statuses
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

		$data['config_captcha_page'] = (array)$this->config->get('config_captcha_page');

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
			'text'  => $this->language->get('text_comment'),
			'value' => 'comment'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_return'),
			'value' => 'returns'
		];

		$data['captcha_pages'][] = [
			'text'  => $this->language->get('text_contact'),
			'value' => 'contact'
		];

		// Image
		$data['config_logo'] = $this->config->get('config_logo');

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_logo'] && is_file(DIR_IMAGE . html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'))) {
			$data['logo'] = $this->model_tool_image->resize($data['config_logo'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['logo'] = $data['placeholder'];
		}
		// Fav Icon
		$data['config_icon'] = $this->config->get('config_icon');
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_icon'] && is_file(DIR_IMAGE . html_entity_decode($data['config_icon'], ENT_QUOTES, 'UTF-8'))) {
			$data['icon'] = $this->model_tool_image->resize($data['config_icon'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['icon'] = '';
		}

		// Image
		$data['config_image_default_width'] = $this->config->get('config_image_default_width');
		$data['config_image_default_height'] = $this->config->get('config_image_default_height');
		$data['config_image_thumb_width'] = $this->config->get('config_image_thumb_width');
		$data['config_image_thumb_height'] = $this->config->get('config_image_thumb_height');
		$data['config_image_popup_width'] = $this->config->get('config_image_popup_width');
		$data['config_image_popup_height'] = $this->config->get('config_image_popup_height');

		// Mail
		$data['config_mail_engine'] = $this->config->get('config_mail_engine');
		$data['config_mail_parameter'] = $this->config->get('config_mail_parameter');
		$data['config_mail_smtp_hostname'] = $this->config->get('config_mail_smtp_hostname');
		$data['config_mail_smtp_username'] = $this->config->get('config_mail_smtp_username');
		$data['config_mail_smtp_password'] = $this->config->get('config_mail_smtp_password');
		$data['config_mail_smtp_port'] = $this->config->get('config_mail_smtp_port');
		$data['config_mail_smtp_timeout'] = $this->config->get('config_mail_smtp_timeout');
		$data['config_mail_alert'] = (array)$this->config->get('config_mail_alert');

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
		$data['config_seo_url'] = $this->config->get('config_seo_url');

		// Security
		$data['config_user_2fa'] = $this->config->get('config_user_2fa');
		$data['config_2fa_expire'] = $this->config->get('config_2fa_expire');
		$data['config_user_password_uppercase'] = $this->config->get('config_user_password_uppercase');
		$data['config_user_password_lowercase'] = $this->config->get('config_user_password_lowercase');
		$data['config_user_password_number'] = $this->config->get('config_user_password_number');
		$data['config_user_password_symbol'] = $this->config->get('config_user_password_symbol');
		$data['config_user_password_length'] = $this->config->get('config_user_password_length');

		// Errors
		$data['config_error_display'] = $this->config->get('config_error_display');
		$data['config_error_log'] = $this->config->get('config_error_log');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/setting', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('setting/setting');

		$json = [];

		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_name']) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		foreach ($this->request->post['config_description'] as $language_id => $value) {
			if (!oc_validate_length($value['meta_title'], 1, 64)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if (!oc_validate_length($this->request->post['config_owner'], 3, 64)) {
			$json['error']['owner'] = $this->language->get('error_owner');
		}

		if (!oc_validate_length($this->request->post['config_address'], 3, 256)) {
			$json['error']['address'] = $this->language->get('error_address');
		}

		if ((oc_strlen($this->request->post['config_email']) > 96) || !filter_var($this->request->post['config_email'], FILTER_VALIDATE_EMAIL)) {
			$json['error']['email'] = $this->language->get('error_email');
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry((int)$this->request->post['config_country_id']);

		if (!$country_info) {
			$json['error']['country'] = $this->language->get('error_country');
		}

		// Zones
		$this->load->model('localisation/zone');

		// Total Zones
		$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId((int)$this->request->post['config_country_id']);

		if ($zone_total && !$this->request->post['config_zone_id']) {
			$json['error']['zone'] = $this->language->get('error_zone');
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

		if (!$this->request->post['config_autocomplete_limit']) {
			$json['error']['autocomplete_limit'] = $this->language->get('error_autocomplete_limit');
		}

		if (!$this->request->post['config_article_description_length']) {
			$json['error']['article_description_length'] = $this->language->get('error_article_description_length');
		}

		if (!empty($this->request->post['config_customer_group_list']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_list'])) {
			$json['error']['customer_group_display'] = $this->language->get('error_customer_group_display');
		}

		if ($this->request->post['config_login_attempts'] < 1) {
			$json['error']['login_attempts'] = $this->language->get('error_login_attempts');
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

		if (!$this->request->post['config_image_default_width'] || !$this->request->post['config_image_default_height']) {
			$json['error']['image_default'] = $this->language->get('error_image_category');
		}

		if (!$this->request->post['config_image_thumb_width'] || !$this->request->post['config_image_thumb_height']) {
			$json['error']['image_thumb'] = $this->language->get('error_image_thumb');
		}

		if (!$this->request->post['config_image_popup_width'] || !$this->request->post['config_image_popup_height']) {
			$json['error']['image_popup'] = $this->language->get('error_image_popup');
		}

		if ($this->request->post['config_user_2fa'] && !$this->request->post['config_mail_engine']) {
			$json['error']['warning'] = $this->language->get('error_user_2fa');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('config', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Theme
	 *
	 * @return void
	 */
	public function theme(): void {
		if (isset($this->request->get['theme'])) {
			$theme = basename($this->request->get['theme']);
		} else {
			$theme = '';
		}

		// Extension
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('theme', $theme);

		if ($extension_info) {
			$this->response->setOutput(HTTP_CATALOG . 'extension/' . $extension_info['extension'] . '/admin/view/image/' . $extension_info['code'] . '.png');
		} else {
			$this->response->setOutput(HTTP_CATALOG . 'image/no_image.png');
		}
	}
}
