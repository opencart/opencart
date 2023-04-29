<?php
namespace Opencart\Admin\Controller\Localisation;
class Zone extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_country'])) {
			$filter_country = (string)$this->request->get['filter_country'];
		} else {
			$filter_country = '';
		}

		if (isset($this->request->get['filter_code'])) {
			$filter_code = (string)$this->request->get['filter_code'];
		} else {
			$filter_code = '';
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
			'href' => $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('localisation/zone.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('localisation/zone.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['filter_name'] = $filter_name;
		$data['filter_country'] = $filter_country;
		$data['filter_code'] = $filter_code;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/zone', $data));
	}

	public function list(): void {
		$this->load->language('localisation/zone');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_country'])) {
			$filter_country = (string)$this->request->get['filter_country'];
		} else {
			$filter_country = '';
		}

		if (isset($this->request->get['filter_code'])) {
			$filter_code = (string)$this->request->get['filter_code'];
		} else {
			$filter_code = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'c.name';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . urlencode(html_entity_decode($this->request->get['filter_country'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
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

		$data['action'] = $this->url->link('localisation/zone.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['zones'] = [];

		$filter_data = [
			'filter_name'    => $filter_name,
			'filter_country' => $filter_country,
			'filter_code'    => $filter_code,
			'sort'           => $sort,
			'order'          => $order,
			'start'          => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'          => $this->config->get('config_pagination_admin')
		];

		$this->load->model('localisation/zone');

		$zone_total = $this->model_localisation_zone->getTotalZones($filter_data);

		$results = $this->model_localisation_zone->getZones($filter_data);

		foreach ($results as $result) {
			$data['zones'][] = [
				'zone_id' => $result['zone_id'],
				'country' => $result['country'],
				'name'    => $result['name'] . (($result['zone_id'] == $this->config->get('config_zone_id')) ? $this->language->get('text_default') : ''),
				'code'    => $result['code'],
				'edit'    => $this->url->link('localisation/zone.form', 'user_token=' . $this->session->data['user_token'] . '&zone_id=' . $result['zone_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . urlencode(html_entity_decode($this->request->get['filter_country'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_country'] = $this->url->link('localisation/zone.list', 'user_token=' . $this->session->data['user_token'] . '&sort=c.name' . $url);
		$data['sort_name'] = $this->url->link('localisation/zone.list', 'user_token=' . $this->session->data['user_token'] . '&sort=z.name' . $url);
		$data['sort_code'] = $this->url->link('localisation/zone.list', 'user_token=' . $this->session->data['user_token'] . '&sort=z.code' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . urlencode(html_entity_decode($this->request->get['filter_country'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $zone_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('localisation/zone.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($zone_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($zone_total - $this->config->get('config_pagination_admin'))) ? $zone_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $zone_total, ceil($zone_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('localisation/zone_list', $data);
	}

	public function form(): void {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_country'])) {
			$url .= '&filter_country=' . urlencode(html_entity_decode($this->request->get['filter_country'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_code'])) {
			$url .= '&filter_code=' . urlencode(html_entity_decode($this->request->get['filter_code'], ENT_QUOTES, 'UTF-8'));
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
			'href' => $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('localisation/zone.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['zone_id'])) {
			$this->load->model('localisation/zone');

			$zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);
		}

		if (isset($this->request->get['zone_id'])) {
			$data['zone_id'] = (int)$this->request->get['zone_id'];
		} else {
			$data['zone_id'] = 0;
		}

		if (!empty($zone_info)) {
			$data['status'] = $zone_info['status'];
		} else {
			$data['status'] = '1';
		}

		if (!empty($zone_info)) {
			$data['name'] = $zone_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($zone_info)) {
			$data['code'] = $zone_info['code'];
		} else {
			$data['code'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		if (!empty($zone_info)) {
			$data['country_id'] = $zone_info['country_id'];
		} else {
			$data['country_id'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/zone_form', $data));
	}

	public function save(): void {
		$this->load->language('localisation/zone');

		$json = [];

		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((oc_strlen($this->request->post['name']) < 1) || (oc_strlen($this->request->post['name']) > 64)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!$json) {
			$this->load->model('localisation/zone');

			if (!$this->request->post['zone_id']) {
				$json['zone_id'] = $this->model_localisation_zone->addZone($this->request->post);
			} else {
				$this->model_localisation_zone->editZone($this->request->post['zone_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('localisation/zone');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('customer/customer');
		$this->load->model('localisation/geo_zone');

		foreach ($selected as $zone_id) {
			if ($this->config->get('config_zone_id') == $zone_id) {
				$json['error'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);

			if ($store_total) {
				$json['error'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$address_total = $this->model_customer_customer->getTotalAddressesByZoneId($zone_id);

			if ($address_total) {
				$json['error'] = sprintf($this->language->get('error_address'), $address_total);
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);

			if ($zone_to_geo_zone_total) {
				$json['error'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
		}

		if (!$json) {
			$this->load->model('localisation/zone');

			foreach ($selected as $zone_id) {
				$this->model_localisation_zone->deleteZone($zone_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
