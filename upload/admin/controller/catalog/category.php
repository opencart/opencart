<?php
namespace Opencart\Admin\Controller\Catalog;
class Category extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('catalog/category');

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
			'href' => $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['repair'] = $this->url->link('catalog/category.repair', 'user_token=' . $this->session->data['user_token']);
		$data['add'] = $this->url->link('catalog/category.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/category.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category', $data));
	}

	public function list(): void {
		$this->load->language('catalog/category');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
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

		$data['action'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['categories'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/category');

		$category_total = $this->model_catalog_category->getTotalCategories();

		$results = $this->model_catalog_category->getCategories($filter_data);

		foreach ($results as $result) {
			$data['categories'][] = [
				'category_id' => $result['category_id'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'edit'        => $this->url->link('catalog/category.form', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'] . $url)
			];
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

		$data['sort_name'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_sort_order'] = $this->url->link('catalog/category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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

	public function form(): void {
		$this->load->language('catalog/category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/category.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/category', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['category_id'])) {
			$this->load->model('catalog/category');

			$category_info = $this->model_catalog_category->getCategory($this->request->get['category_id']);
		}

		if (isset($this->request->get['category_id'])) {
			$data['category_id'] = (int)$this->request->get['category_id'];
		} else {
			$data['category_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->get['category_id'])) {
			$data['category_description'] = $this->model_catalog_category->getDescriptions($this->request->get['category_id']);
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

		$this->load->model('catalog/filter');

		if (isset($this->request->get['category_id'])) {
			$filters = $this->model_catalog_category->getFilters($this->request->get['category_id']);
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

		$this->load->model('setting/store');

		$data['stores'] = [];
		
		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		if (isset($this->request->get['category_id'])) {
			$data['category_store'] = $this->model_catalog_category->getStores($this->request->get['category_id']);
		} else {
			$data['category_store'] = [0];
		}

		if (!empty($category_info)) {
			$data['image'] = $category_info['image'];
		} else {
			$data['image'] = '';
		}

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (is_file(DIR_IMAGE . html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($data['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
		} else {
			$data['thumb'] = $data['placeholder'];
		}

		if (!empty($category_info)) {
			$data['top'] = $category_info['top'];
		} else {
			$data['top'] = 0;
		}

		if (!empty($category_info)) {
			$data['column'] = $category_info['column'];
		} else {
			$data['column'] = 1;
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

		$data['category_seo_url'] = [];

		if (isset($this->request->get['category_id'])) {
			$results = $this->model_catalog_category->getSeoUrls($this->request->get['category_id']);

			foreach ($results as $store_id => $languages) {
				foreach ($languages as $language_id => $keyword) {
					$pos = strrpos($keyword, '/');

					if ($pos !== false) {
						$keyword = substr($keyword, $pos + 1);
					} else {
						$keyword = $keyword;
					}

					$data['category_seo_url'][$store_id][$language_id] = $keyword;
				}
			}
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->get['category_id'])) {
			$data['category_layout'] = $this->model_catalog_category->getLayouts($this->request->get['category_id']);
		} else {
			$data['category_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/category_form', $data));
	}

	public function save(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['category_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if ((oc_strlen(trim($value['meta_title'])) < 1) || (oc_strlen($value['meta_title']) > 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		$this->load->model('catalog/category');

		if (isset($this->request->post['category_id']) && $this->request->post['parent_id']) {
			$results = $this->model_catalog_category->getPaths($this->request->post['parent_id']);
			
			foreach ($results as $result) {
				if ($result['path_id'] == $this->request->post['category_id']) {
					$json['error']['parent'] = $this->language->get('error_parent');
					
					break;
				}
			}
		}

		if ($this->request->post['category_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['category_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ((oc_strlen(trim($keyword)) < 1) || (oc_strlen($keyword) > 100)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($this->request->post['category_id']) || $seo_url_info['key'] != 'path' || $seo_url_info['value'] != $this->model_catalog_category->getPath($this->request->post['category_id']))) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			if (!$this->request->post['category_id']) {
				$json['category_id'] = $this->model_catalog_category->addCategory($this->request->post);
			} else {
				$this->model_catalog_category->editCategory($this->request->post['category_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function repair(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('catalog/category');

			$this->model_catalog_category->repairCategories();

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('catalog/category');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('catalog/category');

			foreach ($selected as $category_id) {
				$this->model_catalog_category->deleteCategory($category_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete(): void {
		$json = [];

		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/category');

			$filter_data = [
				'filter_name' => '%' . $this->request->get['filter_name'] . '%',
				'sort'        => 'name',
				'order'       => 'ASC',
				'start'       => 0,
				'limit'       => 5
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
