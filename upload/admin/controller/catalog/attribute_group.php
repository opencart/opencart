<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Attribute Group
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class AttributeGroup extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/attribute_group');

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
			'href' => $this->url->link('catalog/attribute_group', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/attribute_group.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/attribute_group.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_group', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/attribute_group');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'agd.name';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
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

		$data['action'] = $this->url->link('catalog/attribute_group.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['attribute_groups'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/attribute_group');

		$attribute_group_total = $this->model_catalog_attribute_group->getTotalAttributeGroups();

		$results = $this->model_catalog_attribute_group->getAttributeGroups($filter_data);

		foreach ($results as $result) {
			$data['attribute_groups'][] = [
				'attribute_group_id' => $result['attribute_group_id'],
				'name'               => $result['name'],
				'sort_order'         => $result['sort_order'],
				'edit'               => $this->url->link('catalog/attribute_group.form', 'user_token=' . $this->session->data['user_token'] . '&attribute_group_id=' . $result['attribute_group_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('catalog/attribute_group.list', 'user_token=' . $this->session->data['user_token'] . '&sort=agd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/attribute_group.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ag.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $attribute_group_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/attribute_group.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_group_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($attribute_group_total - $this->config->get('config_pagination_admin'))) ? $attribute_group_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $attribute_group_total, ceil($attribute_group_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/attribute_group_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/attribute_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['attribute_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/attribute_group', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/attribute_group.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/attribute_group', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['attribute_group_id'])) {
			$this->load->model('catalog/attribute_group');

			$attribute_group_info = $this->model_catalog_attribute_group->getAttributeGroup($this->request->get['attribute_group_id']);
		}

		if (isset($this->request->get['attribute_group_id'])) {
			$data['attribute_group_id'] = (int)$this->request->get['attribute_group_id'];
		} else {
			$data['attribute_group_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['attribute_group_id'])) {
			$data['attribute_group_description'] = $this->model_catalog_attribute_group->getDescriptions($this->request->get['attribute_group_id']);
		} else {
			$data['attribute_group_description'] = [];
		}

		if (!empty($attribute_group_info)) {
			$data['sort_order'] = $attribute_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_group_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/attribute_group');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['attribute_group_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/attribute_group');

			if (!$this->request->post['attribute_group_id']) {
				$json['attribute_group_id'] = $this->model_catalog_attribute_group->addAttributeGroup($this->request->post);
			} else {
				$this->model_catalog_attribute_group->editAttributeGroup($this->request->post['attribute_group_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('catalog/attribute_group');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/attribute_group')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/attribute');

		foreach ($selected as $attribute_group_id) {
			$attribute_total = $this->model_catalog_attribute->getTotalAttributesByAttributeGroupId($attribute_group_id);

			if ($attribute_total) {
				$json['error'] = sprintf($this->language->get('error_attribute'), $attribute_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/attribute_group');

			foreach ($selected as $attribute_group_id) {
				$this->model_catalog_attribute_group->deleteAttributeGroup($attribute_group_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
