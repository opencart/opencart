<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/article');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
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
			'href' => $this->url->link('cms/article', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/article.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('cms/article.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('cms/article.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['filter_name'] = $filter_name;
		$data['filter_store_id'] = $filter_store_id;
		$data['filter_language_id'] = $filter_language_id;
		$data['filter_status'] = $filter_status;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/article', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/article');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = (string)$this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_store_id'])) {
			$filter_store_id = (int)$this->request->get['filter_store_id'];
		} else {
			$filter_store_id = '';
		}

		if (isset($this->request->get['filter_language_id'])) {
			$filter_language_id = $this->request->get['filter_language_id'];
		} else {
			$filter_language_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'date_added';
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
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Articles
		$data['articles'] = [];

		$filter_data = [
			'filter_name'     => $filter_name,
			'filter_store_id' => $filter_store_id,
			'filter_status'   => $filter_status,
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'           => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/article');

		$results = $this->model_cms_article->getArticles($filter_data);

		foreach ($results as $result) {
			$data['articles'][] = [
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       => $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id'] . $url)
			] + $result;
		}

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_author'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=author' . $url);
		$data['sort_rating'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=rating' . $url);
		$data['sort_status'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);
		$data['sort_date_added'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Articles
		$article_total = $this->model_cms_article->getTotalArticles($filter_data);

		// Pagination
		$data['total'] = $article_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($article_total - $this->config->get('config_pagination_admin'))) ? $article_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $article_total, ceil($article_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('cms/article_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('cms/article');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('assets/ckeditor/ckeditor.js');
		$this->document->addScript('assets/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_name',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
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
			'href' => $this->url->link('cms/article', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('cms/article.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('cms/article', 'user_token=' . $this->session->data['user_token'] . $url);

		// Article
		if (isset($this->request->get['article_id'])) {
			$this->load->model('cms/article');

			$article_info = $this->model_cms_article->getArticle((int)$this->request->get['article_id']);
		}

		if (!empty($article_info)) {
			$data['article_id'] = $article_info['article_id'];
		} else {
			$data['article_id'] = 0;
		}

		// Languages
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		// Image
		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));

		$data['article_description'] = [];

		if (!empty($article_info)) {
			$results = $this->model_cms_article->getDescriptions($article_info['article_id']);

			foreach ($results as $key => $result) {
				$data['article_description'][$key] = $result;

				if ($result['image'] && is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['article_description'][$key]['thumb'] = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_default_width'), $this->config->get('config_image_default_height'));
				} else {
					$data['article_description'][$key]['thumb'] = $data['placeholder'];
				}
			}
		}

		if (!empty($article_info)) {
			$data['author'] = $article_info['author'];
		} else {
			$data['author'] = $this->user->getFirstName() . ' ' . $this->user->getLastName();
		}

		// Topic
		$this->load->model('cms/topic');

		$data['topics'] = $this->model_cms_topic->getTopics();

		if (!empty($article_info)) {
			$data['topic_id'] = $article_info['topic_id'];
		} else {
			$data['topic_id'] = 0;
		}

		// Stores
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($data['stores'], $this->model_setting_store->getStores());

		if (!empty($article_info)) {
			$data['article_store'] = $this->model_cms_article->getStores($article_info['article_id']);
		} else {
			$data['article_store'] = [0];
		}

		if (!empty($article_info)) {
			$data['status'] = $article_info['status'];
		} else {
			$data['status'] = true;
		}

		// SEO
		if (!empty($article_info)) {
			$this->load->model('design/seo_url');

			$data['article_seo_url'] = $this->model_design_seo_url->getSeoUrlsByKeyValue('article_id', $article_info['article_id']);
		} else {
			$data['article_seo_url'] = [];
		}

		// Layouts
		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (!empty($article_info)) {
			$data['article_layout'] = $this->model_cms_article->getLayouts($article_info['article_id']);
		} else {
			$data['article_layout'] = [];
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/article_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/article');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'article_id'          => 0,
			'article_description' => [],
			'author'              => '',
			'status'              => 0,
			'article_seo_url'     => []
		];

		$post_info = $this->request->post + $required;

		foreach ($post_info['article_description'] as $language_id => $value) {
			if (!oc_validate_length((string)$value['name'], 1, 255)) {
				$json['error']['name_' . (int)$language_id] = $this->language->get('error_name');
			}

			if (!oc_validate_length((string)$value['meta_title'], 1, 255)) {
				$json['error']['meta_title_' . (int)$language_id] = $this->language->get('error_meta_title');
			}
		}

		if (!oc_validate_length((string)$post_info['author'], 3, 64)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		// SEO
		if ($post_info['article_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($post_info['article_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!oc_validate_length((string)$keyword, 1, 64)) {
						$json['error']['keyword_' . (int)$store_id . '_' . (int)$language_id] = $this->language->get('error_keyword');
					}

					if (!oc_validate_path((string)$keyword)) {
						$json['error']['keyword_' . (int)$store_id . '_' . (int)$language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword((string)$keyword, $store_id);

					if ($seo_url_info && (!$post_info['article_id'] || $seo_url_info['key'] != 'article_id' || $seo_url_info['value'] != (int)$post_info['article_id'])) {
						$json['error']['keyword_' . (int)$store_id . '_' . (int)$language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Article
			$this->load->model('cms/article');

			if (!$post_info['article_id']) {
				$json['article_id'] = $this->model_cms_article->addArticle($post_info);
			} else {
				$this->model_cms_article->editArticle($post_info['article_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Rating
	 *
	 * @return void
	 */
	public function rating(): void {
		$this->load->language('cms/article');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$task_data = [
				'code'   => 'article',
				'action' => 'task/catalog/article.rating',
				'args'   => []
			];

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/article');

			foreach ($selected as $topic_id) {
				$this->model_cms_article->editStatus((int)$topic_id, true);
			}

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
		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/article');

			foreach ($selected as $topic_id) {
				$this->model_cms_article->editStatus((int)$topic_id, false);
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
		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Article
			$this->load->model('cms/article');

			foreach ($selected as $article_id) {
				$this->model_cms_article->deleteArticle($article_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
