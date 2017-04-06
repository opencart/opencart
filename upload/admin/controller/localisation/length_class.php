<?php
class ControllerLocalisationLengthClass extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/length_class');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/length_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_length_class->addLengthClass($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/length_class');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_length_class->editLengthClass($this->request->get['length_class_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/length_class');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/length_class');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $length_class_id) {
				$this->model_localisation_length_class->deleteLengthClass($length_class_id);
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

			$this->response->redirect($this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true));
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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/length_class/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/length_class/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['length_classes'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$length_class_total = $this->model_localisation_length_class->getTotalLengthClasses();

		$results = $this->model_localisation_length_class->getLengthClasses($filter_data);

		foreach ($results as $result) {
			$data['length_classes'][] = array(
				'length_class_id' => $result['length_class_id'],
				'title'           => $result['title'] . (($result['length_class_id'] == $this->config->get('config_length_class_id')) ? $this->language->get('text_default') : null),
				'unit'            => $result['unit'],
				'value'           => $result['value'],
				'edit'            => $this->url->link('localisation/length_class/edit', 'token=' . $this->session->data['token'] . '&length_class_id=' . $result['length_class_id'] . $url, true)
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

		$data['sort_title'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . '&sort=title' . $url, true);
		$data['sort_unit'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . '&sort=unit' . $url, true);
		$data['sort_value'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . '&sort=value' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $length_class_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($length_class_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($length_class_total - $this->config->get('config_limit_admin'))) ? $length_class_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $length_class_total, ceil($length_class_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/length_class_list', $data));
	}

	protected function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_form'] = !isset($this->request->get['length_class_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['length_class_id'])) {
			$data['action'] = $this->url->link('localisation/length_class/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/length_class/edit', 'token=' . $this->session->data['token'] . '&length_class_id=' . $this->request->get['length_class_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('localisation/length_class', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['length_class_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$length_class_info = $this->model_localisation_length_class->getLengthClass($this->request->get['length_class_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['length_class_description'])) {
			$data['length_class_description'] = $this->request->post['length_class_description'];
		} elseif (isset($this->request->get['length_class_id'])) {
			$data['length_class_description'] = $this->model_localisation_length_class->getLengthClassDescriptions($this->request->get['length_class_id']);
		} else {
			$data['length_class_description'] = array();
		}

		if (isset($this->request->post['value'])) {
			$data['value'] = $this->request->post['value'];
		} elseif (!empty($length_class_info)) {
			$data['value'] = $length_class_info['value'];
		} else {
			$data['value'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/length_class_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['length_class_description'] as $language_id => $value) {
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
		if (!$this->user->hasPermission('modify', 'localisation/length_class')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $length_class_id) {
			if ($this->config->get('config_length_class_id') == $length_class_id) {
				$this->error['warning'] = $this->language->get('error_default');
			}

			$product_total = $this->model_catalog_product->getTotalProductsByLengthClassId($length_class_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}
}