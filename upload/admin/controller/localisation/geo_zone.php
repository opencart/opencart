<?php
namespace Opencart\Admin\Controller\Localisation;
/**
 * Class Geo Zone
 *
 * @package Opencart\Admin\Controller\Localisation
 */
class GeoZone extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/geo_zone.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/geo_zone.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/geo_zone', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('localisation/geo_zone');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
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

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('localisation/geo_zone.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Geo Zones
		$data['geo_zones'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/geo_zone');

		$results = $this->model_localisation_geo_zone->getGeoZones($filter_data);

		foreach ($results as $result) {
			$data['geo_zones'][] = ['edit' => $this->url->link('localisation/geo_zone.form', 'user_token=' . $this->session->data['user_token'] . '&geo_zone_id=' . $result['geo_zone_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('localisation/geo_zone.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_description'] = $this->url->link('localisation/geo_zone.list', 'user_token=' . $this->session->data['user_token'] . '&sort=description' . $url);

		$allowed = [
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Geo Zones
		$geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones();

		// Pagination
		$data['total'] = $geo_zone_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('catalog/geo_zone.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($geo_zone_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($geo_zone_total - $this->config->get('config_pagination_admin'))) ? $geo_zone_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $geo_zone_total, ceil($geo_zone_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/geo_zone_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['geo_zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/geo_zone.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url);

		// Geo Zone
		if (isset($this->request->get['geo_zone_id'])) {
			$this->load->model('localisation/geo_zone');

			$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone((int)$this->request->get['geo_zone_id']);
		}

		if (!empty($geo_zone_info)) {
			$data['geo_zone_id'] = $geo_zone_info['geo_zone_id'];
		} else {
			$data['geo_zone_id'] = 0;
		}

		if (!empty($geo_zone_info)) {
			$data['name'] = $geo_zone_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($geo_zone_info)) {
			$data['description'] = $geo_zone_info['description'];
		} else {
			$data['description'] = '';
		}

		// Countries
		if (!empty($geo_zone_info)) {
			$data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZones($geo_zone_info['geo_zone_id']);
		} else {
			$data['zone_to_geo_zones'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/geo_zone_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('localisation/geo_zone');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'geo_zone_id'      => 0,
			'name'             => '',
			'description'      => '',
			'zone_to_geo_zone' => []
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!oc_validate_length($post_info['description'], 3, 255)) {
			$json['error']['description'] = $this->language->get('error_description');
		}

		if (!isset($this->request->post['zone_to_geo_zone'])) {


		}


		if (!$json) {
			// Geo Zone
			$this->load->model('localisation/geo_zone');

			if (!$post_info['geo_zone_id']) {
				$json['geo_zone_id'] = $this->model_localisation_geo_zone->addGeoZone($post_info);
			} else {
				$this->model_localisation_geo_zone->editGeoZone($post_info['geo_zone_id'], $post_info);
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
		$this->load->language('localisation/geo_zone');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Tax Rate
		$this->load->model('localisation/tax_rate');

		// Total Tax Rates
		foreach ($selected as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$json['error'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
			}
		}

		if (!$json) {
			// Geo Zone
			$this->load->model('localisation/geo_zone');

			foreach ($selected as $geo_zone_id) {
				$this->model_localisation_geo_zone->deleteGeoZone($geo_zone_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
