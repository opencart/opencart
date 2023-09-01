<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Blog Category
 *
 * @package Opencart\Admin\Controller\Cms
 */
class BlogCategory extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/blog_category');

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
			'href' => $this->url->link('cms/blog_category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/blog_category.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/blog_category.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/blog_category', $data));
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/blog_category');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'bc.sort_order';
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

		$data['action'] = $this->url->link('cms/blog_category.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['blog_categories'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/blog_category');

		$blog_category_total = $this->model_cms_blog_category->getTotalBlogCategories();

		$results = $this->model_cms_blog_category->getBlogCategories($filter_data);

		foreach ($results as $result) {
			$data['blog_categories'][] = [
				'blog_category_id' => $result['blog_category_id'],
				'name'             => $result['name'],
				'status'           => $result['status'],
				'sort_order'       => $result['sort_order'],
				'edit'             => $this->url->link('cms/blog_category.form', 'user_token=' . $this->session->data['user_token'] . '&blog_category_id=' . $result['blog_category_id'] . $url)
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

		$data['sort_name'] = $this->url->link('cms/blog_category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=bcd.name' . $url);
		$data['sort_sort_order'] = $this->url->link('cms/blog_category.list', 'user_token=' . $this->session->data['user_token'] . '&sort=bc.sort_order' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $blog_category_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('cms/blog_category.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($blog_category_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($blog_category_total - $this->config->get('config_pagination_admin'))) ? $blog_category_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $blog_category_total, ceil($blog_category_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('cms/blog_category_list', $data);
	}

	/**
	 * @return void
	 */
	public function form(): void {
		$this->load->language('cms/blog_category');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['blog_category_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
			'href' => $this->url->link('cms/blog_category', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('cms/blog_category.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('cms/blog_category', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['blog_category_id'])) {
			$this->load->model('cms/blog_category');

			$blog_category_info = $this->model_cms_blog_category->getBlogCategory($this->request->get['blog_category_id']);
		}

		if (isset($this->request->get['blog_category_id'])) {
			$data['blog_category_id'] = (int)$this->request->get['blog_category_id'];
		} else {
			$data['blog_category_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['blog_category_description'] = [];

		if (isset($this->request->get['blog_category_id'])) {
			$results = $this->model_cms_blog_category->getDescriptions($this->request->get['blog_category_id']);

			foreach ($results as $key => $result) {
				$data['blog_category_description'][$key] = $result;

				if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['blog_category_description'][$key]['thumb'] = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
				} else {
					$data['blog_category_description'][$key]['thumb'] = $data['placeholder'];
				}
			}
		}

		if (!empty($blog_category_info)) {
			$data['blog_category_id'] = $blog_category_info['blog_category_id'];
		} else {
			$data['blog_category_id'] = 0;
		}

		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		];

		$this->load->model('setting/store');

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = [
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			];
		}

		if (isset($this->request->get['blog_category_id'])) {
			$data['blog_category_store'] = $this->model_cms_blog_category->getStores($this->request->get['blog_category_id']);
		} else {
			$data['blog_category_store'] = [0];
		}

		if (!empty($blog_category_info)) {
			$data['sort_order'] = $blog_category_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		if (!empty($blog_category_info)) {
			$data['status'] = $blog_category_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->get['blog_category_id'])) {
			$data['blog_category_seo_url'] = $this->model_cms_blog_category->getSeoUrls($this->request->get['blog_category_id']);
		} else {
			$data['blog_category_seo_url'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/blog_category_form', $data));
	}

	/**
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/blog_category');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/blog_category')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['blog_category_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if ((oc_strlen(trim($value['meta_title'])) < 1) || (oc_strlen($value['meta_title']) > 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if ($this->request->post['blog_category_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['blog_category_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ((oc_strlen(trim($keyword)) < 1) || (oc_strlen($keyword) > 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (preg_match('/[^a-zA-Z0-9\/_-]|[\p{Cyrillic}]+/u', $keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($this->request->post['blog_category_id']) || $seo_url_info['key'] != 'blog_category_id' || $seo_url_info['value'] != (int)$this->request->post['blog_category_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('cms/blog_category');

			if (!$this->request->post['blog_category_id']) {
				$json['blog_category_id'] = $this->model_cms_blog_category->addBlogCategory($this->request->post);
			} else {
				$this->model_cms_blog_category->editBlogCategory($this->request->post['blog_category_id'], $this->request->post);
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
		$this->load->language('cms/blog_category');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/blog_category')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/blog_category');

			foreach ($selected as $blog_category_id) {
				$this->model_cms_blog_category->deleteBlogCategory($blog_category_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
