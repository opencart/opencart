<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Filter Group
 *
 * Can be loaded using $this->load->controller('catalog/filter_group');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class FilterGroup extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/filter_group');

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
			'href' => $this->url->link('catalog/filter_group', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/filter_group.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/filter_group.delete', 'user_token=' . $this->session->data['user_token']);

		$data['user_token'] = $this->session->data['user_token'];

		$data['list'] = $this->load->controller('catalog/filter_group.getList');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_group', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/filter_group');

		$this->response->setOutput($this->load->controller('catalog/filter_group.getList'));
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
			$sort = 'fgd.name';
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

		$data['action'] = $this->url->link('catalog/filter_group.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Filter Group
		$data['filter_groups'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/filter_group');

		$results = $this->model_catalog_filter_group->getFilterGroups($filter_data);

		foreach ($results as $result) {
			$data['filter_groups'][] = ['edit' => $this->url->link('catalog/filter_group.form', 'user_token=' . $this->session->data['user_token'] . '&filter_group_id=' . $result['filter_group_id'] . $url)] + $result;
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

		$data['sort_name'] = $this->url->link('catalog/filter_group.list', 'user_token=' . $this->session->data['user_token'] . '&sort=fgd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/filter_group.list', 'user_token=' . $this->session->data['user_token'] . '&sort=fg.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$filter_group_total = $this->model_catalog_filter_group->getTotalFilterGroups();

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $filter_group_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/filter_group.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($filter_group_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($filter_group_total - $this->config->get('config_pagination_admin'))) ? $filter_group_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $filter_group_total, ceil($filter_group_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/filter_group_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/filter_group');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['filter_group_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/filter_group', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/filter_group.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/filter_group', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['filter_group_id'])) {
			$this->load->model('catalog/filter_group');

			$filter_group_info = $this->model_catalog_filter_group->getFilterGroup($this->request->get['filter_group_id']);
		}

		if (isset($this->request->get['filter_group_id'])) {
			$data['filter_group_id'] = (int)$this->request->get['filter_group_id'];
		} else {
			$data['filter_group_id'] = 0;
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['filter_group_id'])) {
			$data['filter_group_description'] = $this->model_catalog_filter_group->getDescriptions($this->request->get['filter_group_id']);
		} else {
			$data['filter_group_description'] = [];
		}

		if (!empty($filter_group_info)) {
			$data['sort_order'] = $filter_group_info['sort_order'];
		} else {
			$data['sort_order'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/filter_group_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/filter_group');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/filter_group')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$filter_data = [
			'filter_group_id'          => 0,
			'filter_group_description' => [],
			'sort_order'               => 0
		];

		$post_info = oc_filter_data($filter_data, $this->request->post);

		foreach ($post_info['filter_group_description'] as $language_id => $value) {
			if (!oc_validate_length($value['name'], 1, 64)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('catalog/filter_group');

			if (!$post_info['filter_group_id']) {
				$json['filter_group_id'] = $this->model_catalog_filter_group->addFilterGroup($post_info);
			} else {
				$this->model_catalog_filter_group->editFilterGroup($post_info['filter_group_id'], $post_info);
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
		$this->load->language('catalog/filter_group');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/filter_group')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Filter
		$this->load->model('catalog/filter');

		foreach ($selected as $filter_group_id) {
			$filter_total = $this->model_catalog_filter->getTotalFiltersByFilterGroupId($filter_group_id);

			if ($filter_total) {
				$json['error'] = sprintf($this->language->get('error_filter'), $filter_total);
			}
		}

		if (!$json) {
			$this->load->model('catalog/filter_group');

			foreach ($selected as $filter_group_id) {
				$this->model_catalog_filter_group->deleteFilterGroup($filter_group_id);
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
			$filter_data = [
				'filter_name' => $this->request->get['filter_name'] . '%',
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$this->load->model('catalog/filter_group');

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
