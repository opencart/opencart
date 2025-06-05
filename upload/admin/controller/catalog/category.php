<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Category
 *
 * Can be loaded using $this->load->controller('catalog/category');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Category extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

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
			'href' => $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['repair'] = $this->url->link('catalog/category.repair', 'user_token=' . $this->session->data['user_token']);
		$data['add'] = $this->url->link('catalog/category.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/category.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('catalog/category.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('catalog/category.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->load->controller('catalog/category.getList');

		$data['filter_name'] = $filter_name;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/category');

		$this->response->setOutput($this->load->controller('catalog/category.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Category
		$data['categories'] = [];

		$filter_data = [
			'filter_name'   => $filter_name,
			'filter_status' => $filter_status,
			'sort'          => $sort,
			'order'         => $order,
			'start'         => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'         => $this->config->get('config_pagination_admin')
		];

		// Image
		$this->load->model('tool/image');

		// Categories
		$this->load->model('catalog/category');

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $result['image'];
			} else {
				$image = 'no_image.png';
			}

			$data['categories'][] = [
				'image' => $this->model_tool_image->resize($image, 40, 40),
				'edit'	=> $this->url->link('catalog/category.form', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url)
			] + $result;
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);
		$data['sort_status'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=c1.status' . $url);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Categories
		$category_total = $this->model_catalog_category->getTotalCategories($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $category_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($category_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($category_total - $this->config->get('config_pagination_admin'))) ? $category_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $category_total, ceil($category_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/category_list', $data);
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (isset($this->request->get['category_id'])) {
			$category_id = (int)$this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Category
			$this->load->model('catalog/category');

			$this->model_catalog_category->editStatus($category_id, true);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Disable
	 *
	 * @return void
	 */
	public function disable(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (isset($this->request->get['category_id'])) {
			$category_id = (int)$this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Category
			$this->load->model('catalog/category');

			$this->model_catalog_category->editStatus($category_id, false);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

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
			'href' => $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/category.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url);

		// Category
		if (isset($this->request->get['category_id'])) {
			$this->load->model('catalog/category');

			$category_info = $this->model_catalog_category->getCategory((int)$this->request->get['category_id']);
		}

		if (!empty($category_info)) {
			$data['category_id'] = $category_info['category_id'];
		} else {
			$data['category_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($category_info)) {
			$data['category_description'] = $this->model_catalog_category->getDescriptions($category_info['category_id']);
		} else {
			$data['category_description'] = [];
		}

		if (!empty($category_info)) {
			$data['path'] = $category_info['path'];
		} else {
			$data['path'] = '';
		}

		if (!empty($category_info)) {
			$data['parent_id'] = $category_info['parent_id'];
		} else {
			$data['parent_id'] = 0;
		}

		// Filter
		$this->load->model('catalog/filter');

		if (!empty($category_info)) {
			$filters = $this->model_catalog_category->getFilters($category_info['category_id']);
		} else {
			$filters = [];
		}

		$data['category_filters'] = [];

		foreach ($filters as $filter_id) {
			$filter_info = $this->model_catalog_filter->getFilter($filter_id);

			if ($filter_info) {
				$data['category_filters'][] = [
					'filter_id' => $filter_info['filter_id'],
					'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
				];
			}
		}

		// Stores
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($stores, $this->model_setting_store->getStores());

		if (!empty($category_info)) {
			$data['category_store'] = $this->model_catalog_category->getStores($category_info['category_id']);
		} else {
			$data['category_store'] = [0];
		}

		// Image
		if (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		if ($data['image'] && is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize($data['image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (!empty($category_info)) {
			$data['sort_order'] = $category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (!empty($category_info)) {
			$data['status'] = $category_info['status'];
		} else {
			$data['status'] = true;
		}

		// SEO
		$data['category_seo_url'] = [];

		if (!empty($category_info)) {
			$this->load->model('design/seo_url');

			$results = $this->model_design_seo_url->getSeoUrlsByKeyValue('path', $this->model_catalog_category->getPath($category_info['category_id']));

			foreach ($results as $store_id => $languages) {
				foreach ($languages as $language_id => $keyword) {
					$pos = strrpos($keyword, '/');

					if ($pos !== false) {
						$keyword = substr($keyword, $pos + 1);
					}

					$data['category_seo_url'][$store_id][$language_id] = $keyword;
				}
			}
		}

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (!empty($category_info)) {
			$data['category_layout'] = $this->model_catalog_category->getLayouts($category_info['category_id']);
		} else {
			$data['category_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'category_id'          => 0,
			'category_description' => [],
			'image'                => '',
			'parent_id'            => 0,
			'sort_order'           => 0,
			'status'               => 0
		];

		$post_info = $this->request->post + $required;

		foreach ((array)$post_info['category_description'] as $language_id => $value) {
			if (!oc_validate_length((string)$value['name'], 1, 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if (!oc_validate_length((string)$value['meta_title'], 1, 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		// Category
		$this->load->model('catalog/category');

		if (isset($post_info['category_id']) && $post_info['parent_id']) {
			$results = $this->model_catalog_category->getPaths((int)$post_info['parent_id']);

			foreach ($results as $result) {
				if ($result['path_id'] == $post_info['category_id']) {
					$json['error']['parent'] = $this->language->get('error_parent');
					break;
				}
			}
		}

		// SEO
		if ($post_info['category_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($post_info['category_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!oc_validate_length($keyword, 1, 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (!oc_validate_path($keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($post_info['category_id']) || $seo_url_info['key'] != 'path' || $seo_url_info['value'] != $this->model_catalog_category->getPath($post_info['category_id']))) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			if (!$post_info['category_id']) {
				$json['category_id'] = $this->model_catalog_category->addCategory($post_info);
			} else {
				$this->model_catalog_category->editCategory($post_info['category_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Repair
	 *
	 * @return void
	 */
	public function repair(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Categories
			$this->load->model('catalog/category');

			$this->model_catalog_category->repairCategories();

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
		$this->load->language('catalog/category');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Category
			$this->load->model('catalog/category');

			foreach ($selected as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Status
	 *
	 * @return void
	 */
	public function status(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (isset($this->request->get['category_id'])) {
			$category_id = (int)$this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Modification
			$this->load->model('catalog/category');

			$this->model_setting_modification->editStatus($category_id, true);

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

		// Categories
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$filter_data = [
				'filter_name' => $this->request->get['filter_name'] . '%',
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_catalog_category->getCategories($filter_data);

			foreach ($results as $result) {
				$json[] = [
					'category_id' => $result['category_id'],
					'name'        => $result['name']
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
