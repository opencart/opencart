<?php
class ControllerCatalogAttribute extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		$this->getList();
	}

	public function add() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->addAttribute($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/attribute'));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_attribute->editAttribute($this->request->get['attribute_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->provider->detach('attribute_id');
			
			$this->response->redirect($this->provider->link('catalog/attribute'));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/attribute');

		if ($this->request->hasPost('selected') && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute($attribute_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->provider->link('catalog/attribute'));
		}

		$this->getList();
	}

	protected function getList() {
		$this->provider->parser(array('sort' => 'ad.name'));

		$filter_data = array_merge($this->provider->getParams(), $this->provider->default_filter);

		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();

		$results = $this->model_catalog_attribute->getAttributes($filter_data);

		$this->breadcrumbs->setDefaults();

		$data['add'] = $this->provider->link('catalog/attribute/add');
		$data['delete'] = $this->provider->link('catalog/attribute/delete');

		$data['attributes'] = array();

		foreach ($results as $result) {
			$data['attributes'][] = array(
				'attribute_id'    => $result['attribute_id'],
				'name'            => $result['name'],
				'attribute_group' => $result['attribute_group'],
				'sort_order'      => $result['sort_order'],
				'edit'            => $this->provider->link('catalog/attribute/edit', array('attribute_id' => $result['attribute_id']))
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

		$sorts = array('sort_name' => 'ad.name', 'sort_attribute_group' => 'attribute_group', 'sort_order' => 'a.sort_order');

		foreach ($sorts as $key => $sort) {
			$data[$key] = $this->provider->link('catalog/attribute', array('sort' => $sort, 'order' => $this->provider->order));
		}

		$data['pagination'] = $this->load->controller('common/pagination', $attribute_total);

		$data['results'] = $this->load->controller('common/pagination/results', $attribute_total);

		$data['sort'] = $this->provider->getParser('sort');
		$data['order'] = $this->provider->getParser('order');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_list', $data));
	}

	protected function getForm() {
		$this->provider->detach('attribute_id');
		
		$data['text_form'] = $this->request->hasGet('attribute_id') ? $this->language->get('text_edit') : $this->language->get('text_add');

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

		if (isset($this->error['attribute_group'])) {
			$data['error_attribute_group'] = $this->error['attribute_group'];
		} else {
			$data['error_attribute_group'] = '';
		}

		if ($this->request->hasGet('attribute_id')) {
			$data['action'] = $this->provider->link('catalog/attribute/edit', array('attribute_id' => $this->request->get['attribute_id']));
		} else {
			$data['action'] = $this->provider->link('catalog/attribute/add');
		}

		$data['cancel'] = $this->provider->link('catalog/attribute');

		if ($this->request->hasGet('attribute_id') && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$attribute_info = $this->model_catalog_attribute->getAttribute($this->request->get['attribute_id']);
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if ($this->request->hasPost('attribute_description')) {
			$data['attribute_description'] = $this->request->post['attribute_description'];
		} elseif ($this->request->hasGet('attribute_id')) {
			$data['attribute_description'] = $this->model_catalog_attribute->getAttributeDescriptions($this->request->get['attribute_id']);
		} else {
			$data['attribute_description'] = array();
		}

		if ($this->request->hasPost('attribute_group_id')) {
			$data['attribute_group_id'] = $this->request->post['attribute_group_id'];
		} elseif (!empty($attribute_info)) {
			$data['attribute_group_id'] = $attribute_info['attribute_group_id'];
		} else {
			$data['attribute_group_id'] = '';
		}

		$this->load->model('catalog/attribute_group');

		$data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

		if ($this->request->hasPost('sort_order')) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($attribute_info)) {
			$data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['breadcrumbs'] = $this->breadcrumbs->render();
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['attribute_group_id']) {
			$this->error['attribute_group'] = $this->language->get('error_attribute_group');
		}

		foreach ($this->request->post['attribute_description'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 64)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/product');

		foreach ($this->request->post['selected'] as $attribute_id) {
			$product_total = $this->model_catalog_product->getTotalProductsByAttributeId($attribute_id);

			if ($product_total) {
				$this->error['warning'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		return !$this->error;
	}

	public function autocomplete() {
		$json = array();

		if ($this->request->hasGet('filter_name')) {
			$this->load->model('catalog/attribute');

			$filter_data = array(
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => 5
			);

			$results = $this->model_catalog_attribute->getAttributes($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'attribute_id'    => $result['attribute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
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
