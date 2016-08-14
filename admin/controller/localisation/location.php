<?php
class ControllerLocalisationLocation extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/location');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/location');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/location');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/location');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_location->addLocation($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/location');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/location');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_location->editLocation($this->request->get['location_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/location');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/location');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $location_id) {
				$this->model_localisation_location->deleteLocation($location_id);
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

			$this->response->redirect($this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'l.name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
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

		$data['breadcrumbs'] =   array();

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] =   array(
			'text' =>  $this->language->get('heading_title'),
			'href' =>  $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('localisation/location/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/location/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['location'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$location_total = $this->model_localisation_location->getTotalLocations();

		$results = $this->model_localisation_location->getLocations($filter_data);

		foreach ($results as $result) {
			$data['location'][] =   array(
				'location_id' => $result['location_id'],
				'name'        => $result['name'],
				'address'     => $result['address'],
				'edit'        => $this->url->link('localisation/location/edit', 'token=' . $this->session->data['token'] . '&location_id=' . $result['location_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_name'] = $this->language->get('column_name');
		$data['column_address'] = $this->language->get('column_address');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

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

		$data['sort_name'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$data['sort_address'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . '&sort=address' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $location_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($location_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($location_total - $this->config->get('config_limit_admin'))) ? $location_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $location_total, ceil($location_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/location_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['location_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_geocode'] = $this->language->get('text_geocode');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_address'] = $this->language->get('entry_address');
		$data['entry_geocode'] = $this->language->get('entry_geocode');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_open'] = $this->language->get('entry_open');
		$data['entry_comment'] = $this->language->get('entry_comment');

		$data['help_geocode'] = $this->language->get('help_geocode');
		$data['help_open'] = $this->language->get('help_open');
		$data['help_comment'] = $this->language->get('help_comment');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

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

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['location_id'])) {
			$data['action'] = $this->url->link('localisation/location/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/location/edit', 'token=' . $this->session->data['token'] .  '&location_id=' . $this->request->get['location_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['location_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$location_info = $this->model_localisation_location->getLocation($this->request->get['location_id']);
		}

		$data['token'] = $this->session->data['token'];

		$this->load->model('setting/store');

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($location_info)) {
			$data['name'] = $location_info['name'];
		} else {
			$data['name'] =   '';
		}

		if (isset($this->request->post['address'])) {
			$data['address'] = $this->request->post['address'];
		} elseif (!empty($location_info)) {
			$data['address'] = $location_info['address'];
		} else {
			$data['address'] = '';
		}

		if (isset($this->request->post['geocode'])) {
			$data['geocode'] = $this->request->post['geocode'];
		} elseif (!empty($location_info)) {
			$data['geocode'] = $location_info['geocode'];
		} else {
			$data['geocode'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($location_info)) {
			$data['telephone'] = $location_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($location_info)) {
			$data['fax'] = $location_info['fax'];
		} else {
			$data['fax'] = '';
		}

		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($location_info)) {
			$data['image'] = $location_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
		} elseif (!empty($location_info) && is_file(DIR_IMAGE . $location_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($location_info['image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['open'])) {
			$data['open'] = $this->request->post['open'];
		} elseif (!empty($location_info)) {
			$data['open'] = $location_info['open'];
		} else {
			$data['open'] = '';
		}

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (!empty($location_info)) {
			$data['comment'] = $location_info['comment'];
		} else {
			$data['comment'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/location_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/location')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['address']) < 3) || (utf8_strlen($this->request->post['address']) > 128)) {
			$this->error['address'] = $this->language->get('error_address');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/location')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}