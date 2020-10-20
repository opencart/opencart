<?php
class ControllerLocalisationZone extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_zone->addZone($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_zone->editZone($this->request->get['zone_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/zone');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/zone');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $zone_id) {
				$this->model_localisation_zone->deleteZone($zone_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

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

			$this->response->redirect($this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/zone/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/zone/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['zones'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$zone_total = $this->model_localisation_zone->getTotalZones();

		$results = $this->model_localisation_zone->getZones($filter_data);

		foreach ($results as $result) {
			$data['zones'][] = array(
				'zone_id' => $result['zone_id'],
				'country' => $result['country'],
				'name'    => $result['name'] . (($result['zone_id'] == $this->config->get('config_zone_id')) ? $this->language->get('text_default') : null),
				'code'    => $result['code'],
				'edit'    => $this->url->link('localisation/zone/edit', 'user_token=' . $this->session->data['user_token'] . '&zone_id=' . $result['zone_id'] . $url, true)
			);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
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

		$data['sort_country'] = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . '&sort=c.name' . $url, true);
		$data['sort_name'] = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . '&sort=z.name' . $url, true);
		$data['sort_code'] = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . '&sort=z.code' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $zone_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($zone_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($zone_total - $this->config->get('config_limit_admin'))) ? $zone_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $zone_total, ceil($zone_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/zone_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['zone_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['zone_id'])) {
			$data['action'] = $this->url->link('localisation/zone/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/zone/edit', 'user_token=' . $this->session->data['user_token'] . '&zone_id=' . $this->request->get['zone_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/zone', 'user_token=' . $this->session->data['user_token'] . $url, true);

		if (isset($this->request->get['zone_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($zone_info)) {
			$data['status'] = $zone_info['status'];
		} else {
			$data['status'] = '1';
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($zone_info)) {
			$data['name'] = $zone_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($zone_info)) {
			$data['code'] = $zone_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($zone_info)) {
			$data['country_id'] = $zone_info['country_id'];
		} else {
			$data['country_id'] = '';
		}

		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/zone_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/zone')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('setting/store');
		$this->load->model('customer/customer');
		$this->load->model('localisation/geo_zone');

		foreach ($this->request->post['selected'] as $zone_id) {
			if ($this->config->get('config_zone_id') == $zone_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$store_total = $this->model_setting_store->getTotalStoresByZoneId($zone_id);

			if ($store_total) {
				$this->error['warning'] = sprintf($this->language->get('error_store'), $store_total);
			}

			$address_total = $this->model_customer_customer->getTotalAddressesByZoneId($zone_id);

			if ($address_total) {
				$this->error['warning'] = sprintf($this->language->get('error_address'), $address_total);
			}

			$zone_to_geo_zone_total = $this->model_localisation_geo_zone->getTotalZoneToGeoZoneByZoneId($zone_id);

			if ($zone_to_geo_zone_total) {
				$this->error['warning'] = sprintf($this->language->get('error_zone_to_geo_zone'), $zone_to_geo_zone_total);
			}
		}

		return !$this->error;
	}
}