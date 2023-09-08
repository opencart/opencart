<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Article
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Article extends \Opencart\System\Engine\Controller {
	/**
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
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/article');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	protected function getList(): string {
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

		$data['articles'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/article');

		$article_total = $this->model_cms_article->getTotalArticles();

		$results = $this->model_cms_article->getArticles($filter_data);

		foreach ($results as $result) {
			$data['articles'][] = [
				'article_id'  => $result['article_id'],
				'name'        => $result['name'],
				'author'      => $result['author'],
				'status'      => $result['status'],
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'        => $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=ad.name' . $url);
		$data['sort_author'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.author' . $url);
		$data['sort_date_added'] = $this->url->link('cms/article.list', 'user_token=' . $this->session->data['user_token'] . '&sort=a.date_added' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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

		if (isset($this->request->get['article_id'])) {
			$this->load->model('cms/article');

			$article_info = $this->model_cms_article->getArticle($this->request->get['article_id']);
		}

		if (isset($this->request->get['article_id'])) {
			$data['article_id'] = (int)$this->request->get['article_id'];
		} else {
			$data['article_id'] = 0;
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('tool/image');

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		$data['article_description'] = [];

		if (isset($this->request->get['article_id'])) {
			$results = $this->model_cms_article->getDescriptions($this->request->get['article_id']);

			foreach ($results as $key => $result) {
				$data['article_description'][$key] = $result;

				if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
					$data['article_description'][$key]['thumb'] = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), 100, 100);
				} else {
					$data['article_description'][$key]['thumb'] = $data['placeholder'];
				}
			}
		}

		if (!empty($article_info)) {
			$data['author'] = $article_info['author'];
		} else {
			$data['author'] = '';
		}

		$this->load->model('cms/topic');

		$data['topics'] = $this->model_cms_topic->getTopics();

		if (!empty($article_info)) {
			$data['topic_id'] = $article_info['topic_id'];
		} else {
			$data['topic_id'] = 0;
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

		if (isset($this->request->get['article_id'])) {
			$data['article_store'] = $this->model_cms_article->getStores($this->request->get['article_id']);
		} else {
			$data['article_store'] = [0];
		}

		if (!empty($article_info)) {
			$data['image'] = $article_info['image'];
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

		if (!empty($article_info)) {
			$data['status'] = $article_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->get['article_id'])) {
			$data['article_seo_url'] = $this->model_cms_article->getSeoUrls($this->request->get['article_id']);
		} else {
			$data['article_seo_url'] = [];
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();

		if (isset($this->request->get['article_id'])) {
			$data['article_layout'] = $this->model_cms_article->getLayouts($this->request->get['article_id']);
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
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/article');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['article_description'] as $language_id => $value) {
			if ((oc_strlen(trim($value['name'])) < 1) || (oc_strlen($value['name']) > 255)) {
				$json['error']['name_' . $language_id] = $this->language->get('error_name');
			}

			if ((oc_strlen(trim($value['meta_title'])) < 1) || (oc_strlen($value['meta_title']) > 255)) {
				$json['error']['meta_title_' . $language_id] = $this->language->get('error_meta_title');
			}
		}

		if ((oc_strlen($this->request->post['author']) < 3) || (oc_strlen($this->request->post['author']) > 64)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		if ($this->request->post['article_seo_url']) {
			$this->load->model('design/seo_url');

			foreach ($this->request->post['article_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if ((oc_strlen(trim($keyword)) < 1) || (oc_strlen($keyword) > 64)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword');
					}

					if (preg_match('/[^a-zA-Z0-9\/_-]|[\p{Cyrillic}]+/u', $keyword)) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_character');
					}

					$seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id);

					if ($seo_url_info && (!isset($this->request->post['article_id']) || $seo_url_info['key'] != 'article_id' || $seo_url_info['value'] != (int)$this->request->post['article_id'])) {
						$json['error']['keyword_' . $store_id . '_' . $language_id] = $this->language->get('error_keyword_exists');
					}
				}
			}
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			$this->load->model('cms/article');

			if (!$this->request->post['article_id']) {
				$json['article_id'] = $this->model_cms_article->addArticle($this->request->post);
			} else {
				$this->model_cms_article->editArticle($this->request->post['article_id'], $this->request->post);
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
		$this->load->language('cms/article');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/article')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
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
