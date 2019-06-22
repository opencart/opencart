<?php
class ControllerCatalogRecurring extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_recurring->addRecurring($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/recurring'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_recurring->editRecurring($this->request->get['recurring_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('recurring_id');

			$this->response->redirect($this->provider->link('catalog/recurring'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $recurring_id) {
				$this->model_catalog_recurring->deleteRecurring($recurring_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/recurring'));
		}

		$this->getList();
	}

	public function copy() {
		$this->load->language('catalog/recurring');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/recurring');

		if ($this->request->hasPost('selected') && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $recurring_id) {
				$this->model_catalog_recurring->copyRecurring($recurring_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/recurring'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('sort' => 'rd.name'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$recurring_total = $this->model_catalog_recurring->getTotalRecurrings();

		$results = $this->model_catalog_recurring->getRecurrings($filter_data);

		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/recurring/add');
		$data['copy'] = $this->provider->link('catalog/recurring/copy');
		$data['delete'] = $this->provider->link('catalog/recurring/delete');

		$data['recurrings'] = array();

		foreach ($results as $result) {
			$data['recurrings'][] = array(
				'recurring_id' => $result['recurring_id'],
				'name'         => $result['name'],
				'sort_order'   => $result['sort_order'],
				'edit'         => $this->provider->link('catalog/recurring/edit', array('recurring_id' => $result['recurring_id']))
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

		if ($this->request->hasPost('selected')) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$sorts = array('sort_name' => 'rd.name', 'sort_order' => 'r.sort_order');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/recurring', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $recurring_total);

		$data['results'] = $this->load->controller('common/pagination/results', $recurring_total);

		$data['sort'] = $this->provider->getParser('sort');
		$data['order'] = $this->provider->getParser('order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/recurring_list', $data));
	}

	protected function getForm() {
		$this->provider->detach('recurring_id');

		$data['text_form'] = $this->request->hasGet('recurring_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

		$this->breadcrumbs->setDefaults();

		$this->breadcrumbs->set((string)$data['text_form']);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if ($this->request->hasGet('recurring_id')) {
			$data['action'] = $this->provider->link('catalog/recurring/edit', array('recurring_id' => $this->request->get['recurring_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/recurring/add');
		}

		$data['cancel'] = $this->provider->link('catalog/recurring');

		if ($this->request->hasGet('recurring_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$recurring_info = $this->model_catalog_recurring->getRecurring($this->request->get['recurring_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('recurring_description')) {
			$data['recurring_description'] = $this->request->post['recurring_description'];
		} elseif (!empty($recurring_info)) {
			$data['recurring_description'] = $this->model_catalog_recurring->getRecurringDescription($recurring_info['recurring_id']);
		} else {
			$data['recurring_description'] = array();
		}

		$data['frequencies'] = array();

		$frequencies = array('day', 'week', 'semi_month', 'month', 'year');

		foreach ($frequencies as $frequencie) {
			$data['frequencies'][] = array(
				'text'  => $this->language->get('text_' . $frequencie),
				'value' => $frequencie
			);
		}

		$variables = array(
			'price' => 0,
			'frequency' => '',
			'duration' => 0,
			'cycle' => 1,
			'status' => 0,
			'trial_price' => 0.00,
			'trial_frequency' => '',
			'trial_duration' => '0',
			'trial_cycle' => '1',
			'trial_status' => 0,
			'sort_order' => 0
		);

		foreach ($variables as $key => $value) {
			if ($this->request->hasPost($key)) {
				$data[$key] = $this->request->post[$key];
			} elseif (!empty($recurring_info)) {
				$data[$key] = $recurring_info[$key];
			} else {
				$data[$key] = $value;
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/recurring_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['recurring_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $recurring_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByProfileId($recurring_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	protected function validateCopy() {
		if (!$this->user->hasPermission('modify', 'catalog/recurring')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}