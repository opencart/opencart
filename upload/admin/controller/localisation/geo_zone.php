<?php
namespace Opencart\Admin\Controller\Localisation;
class GeoZone extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('localisation/geo_zone');

		$this->document->setTitle($this->language->get('heading_title'));

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
			'href' => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/geo_zone|form', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->getList();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/geo_zone', $data));
	}

	public function list(): void {
		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
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

		$data['add'] = $this->url->link('localisation/geo_zone|add', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['geo_zones'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$geo_zone_total = $this->model_localisation_geo_zone->getTotalGeoZones();

		$results = $this->model_localisation_geo_zone->getGeoZones($filter_data);

		foreach ($results as $result) {
			$data['geo_zones'][] = [
				'geo_zone_id' => $result['geo_zone_id'],
				'name'        => $result['name'],
				'description' => $result['description'],
				'edit'        => $this->url->link('localisation/geo_zone|edit', 'user_token=' . $this->session->data['user_token'] . '&geo_zone_id=' . $result['geo_zone_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_description'] = $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . '&sort=description' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $geo_zone_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($geo_zone_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($geo_zone_total - $this->config->get('config_pagination_admin'))) ? $geo_zone_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $geo_zone_total, ceil($geo_zone_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('localisation/geo_zone_list', $data));
	}

	protected function getForm(): void {
		$data['text_form'] = !isset($this->request->get['geo_zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		if (!isset($this->request->get['geo_zone_id'])) {
			$data['action'] = $this->url->link('localisation/geo_zone|add', 'user_token=' . $this->session->data['user_token'] . $url);
		} else {
			$data['action'] = $this->url->link('localisation/geo_zone|edit', 'user_token=' . $this->session->data['user_token'] . '&geo_zone_id=' . $this->request->get['geo_zone_id'] . $url);
		}

		$data['back'] = $this->url->link('localisation/geo_zone', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['geo_zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$geo_zone_info = $this->model_localisation_geo_zone->getGeoZone($this->request->get['geo_zone_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

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

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (!empty($geo_zone_info)) {
			$data['zone_to_geo_zones'] = $this->model_localisation_geo_zone->getZoneToGeoZones($this->request->get['geo_zone_id']);
		} else {
			$data['zone_to_geo_zones'] = [];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/geo_zone_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/geo_zone');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen($this->request->post['description']) > 255)) {
			$json['error']['description'] = $this->language->get('error_description');
		}

		$this->load->model('localisation/geo_zone');

		$this->model_localisation_geo_zone->addGeoZone($this->request->post);
		$this->model_localisation_geo_zone->editGeoZone($this->request->get['geo_zone_id'], $this->request->post);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/geo_zone');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/geo_zone')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/tax_rate');

		foreach ($selected as $geo_zone_id) {
			$tax_rate_total = $this->model_localisation_tax_rate->getTotalTaxRatesByGeoZoneId($geo_zone_id);

			if ($tax_rate_total) {
				$json['error'] = sprintf($this->language->get('error_tax_rate'), $tax_rate_total);
			}
		}

		if (!$json) {
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
