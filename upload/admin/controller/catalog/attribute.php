<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Attribute
 *
 * Can be loaded using $this->load->controller('catalog/attribute');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Attribute extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/attribute.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/attribute.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('catalog/attribute.getList');

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/attribute');

		$this->response->setOutput($this->load->controller('catalog/attribute.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Attributes
		$data['attributes'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/attribute');

		$results = $this->model_catalog_attribute->getAttributes($filter_data);

		foreach ($results as $result) {
			$data['attributes'][] = ['edit' => $this->url->link('catalog/attribute.form', 'user_token=' . $this->session->data['user_token'] . '&attribute_id=' . $result['attribute_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_attribute_group'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=attribute_group' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$allowed = [
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Attributes
		$attribute_total = $this->model_catalog_attribute->getTotalAttributes();

		// Pagination
		$data['total'] = $attribute_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('catalog/attribute.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($attribute_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($attribute_total - $this->config->get('config_pagination_admin'))) ? $attribute_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $attribute_total, ceil($attribute_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/attribute_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/attribute');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['attribute_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/attribute.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/attribute', 'user_token=' . $this->session->data['user_token'] . $url);

		// Attribute
		if (isset($this->request->get['attribute_id'])) {
			$this->load->model('catalog/attribute');

			$attribute_info = $this->model_catalog_attribute->getAttribute((int)$this->request->get['attribute_id']);
		}

		if (!empty($attribute_info)) {
			$data['attribute_id'] = $attribute_info['attribute_id'];
		} else {
			$data['attribute_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($attribute_info)) {
			$data['attribute_description'] = $this->model_catalog_attribute->getDescriptions($attribute_info['attribute_id']);
		} else {
			$data['attribute_description'] = [];
		}

		// Attribute Groups
		$this->load->model('catalog/attribute_group');

		$data['attribute_groups'] = $this->model_catalog_attribute_group->getAttributeGroups();

		if (!empty($attribute_info)) {
			$data['attribute_group_id'] = $attribute_info['attribute_group_id'];
		} else {
			$data['attribute_group_id'] = 0;
		}

		if (!empty($attribute_info)) {
			$data['sort_order'] = $attribute_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/attribute_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/attribute');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'attribute_id'          => 0,
			'attribute_group_id'    => 0,
			'attribute_description' => [],
			'sort_order'            => 0
		];

		$post_info = $this->request->post + $required;

		if (!$post_info['attribute_group_id']) {
			$json['error']['attribute_group'] = $this->language->get('error_attribute_group');
		}

		foreach ($post_info['attribute_description'] as $language_id => $value) {
			if (!oc_validate_length((string)$value['name'], 1, 64)) {
				$json['error']['name_' . (int)$language_id] = $this->language->get('error_name');
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Attribute
			$this->load->model('catalog/attribute');

			if (!$post_info['attribute_id']) {
				$json['attribute_id'] = $this->model_catalog_attribute->addAttribute($post_info);
			} else {
				$this->model_catalog_attribute->editAttribute((int)$post_info['attribute_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('catalog/attribute');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/attribute')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Product
		$this->load->model('catalog/product');

		foreach ($selected as $attribute_id) {
			// Total Attributes
			$product_total = $this->model_catalog_product->getTotalAttributesByAttributeId((int)$attribute_id);

			if ($product_total) {
				$json['error'] = sprintf($this->language->get('error_product'), $product_total);
			}
		}

		if (!$json) {
			// Attribute
			$this->load->model('catalog/attribute');

			foreach ($selected as $attribute_id) {
				$this->model_catalog_attribute->deleteAttribute((int)$attribute_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Autocomplete
	 *
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			// Attributes
			$filter_data = [
				'filter_name' => $this->request->get['filter_name'],
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$this->load->model('catalog/attribute');

			$results = $this->model_catalog_attribute->getAttributes($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'attribute_id'    => $result['attribute_id'],
					'name'            => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'attribute_group' => $result['attribute_group']
				];
			}
		}

		array_multisort(array_column($json, 'name'), SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
