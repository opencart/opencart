<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class Country extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$filter_iso_code_2 = (string)$this->request->get['filter_iso_code_2'];
		} else {
			$filter_iso_code_2 = '';
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$filter_iso_code_3 = (string)$this->request->get['filter_iso_code_3'];
		} else {
			$filter_iso_code_3 = '';
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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
			'href' => $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/country.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/country.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['filter_name'] = $filter_name;
		$data['filter_iso_code_2'] = $filter_iso_code_2;
		$data['filter_iso_code_3'] = $filter_iso_code_3;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/country', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/country');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$filter_iso_code_2 = (string)$this->request->get['filter_iso_code_2'];
		} else {
			$filter_iso_code_2 = '';
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$filter_iso_code_3 = (string)$this->request->get['filter_iso_code_3'];
		} else {
			$filter_iso_code_3 = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Countries
		$data['countries'] = [];

		$filter_data = [
			'filter_name'       => $filter_name,
			'filter_iso_code_2' => $filter_iso_code_2,
			'filter_iso_code_3' => $filter_iso_code_3,
			'sort'              => $sort,
			'order'             => $order,
			'start'             => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'             => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/country');

		$results = $this->model_localisation_country->getCountries($filter_data);

		foreach ($results as $result) {
			$data['countries'][] = ['edit' => $this->url->link('localisation/country.form', 'user_token=' . $this->session->data['user_token'] . '&country_id=' . $result['country_id'] . $url)] + $result;
		}

		// Default
		$data['country_id'] = $this->config->get('config_country_id');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_iso_code_2'] = $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . '&sort=iso_code_2' . $url);
		$data['sort_iso_code_3'] = $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . '&sort=iso_code_3' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Countries
		$country_total = $this->model_localisation_country->getTotalCountries($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $country_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($country_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($country_total - $this->config->get('config_pagination_admin'))) ? $country_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $country_total, ceil($country_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/country_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/country');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['country_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_2'])) {
			$url .= '&filter_iso_code_2=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_2'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_iso_code_3'])) {
			$url .= '&filter_iso_code_3=' . urlencode(html_entity_decode($this->request->get['filter_iso_code_3'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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
			'href' => $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/country.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/country', 'user_token=' . $this->session->data['user_token'] . $url);

		// Country
		if (isset($this->request->get['country_id'])) {
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->get['country_id']);
		}

		if (!empty($country_info)) {
			$data['country_id'] = $country_info['country_id'];
		} else {
			$data['country_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($country_info)) {
			$data['country_description'] = $this->model_localisation_country->getDescriptions($country_info['country_id']);
		} else {
			$data['country_description'] = [];
		}

		if (!empty($country_info)) {
			$data['iso_code_2'] = $country_info['iso_code_2'];
		} else {
			$data['iso_code_2'] = '';
		}

		if (!empty($country_info)) {
			$data['iso_code_3'] = $country_info['iso_code_3'];
		} else {
			$data['iso_code_3'] = '';
		}

		// Address Formats
		$this->load->model('localisation/address_format');

		$data['address_formats'] = $this->model_localisation_address_format->getAddressFormats();

		if (!empty($country_info)) {
			$data['address_format_id'] = $country_info['address_format_id'];
		} else {
			$data['address_format_id'] = '';
		}

		if (!empty($country_info)) {
			$data['postcode_required'] = $country_info['postcode_required'];
		} else {
			$data['postcode_required'] = 0;
		}

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($stores, $this->model_setting_store->getStores());

		if (!empty($country_info)) {
			$data['country_store'] = $this->model_localisation_country->getStores($country_info['country_id']);
		} else {
			$data['country_store'] = [0];
		}

		if (!empty($country_info)) {
			$data['status'] = $country_info['status'];
		} else {
			$data['status'] = '1';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/country_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/country');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'country_id'          => 0,
			'country_description' => [],
			'iso_code_2'          => '',
			'iso_code_3'          => '',
			'address_format_id'   => 0,
			'postcode_required'   => 0,
			'country_store'       => [],
			'status'              => 0
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['country_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 128)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (oc_strlen($post_info['iso_code_2']) != 2) {
			$json['error']['iso_code_2'] = $this->language->get('error_iso_code_2');
		}

		if (oc_strlen($post_info['iso_code_3']) != 3) {
			$json['error']['iso_code_3'] = $this->language->get('error_iso_code_3');
		}

		if (!$json) {
			// Country
			$this->load->model('localisation/country');

			if (!$post_info['country_id']) {
				$json['country_id'] = $this->model_localisation_country->addCountry($post_info);
			} else {
				$this->model_localisation_country->editCountry($post_info['country_id'], $post_info);
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
		$this->load->language('localisation/country');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Setting
		$this->load->model('setting/store');

		// Customer
		$this->load->model('customer/customer');

		// Zones
		$this->load->model('localisation/zone');

		// Geo Zones
		$this->load->model('localisation/geo_zone');

		foreach ($selected as $country_id) {
			if ($this->config->get('config_country_id') == $country_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCountryId($country_id);

			if ($store_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $store_total);
			}

			// Total Customers
			$address_total = $this->model_customer_customer->getTotalAddressesByCountryId($country_id);

			if ($address_total) {
				$json['error'] = sprintf($this->language->get('error_address'), $address_total);
			}

			// Total Zones
			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);

			if ($zone_total) {
				$json['error'] = sprintf($this->language->get('error_zone'), $zone_total);
			}

			// Total Geo Zones
			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);

			if ($zone_to_geo_zone_total) {
				$json['error'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
		}

		if (!$json) {
			// Country
			$this->load->model('localisation/country');

			foreach ($selected as $country_id) {
				$this->model_localisation_country->deleteCountry($country_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Country
	 *
	 * @return void
	 */
	public function country(): void {
		$json = [];

		if (isset($this->request->get['country_id'])) {
			$country_id = (int)$this->request->get['country_id'];
		} else {
			$country_id = 0;
		}

		// Country
		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($country_id);

		if ($country_info) {
			// Zones
			$this->load->model('localisation/zone');

			$json = ['zone' => $this->model_localisation_zone->getZonesByCountryId($country_id)] + $country_info;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
