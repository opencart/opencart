<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Filter
 *
 * Can be loaded using $this->load->controller('catalog/filter');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Filter extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/filter');

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
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/filter.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/filter.delete', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->load->controller('catalog/filter.getList');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/filter');

		$this->response->setOutput($this->load->controller('catalog/filter.getList'));
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

		$data['action'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Filters
		$data['filters'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/filter');

		$results = $this->model_catalog_filter->getFilters($filter_data);

		foreach ($results as $result) {
			$data['filters'][] = ['edit' => $this->url->link('catalog/filter.form', 'user_token=' . $this->session->data['user_token'] . '&filter_id=' . $result['filter_id'] . $url)] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_filter_group'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$allowed = [
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Filters
		$filter_total = $this->model_catalog_filter->getTotalFilters();

		// Pagination
		$data['total'] = $filter_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('catalog/filter.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($filter_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($filter_total - $this->config->get('config_pagination_admin'))) ? $filter_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $filter_total, ceil($filter_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/filter_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/filter');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['filter_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/filter.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/filter', 'user_token=' . $this->session->data['user_token'] . $url);

		// Filter
		if (isset($this->request->get['filter_id'])) {
			$this->load->model('catalog/filter');

			$filter_info = $this->model_catalog_filter->getFilter((int)$this->request->get['filter_id']);
		}

		if (isset($filter_info)) {
			$data['filter_id'] = $filter_info['filter_id'];
		} else {
			$data['filter_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($filter_info)) {
			$data['filter_description'] = $this->model_catalog_filter->getDescriptions($filter_info['filter_id']);
		} else {
			$data['filter_description'] = [];
		}

		if (!empty($filter_info)) {
			$data['sort_order'] = $filter_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		// Filter Groups
		$this->load->model('catalog/filter_group');

		$data['filter_groups'] = $this->model_catalog_filter_group->getFilterGroups();

		if (!empty($filter_info)) {
			$data['filter_group_id'] = $filter_info['filter_group_id'];
		} else {
			$data['filter_group_id'] = 0;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/filter');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'filter_id'          => 0,
			'filter_description' => [],
			'filter_group_id'    => 0,
			'sort_order'         => 0
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['filter_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (empty($post_info['filter_group_id'])) {
			$json['error']['filter_group'] = $this->language->get('error_filter_group');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Filter
			$this->load->model('catalog/filter');

			if (!$post_info['filter_id']) {
				$json['filter_id'] = $this->model_catalog_filter->addFilter($post_info);
			} else {
				$this->model_catalog_filter->editFilter($post_info['filter_id'], $post_info);
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
		$this->load->language('catalog/filter');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/filter')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Filter
			$this->load->model('catalog/filter');

			foreach ($selected as $filter_id) {
				$this->model_catalog_filter->deleteFilter($filter_id);
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
			// Filters
			$filter_data = [
				'filter_name' => $this->request->get['filter_name'] . '%',
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$this->load->model('catalog/filter');

			$filters = $this->model_catalog_filter->getFilters($filter_data);

			foreach ($filters as $filter) {
				$json[] = [
					'filter_id' => $filter['filter_id'],
					'name'      => strip_tags(html_entity_decode($filter['filter_group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
				];
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
