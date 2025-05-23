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
			'href' => $this->url->link('cms/article', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/article.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

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
		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'a.date_added';
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

		$data['action'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Articles
		$data['articles'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
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

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_name'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ad.name' . $url);
		$data['sort_author'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.author' . $url);
		$data['sort_rating'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.rating' . $url);
		$data['sort_date_added'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.date_added' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Articles
		$article_total = $this->model_cms_article->getTotalArticles();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $article_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

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

		$this->document->addScript('view/javascript/ckeditor/ckeditor.js');
		$this->document->addScript('view/javascript/ckeditor/adapters/jquery.js');

		$data['text_form'] = !isset($this->request->get['article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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
		$stores = [];

		$stores[] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = array_merge($stores, $this->model_setting_store->getStores());

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

	/**
	 * Rating
	 *
	 * @return void
	 */
	public function rating(): void {
		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$limit = 100;

			// Articles
			$filter_data = [
				'sort'  => 'date_added',
				'order' => 'ASC',
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$this->load->model('cms/article');

			$results = $this->model_cms_article->getArticles($filter_data);

			foreach ($results as $result) {
				$like = 0;
				$dislike = 0;

				$ratings = $this->model_cms_article->getRatings($result['article_id']);

				foreach ($ratings as $rating) {
					if ($rating['rating'] == 1) {
						$like = $rating['total'];
					}

					if ($rating['rating'] == 0) {
						$dislike = $rating['total'];
					}
				}

				$this->model_cms_article->editRating($result['article_id'], $like - $dislike);
			}

			// Total Articles
			$article_total = $this->model_cms_article->getTotalArticles();

			$start = ($page - 1) * $limit;
			$end = ($start > ($article_total - $limit)) ? $article_total : ($start + $limit);

			if ($end < $article_total) {
				$json['text'] = sprintf($this->language->get('text_next'), $start ?: 1, $end, $article_total);

				$json['next'] = $this->url->link('cms/article.rating', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
