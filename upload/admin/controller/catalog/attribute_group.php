<?php
class ControllerCatalogAttributeGroup extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_group->addAttributeGroup($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/attribute_group'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute_group->editAttributeGroup($this->request->get['attribute_group_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('attribute_group_id');

			$this->response->redirect($this->provider->link('catalog/attribute_group'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute_group');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_group_id) {
				$this->model_catalog_attribute_group->deleteAttributeGroup($attribute_group_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/attribute_group'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('sort' => 'agd.name'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$attribute_group_total = $this->model_catalog_attribute_group->getTotalAttributeGroups();

		$results = $this->model_catalog_attribute_group->getAttributeGroups($filter_data);

		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/attribute_group/add');
		$data['delete'] = $this->provider->link('catalog/attribute_group/delete');

		$data['attribute_groups'] = array();

		foreach ($results as $result) {
			$data['attribute_groups'][] = array(
				'attribute_group_id' => $result['attribute_group_id'],
				'name'               => $result['name'],
				'sort_order'         => $result['sort_order'],
				'edit'               => $this->provider->link('catalog/attribute_group/edit', array('attribute_group_id' => $result['attribute_group_id']))
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

		$sorts = array('sort_name' => 'agd.name', 'sort_order' => 'ag.sort_order');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/attribute_group', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $attribute_group_total);

		$data['results'] = $this->load->controller('common/pagination/results', $attribute_group_total);

		$data['sort'] = $this->provider->getParser('sort');
		$data['order'] = $this->provider->getParser('order');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_group_list', $data));
	}

	protected function getForm() {
		$this->provider->detach('attribute_group_id');
		
		$data['text_form'] = $this->request->hasGet('attribute_group_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

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

		if ($this->request->hasGet('attribute_group_id')) {
			$data['action'] = $this->provider->link('catalog/attribute_group/edit', array('attribute_group_id' => $this->request->get['attribute_group_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/attribute_group/add');
		}

		$data['cancel'] = $this->provider->link('catalog/attribute_group');

		if ($this->request->hasGet('attribute_group_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($this->request->get['attribute_group_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('attribute_group_description')) {
			$data['attribute_group_description'] = $this->request->post['attribute_group_description'];
		} elseif (!empty($attribute_group_info)) {
			$data['attribute_group_description'] = $this->model_catalog_attribute_group->getAttributeGroupDescriptions($this->request->get['attribute_group_id']);
		} else {
			$data['attribute_group_description'] = array();
		}

		if ($this->request->hasPost('sort_order')) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_group_info)) {
			$data['sort_order'] = $attribute_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_group_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['attribute_group_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/attribute');

		foreach ($this->request->post['selected'] as $attribute_group_id) {
			$attribute_total = $this->model_catalog_attribute->getTotalAttributesByAttributeGroupId($attribute_group_id);

			if ($attribute_total) {
				$this->error['warning'] = sprintf($this->language->get('error_attribute'), $attribute_total);
			}
		}

		return !$this->error;
	}
}
