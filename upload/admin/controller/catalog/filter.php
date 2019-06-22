<?php
class ControllerCatalogFilter extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_filter->addFilter($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/filter'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_filter->editFilter($this->request->get['filter_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('filter_group_id');

			$this->response->redirect($this->provider->link('catalog/filter'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/filter');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $filter_group_id) {
				$this->model_catalog_filter->deleteFilter($filter_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/filter'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('sort' => 'fgd.name'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$filter_total = $this->model_catalog_filter->getTotalFilterGroups();

		$results = $this->model_catalog_filter->getFilterGroups($filter_data);

		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/filter/add');
		$data['delete'] = $this->provider->link('catalog/filter/delete');

		$data['filters'] = array();

		foreach ($results as $result) {
			$data['filters'][] = array(
				'filter_group_id' => $result['filter_group_id'],
				'name'            => $result['name'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->provider->link('catalog/filter/edit', array('filter_group_id' => $result['filter_group_id']))
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

		$sorts = array('sort_name' => 'fgd.name', 'sort_order' => 'fg.sort_order');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/filter', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $filter_total);

		$data['results'] = $this->load->controller('common/pagination/results', $filter_total);

		$data['sort'] = $this->provider->getParser('sort');
		$data['order'] = $this->provider->getParser('order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_list', $data));
	}

	protected function getForm() {
		$this->provider->detach('filter_group_id');

		$data['text_form'] = $this->request->hasGet('filter_group_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

		$this->breadcrumbs->setDefaults();

		$this->breadcrumbs->set((string)$data['text_form']);

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['group'])) {
			$data['error_group'] = $this->error['group'];
		} else {
			$data['error_group'] = array();
		}

		if (isset($this->error['filter'])) {
			$data['error_filter'] = $this->error['filter'];
		} else {
			$data['error_filter'] = array();
		}

		if ($this->request->hasGet('filter_group_id')) {
			$data['action'] = $this->provider->link('catalog/filter/edit', array('filter_group_id' => $this->request->get['filter_group_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/filter/add');
		}

		$data['cancel'] = $this->provider->link('catalog/filter');

		if ($this->provider->link('filter_group_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$filter_group_info = $this->model_catalog_filter->getFilterGroup($this->request->get['filter_group_id']);
		}

		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('filter_group_description')) {
			$data['filter_group_description'] = $this->request->post['filter_group_description'];
		} elseif (!empty($filter_group_info)) {
			$data['filter_group_description'] = $this->model_catalog_filter->getFilterGroupDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filter_group_description'] = array();
		}

		if ($this->request->hasPost('sort_order')) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($filter_group_info)) {
			$data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		if ($this->request->hasPost('filter')) {
			$data['filters'] = $this->request->post['filter'];
		} elseif (!empty($filter_group_info)) {
			$data['filters'] = $this->model_catalog_filter->getFilterDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filters'] = array();
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['filter_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['group'][$language_id] = $this->language->get('error_group');
			}
		}

		if ($this->request->hasPost('filter')) {
			foreach ($this->request->post['filter'] as $key => $filter) {
				foreach ($filter['filter_description'] as $language_id => $filter_description) {
					if ((utf8_strlen($filter_description['name']) < 1) || (utf8_strlen($filter_description['name']) > 64)) {
						$this->error['filter'][$key][$language_id] = $this->language->get('error_name');
					}
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if ($this->request->hasGet('filter_name')) {
			$this->load->model('catalog/filter');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$filters = $this->model_catalog_filter->getFilters($filter_data);

			foreach ($filters as $filter) {
				$json[] = array(
					'filter_id' => $filter['filter_id'],
					'name'      => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}