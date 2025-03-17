<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Country
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class ProductIdentifier extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/product_identifier');

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
			'href' => $this->url->link('localisation/product_identifier', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/product_identifier.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/product_identifier.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/product_identifier', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/product_identifier');

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

		$data['action'] = $this->url->link('localisation/product_identifier.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Country
		$data['product_identifiers'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/product_identifier');

		$results = $this->model_localisation_product_identifier->getProductIdentifiers($filter_data);

		foreach ($results as $result) {
			$data['product_identifiers'][] = ['edit' => $this->url->link('localisation/product_identifier.form', 'user_token=' . $this->session->data['user_token'] . '&product_identifier_id=' . $result['product_identifier_id'] . $url)] + $result;
		}

		$url = '';

		$identifier_total = $this->model_localisation_product_identifier->getTotalCountries($filter_data);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $identifier_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/country.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($identifier_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($identifier_total - $this->config->get('config_pagination_admin'))) ? $identifier_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $identifier_total, ceil($identifier_total / $this->config->get('config_pagination_admin')));

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

		if (isset($this->request->get['country_id'])) {
			$this->load->model('localisation/country');

			$country_info = $this->model_localisation_country->getCountry((int)$this->request->get['country_id']);
		}

		if (isset($this->request->get['country_id'])) {
			$data['country_id'] = (int)$this->request->get['country_id'];
		} else {
			$data['country_id'] = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['country_id'])) {
			$data['country_description'] = $this->model_localisation_country->getDescriptions($this->request->get['country_id']);
		} else {
			$data['country_description'] = [];
		}

		if (!empty($country_info)) {
			$data['name'] = $country_info['name'];
		} else {
			$data['name'] = '';
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

		// Address Format
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

		$post_info = $this->request->post;

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
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/country')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Store
		$this->load->model('setting/store');

		// Customer
		$this->load->model('customer/customer');

		// Zone
		$this->load->model('localisation/zone');

		// Geo Zone
		$this->load->model('localisation/geo_zone');

		foreach ($selected as $country_id) {
			if ($this->config->get('config_country_id') == $country_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByCountryId($country_id);

			if ($store_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$address_total = $this->model_customer_customer->getTotalAddressesByCountryId($country_id);

			if ($address_total) {
				$json['error'] = sprintf($this->language->get('error_address'), $address_total);
			}

			$zone_total = $this->model_localisation_zone->getTotalZonesByCountryId($country_id);

			if ($zone_total) {
				$json['error'] = sprintf($this->language->get('error_zone'), $zone_total);
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByCountryId($country_id);

			if ($zone_to_geo_zone_total) {
				$json['error'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
		}

		if (!$json) {
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

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($country_id);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = [
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format_id' => $country_info['address_format_id'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($country_id),
				'status'            => $country_info['status']
			];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
