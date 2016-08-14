<?php
class ControllerLocalisationWeightClass extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/weight_class');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/weight_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_weight_class->addWeightClass($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/weight_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_weight_class->editWeightClass($this->request->get['weight_class_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/weight_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/weight_class');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $weight_class_id) {
				$this->model_localisation_weight_class->deleteWeightClass($weight_class_id);
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

			$this->response->redirect($this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'title';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['add'] = $this->url->link('localisation/weight_class/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('localisation/weight_class/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['weight_classes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$weight_class_total = $this->model_localisation_weight_class->getTotalWeightClasses();

		$results = $this->model_localisation_weight_class->getWeightClasses($filter_data);

		foreach ($results as $result) {
			$data['weight_classes'][] = array(
				'weight_class_id' => $result['weight_class_id'],
				'title'           => $result['title'] . (($result['weight_class_id'] == $this->config->get('config_weight_class_id')) ? $this->language->get('text_default') : null),
				'unit'            => $result['unit'],
				'value'           => $result['value'],
				'edit'            => $this->url->link('localisation/weight_class/edit', 'token=' . $this->session->data['token'] . '&weight_class_id=' . $result['weight_class_id'] . $url, 'SSL')
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_title'] = $this->language->get('column_title');
		$data['column_unit'] = $this->language->get('column_unit');
		$data['column_value'] = $this->language->get('column_value');
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

		$data['sort_title'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . '&sort=title' . $url, 'SSL');
		$data['sort_unit'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . '&sort=unit' . $url, 'SSL');
		$data['sort_value'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . '&sort=value' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $weight_class_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($weight_class_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($weight_class_total - $this->config->get('config_limit_admin'))) ? $weight_class_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $weight_class_total, ceil($weight_class_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/weight_class_list.tpl', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['weight_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_unit'] = $this->language->get('entry_unit');
		$data['entry_value'] = $this->language->get('entry_value');

		$data['help_value'] = $this->language->get('help_value');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}

		if (isset($this->error['unit'])) {
			$data['error_unit'] = $this->error['unit'];
		} else {
			$data['error_unit'] = array();
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
			'href' => $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['weight_class_id'])) {
			$data['action'] = $this->url->link('localisation/weight_class/add', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('localisation/weight_class/edit', 'token=' . $this->session->data['token'] . '&weight_class_id=' . $this->request->get['weight_class_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('localisation/weight_class', 'token=' . $this->session->data['token'] . $url, 'SSL');

		if (isset($this->request->get['weight_class_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$weight_class_info = $this->model_localisation_weight_class->getWeightClass($this->request->get['weight_class_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['weight_class_description'])) {
			$data['weight_class_description'] = $this->request->post['weight_class_description'];
		} elseif (isset($this->request->get['weight_class_id'])) {
			$data['weight_class_description'] = $this->model_localisation_weight_class->getWeightClassDescriptions($this->request->get['weight_class_id']);
		} else {
			$data['weight_class_description'] = array();
		}

		if (isset($this->request->post['value'])) {
			$data['value'] = $this->request->post['value'];
		} elseif (!empty($weight_class_info)) {
			$data['value'] = $weight_class_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/weight_class_form.tpl', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['weight_class_description'] as $language_id => $value) {
			if ((utf8_strlen($value['title']) < 3) || (utf8_strlen($value['title']) > 32)) {
				$this->error['title'][$language_id] = $this->language->get('error_title');
			}

			if (!$value['unit'] || (utf8_strlen($value['unit']) > 4)) {
				$this->error['unit'][$language_id] = $this->language->get('error_unit');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/weight_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $weight_class_id) {
			if ($this->config->get('config_weight_class_id') == $weight_class_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$product_total = $this->model_catalog_product->getTotalProductsByWeightClassId($weight_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}
}