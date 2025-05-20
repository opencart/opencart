<?php
namespace Opencart\Admin\Controller\Setting;
/**
 * Class Store
 *
 * @package Opencart\Admin\Controller\Setting
 */
class Store extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('setting/store.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('setting/store.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/store', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('setting/store');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('setting/store.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Stores
		$data['stores'] = [];

		$store_total = 0;

		if ($page == 1) {
			$store_total = 1;

			$data['stores'][] = [
				'store_id' => 0,
				'name'     => $this->config->get('config_name'),
				'url'      => HTTP_CATALOG,
				'edit'     => $this->url->link('setting/setting', 'user_token=' . $this->session->data['user_token'])
			];
		}

		$this->load->model('setting/store');

		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = ['edit' => $this->url->link('setting/store.form', 'user_token=' . $this->session->data['user_token'] . '&store_id=' . $result['store_id'])] + $result;
		}

		$store_total += $this->model_setting_store->getTotalStores();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $store_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('setting/store.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($store_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($store_total - $this->config->get('config_pagination_admin'))) ? $store_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $store_total, ceil($store_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('setting/store_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('setting/store');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['store_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_settings'),
			'href' => $this->url->link('setting/store.form', 'user_token=' . $this->session->data['user_token'] . (isset($this->request->post['store_id']) ? '&store_id=' . $this->request->get['store_id'] : '') . $url)
		];

		$data['save'] = $this->url->link('setting/store.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('setting/store', 'user_token=' . $this->session->data['user_token']);

		if (isset($this->request->get['store_id'])) {
			// Setting
			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($this->request->get['store_id']);

			$this->load->model('setting/setting');

			$setting_info = $this->model_setting_setting->getSetting('config', $this->request->get['store_id']);
		}

		if (!empty($store_info)) {
			$data['store_id'] = $store_info['store_id'];
		} else {
			$data['store_id'] = 0;
		}

		if (isset($setting_info['config_url'])) {
			$data['config_url'] = $setting_info['config_url'];
		} else {
			$data['config_url'] = '';
		}

		if (isset($setting_info['config_description'])) {
			$data['config_description'] = $setting_info['config_description'];
		} else {
			$data['config_description'] = [];
		}

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

		if (isset($setting_info['config_theme'])) {
			$data['config_theme'] = $setting_info['config_theme'];
		} else {
			$data['config_theme'] = '';
		}

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($setting_info['config_layout_id'])) {
			$data['config_layout_id'] = $setting_info['config_layout_id'];
		} else {
			$data['config_layout_id'] = '';
		}

		if (isset($setting_info['config_name'])) {
			$data['config_name'] = $setting_info['config_name'];
		} else {
			$data['config_name'] = '';
		}

		if (isset($setting_info['config_owner'])) {
			$data['config_owner'] = $setting_info['config_owner'];
		} else {
			$data['config_owner'] = '';
		}

		if (isset($setting_info['config_address'])) {
			$data['config_address'] = $setting_info['config_address'];
		} else {
			$data['config_address'] = '';
		}

		if (isset($setting_info['config_geocode'])) {
			$data['config_geocode'] = $setting_info['config_geocode'];
		} else {
			$data['config_geocode'] = '';
		}

		if (isset($setting_info['config_email'])) {
			$data['config_email'] = $setting_info['config_email'];
		} else {
			$data['config_email'] = '';
		}

		if (isset($setting_info['config_telephone'])) {
			$data['config_telephone'] = $setting_info['config_telephone'];
		} else {
			$data['config_telephone'] = '';
		}

		// Image
		if (isset($setting_info['config_image'])) {
			$data['config_image'] = $setting_info['config_image'];
		} else {
			$data['config_image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_image'] && is_file(DIR_IMAGE . html_entity_decode($data['config_image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize($data['config_image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (isset($setting_info['config_open'])) {
			$data['config_open'] = $setting_info['config_open'];
		} else {
			$data['config_open'] = '';
		}

		if (isset($setting_info['config_comment'])) {
			$data['config_comment'] = $setting_info['config_comment'];
		} else {
			$data['config_comment'] = '';
		}

		// Locations
		$this->load->model('localisation/location');

		$data['locations'] = $this->model_localisation_location->getLocations();

		if (isset($setting_info['config_location'])) {
			$data['config_location'] = $setting_info['config_location'];
		} else {
			$data['config_location'] = [];
		}

		// Countries
		if (isset($setting_info['config_country_id'])) {
			$data['config_country_id'] = $setting_info['config_country_id'];
		} else {
			$data['config_country_id'] = $this->config->get('config_country_id');
		}

		if (isset($setting_info['config_zone_id'])) {
			$data['config_zone_id'] = $setting_info['config_zone_id'];
		} else {
			$data['config_zone_id'] = $this->config->get('config_zone_id');
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($setting_info['config_language_catalog'])) {
			$data['config_language_catalog'] = $setting_info['config_language_catalog'];
		} else {
			$data['config_language_catalog'] = $this->config->get('config_language_catalog');
		}

		// Currencies
		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (isset($setting_info['config_currency'])) {
			$data['config_currency'] = $setting_info['config_currency'];
		} else {
			$data['config_currency'] = $this->config->get('config_currency');
		}

		// Options
		if (isset($setting_info['config_product_description_length'])) {
			$data['config_product_description_length'] = $setting_info['config_product_description_length'];
		} else {
			$data['config_product_description_length'] = 100;
		}

		if (isset($setting_info['config_pagination'])) {
			$data['config_pagination'] = $setting_info['config_pagination'];
		} else {
			$data['config_pagination'] = 15;
		}

		if (isset($setting_info['config_product_count'])) {
			$data['config_product_count'] = $setting_info['config_product_count'];
		} else {
			$data['config_product_count'] = 10;
		}

		if (isset($setting_info['config_article_description_length'])) {
			$data['config_article_description_length'] = $setting_info['config_article_description_length'];
		} else {
			$data['config_article_description_length'] = 100;
		}

		if (isset($setting_info['config_cookie_id'])) {
			$data['config_cookie_id'] = $setting_info['config_cookie_id'];
		} else {
			$data['config_cookie_id'] = '';
		}

		if (isset($setting_info['config_gdpr_id'])) {
			$data['config_gdpr_id'] = $setting_info['config_gdpr_id'];
		} else {
			$data['config_gdpr_id'] = '';
		}

		if (isset($setting_info['config_tax'])) {
			$data['config_tax'] = $setting_info['config_tax'];
		} else {
			$data['config_tax'] = '';
		}

		if (isset($setting_info['config_tax_default'])) {
			$data['config_tax_default'] = $setting_info['config_tax_default'];
		} else {
			$data['config_tax_default'] = '';
		}

		if (isset($setting_info['config_tax_customer'])) {
			$data['config_tax_customer'] = $setting_info['config_tax_customer'];
		} else {
			$data['config_tax_customer'] = '';
		}

		// Customer Groups
		$this->load->model('customer/customer_group');

		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		if (isset($setting_info['config_customer_group_id'])) {
			$data['config_customer_group_id'] = $setting_info['config_customer_group_id'];
		} else {
			$data['config_customer_group_id'] = '';
		}

		if (isset($setting_info['config_customer_group_display'])) {
			$data['config_customer_group_display'] = $setting_info['config_customer_group_display'];
		} else {
			$data['config_customer_group_display'] = [];
		}

		if (isset($setting_info['config_customer_price'])) {
			$data['config_customer_price'] = $setting_info['config_customer_price'];
		} else {
			$data['config_customer_price'] = '';
		}

		// Information
		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();

		if (isset($setting_info['config_account_id'])) {
			$data['config_account_id'] = $setting_info['config_account_id'];
		} else {
			$data['config_account_id'] = '';
		}

		if (isset($setting_info['config_cart_weight'])) {
			$data['config_cart_weight'] = $setting_info['config_cart_weight'];
		} else {
			$data['config_cart_weight'] = '';
		}

		if (isset($setting_info['config_checkout_guest'])) {
			$data['config_checkout_guest'] = $setting_info['config_checkout_guest'];
		} else {
			$data['config_checkout_guest'] = '';
		}

		if (isset($setting_info['config_checkout_id'])) {
			$data['config_checkout_id'] = $setting_info['config_checkout_id'];
		} else {
			$data['config_checkout_id'] = '';
		}

		if (isset($setting_info['config_stock_display'])) {
			$data['config_stock_display'] = $setting_info['config_stock_display'];
		} else {
			$data['config_stock_display'] = '';
		}

		if (isset($setting_info['config_stock_checkout'])) {
			$data['config_stock_checkout'] = $setting_info['config_stock_checkout'];
		} else {
			$data['config_stock_checkout'] = '';
		}

		// Image
		if (isset($setting_info['config_logo'])) {
			$data['config_logo'] = $setting_info['config_logo'];
		} else {
			$data['config_logo'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_logo'] && is_file(DIR_IMAGE . html_entity_decode($data['config_logo'], ENT_QUOTES, 'UTF-8'))) {
			$data['logo'] = $this->model_tool_image->resize($data['config_logo'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['logo'] = $data['placeholder'];
		}

		// Fav Icon
		if (isset($setting_info['config_icon'])) {
			$data['config_icon'] = $setting_info['config_icon'];
		} else {
			$data['config_icon'] = '';
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['config_icon'] && is_file(DIR_IMAGE . html_entity_decode($data['config_icon'], ENT_QUOTES, 'UTF-8'))) {
			$data['icon'] = $this->model_tool_image->resize($data['config_icon'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['icon'] = '';
		}

		if (isset($setting_info['config_image_category_width'])) {
			$data['config_image_category_width'] = $setting_info['config_image_category_width'];
		} else {
			$data['config_image_category_width'] = 80;
		}

		if (isset($setting_info['config_image_category_height'])) {
			$data['config_image_category_height'] = $setting_info['config_image_category_height'];
		} else {
			$data['config_image_category_height'] = 80;
		}

		if (isset($setting_info['config_image_thumb_width'])) {
			$data['config_image_thumb_width'] = $setting_info['config_image_thumb_width'];
		} else {
			$data['config_image_thumb_width'] = 228;
		}

		if (isset($setting_info['config_image_thumb_height'])) {
			$data['config_image_thumb_height'] = $setting_info['config_image_thumb_height'];
		} else {
			$data['config_image_thumb_height'] = 228;
		}

		if (isset($setting_info['config_image_popup_width'])) {
			$data['config_image_popup_width'] = $setting_info['config_image_popup_width'];
		} else {
			$data['config_image_popup_width'] = 500;
		}

		if (isset($setting_info['config_image_popup_height'])) {
			$data['config_image_popup_height'] = $setting_info['config_image_popup_height'];
		} else {
			$data['config_image_popup_height'] = 500;
		}

		if (isset($setting_info['config_image_product_width'])) {
			$data['config_image_product_width'] = $setting_info['config_image_product_width'];
		} else {
			$data['config_image_product_width'] = 228;
		}

		if (isset($setting_info['config_image_product_height'])) {
			$data['config_image_product_height'] = $setting_info['config_image_product_height'];
		} else {
			$data['config_image_product_height'] = 228;
		}

		if (isset($setting_info['config_image_additional_width'])) {
			$data['config_image_additional_width'] = $setting_info['config_image_additional_width'];
		} else {
			$data['config_image_additional_width'] = 74;
		}

		if (isset($setting_info['config_image_additional_height'])) {
			$data['config_image_additional_height'] = $setting_info['config_image_additional_height'];
		} else {
			$data['config_image_additional_height'] = 74;
		}

		if (isset($setting_info['config_image_related_width'])) {
			$data['config_image_related_width'] = $setting_info['config_image_related_width'];
		} else {
			$data['config_image_related_width'] = 80;
		}

		if (isset($setting_info['config_image_related_height'])) {
			$data['config_image_related_height'] = $setting_info['config_image_related_height'];
		} else {
			$data['config_image_related_height'] = 74;
		}

		if (isset($setting_info['config_image_article_width'])) {
			$data['config_image_article_width'] = $setting_info['config_image_article_width'];
		} else {
			$data['config_image_article_width'] = 1140;
		}

		if (isset($setting_info['config_image_article_height'])) {
			$data['config_image_article_height'] = $setting_info['config_image_article_height'];
		} else {
			$data['config_image_article_height'] = 380;
		}

		if (isset($setting_info['config_image_topic_width'])) {
			$data['config_image_topic_width'] = $setting_info['config_image_topic_width'];
		} else {
			$data['config_image_topic_width'] = 1140;
		}

		if (isset($setting_info['config_image_topic_height'])) {
			$data['config_image_topic_height'] = $setting_info['config_image_topic_height'];
		} else {
			$data['config_image_topic_height'] = 380;
		}

		if (isset($setting_info['config_image_compare_width'])) {
			$data['config_image_compare_width'] = $setting_info['config_image_compare_width'];
		} else {
			$data['config_image_compare_width'] = 90;
		}

		if (isset($setting_info['config_image_compare_height'])) {
			$data['config_image_compare_height'] = $setting_info['config_image_compare_height'];
		} else {
			$data['config_image_compare_height'] = 90;
		}

		if (isset($setting_info['config_image_wishlist_width'])) {
			$data['config_image_wishlist_width'] = $setting_info['config_image_wishlist_width'];
		} else {
			$data['config_image_wishlist_width'] = 47;
		}

		if (isset($setting_info['config_image_wishlist_height'])) {
			$data['config_image_wishlist_height'] = $setting_info['config_image_wishlist_height'];
		} else {
			$data['config_image_wishlist_height'] = 47;
		}

		if (isset($setting_info['config_image_cart_width'])) {
			$data['config_image_cart_width'] = $setting_info['config_image_cart_width'];
		} else {
			$data['config_image_cart_width'] = 47;
		}

		if (isset($setting_info['config_image_cart_height'])) {
			$data['config_image_cart_height'] = $setting_info['config_image_cart_height'];
		} else {
			$data['config_image_cart_height'] = 47;
		}

		if (isset($setting_info['config_image_location_width'])) {
			$data['config_image_location_width'] = $setting_info['config_image_location_width'];
		} else {
			$data['config_image_location_width'] = 268;
		}

		if (isset($setting_info['config_image_location_height'])) {
			$data['config_image_location_height'] = $setting_info['config_image_location_height'];
		} else {
			$data['config_image_location_height'] = 50;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('setting/store_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('setting/store');

		$json = [];

		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['config_url']) {
			$json['error']['url'] = $this->language->get('error_url');
		}

		foreach ($this->request->post['config_description'] as $language_id => $value) {
			if (!oc_validate_length($value['meta_title'], 1, 64)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if (!$this->request->post['config_name']) {
			$json['error']['name'] = $this->language->get('error_name');
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

		if (!empty($this->request->post['config_customer_group_display']) && !in_array($this->request->post['config_customer_group_id'], $this->request->post['config_customer_group_display'])) {
			$json['error']['customer_group_display'] = $this->language->get('error_customer_group_display');
		}

		if (!$this->request->post['config_product_description_length']) {
			$json['error']['product_description_length'] = $this->language->get('error_product_description_length');
		}

		if (!$this->request->post['config_pagination']) {
			$json['error']['pagination'] = $this->language->get('error_pagination');
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

		if (!$this->request->post['config_image_article_width'] || !$this->request->post['config_image_article_height']) {
			$json['error']['image_article'] = $this->language->get('error_image_article');
		}

		if (!$this->request->post['config_image_topic_width'] || !$this->request->post['config_image_topic_height']) {
			$json['error']['image_topic'] = $this->language->get('error_image_topic');
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

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			// Setting
			$this->load->model('setting/store');

			if (!$this->request->post['store_id']) {
				$json['store_id'] = $this->model_setting_store->addStore($this->request->post);

				$this->model_setting_setting->editSetting('config', $this->request->post, $json['store_id']);
			} else {
				$this->model_setting_store->editStore($this->request->post['store_id'], $this->request->post);

				$this->model_setting_setting->editSetting('config', $this->request->post, $this->request->post['store_id']);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('setting/store');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'setting/store')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Orders
		$this->load->model('sale/order');

		// Total Subscriptions
		$this->load->model('sale/subscription');

		foreach ($selected as $store_id) {
			if (!$store_id) {
				$json['error'] = $this->language->get('error_default');
			}

			// Total Orders
			$order_total = $this->model_sale_order->getTotalOrdersByStoreId($store_id);

			if ($order_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $order_total);
			}

			// Total Subscriptions
			$subscription_total = $this->model_sale_subscription->getTotalSubscriptionsByStoreId($store_id);

			if ($subscription_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $subscription_total);
			}
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/store');

			$this->load->model('setting/setting');

			foreach ($selected as $store_id) {
				$this->model_setting_store->deleteStore($store_id);

				$this->model_setting_setting->deleteSetting('config', $store_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
