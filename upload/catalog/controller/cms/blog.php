<?php
namespace Opencart\Catalog\Controller\Cms;
/**
 * Class Blog
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Blog extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */
	public function index(): void {
		$this->load->language('cms/blog');

		if (isset($this->request->get['search'])) {
			$search = (string)$this->request->get['search'];
		} else {
			$search = '';
		}

		if (isset($this->request->get['topic_id'])) {
			$topic_id = (int)$this->request->get['topic_id'];
		} else {
			$topic_id = 0;
		}

		if (isset($this->request->get['author'])) {
			$author = (string)$this->request->get['author'];
		} else {
			$author = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . (string)$this->request->get['search'];
		}

		if (isset($this->request->get['author'])) {
			$url .= '&author=' . (string)$this->request->get['author'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_blog'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
		];

		$this->load->model('cms/topic');

		$topic_info = $this->model_cms_topic->getTopic($topic_id);

		if ($topic_info) {
			$url = '';

			if (isset($this->request->get['search'])) {
				$url .= '&search=' . (string)$this->request->get['search'];
			}

			if (isset($this->request->get['topic_id'])) {
				$url .= '&topic_id=' . (int)$this->request->get['topic_id'];
			}

			if (isset($this->request->get['author'])) {
				$url .= '&author=' . (string)$this->request->get['author'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = [
				'text' => $topic_info['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url)
			];
		}

		$this->load->model('tool/image');

		if ($topic_info && is_file(DIR_IMAGE . html_entity_decode($topic_info['image'], ENT_QUOTES, 'UTF-8'))) {
			$data['thumb'] = $this->model_tool_image->resize(html_entity_decode($topic_info['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
		} else {
			$data['thumb'] = '';
		}

		if ($topic_info) {
			$this->document->setTitle($topic_info['meta_title']);
			$this->document->setDescription($topic_info['meta_description']);
			$this->document->setKeywords($topic_info['meta_keyword']);

			$data['heading_title'] = $topic_info['name'];
		} else {
			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');
		}

		if ($topic_info) {
			$data['description'] = html_entity_decode($topic_info['description'], ENT_QUOTES, 'UTF-8');
		} else {
			$data['description'] = '';
		}

		$limit = 20;

		$data['articles'] = [];

		$filter_data = [
			'filter_search'   => $search,
			'filter_topic_id' => $topic_id,
			'filter_author'   => $author,
			'start'           => ($page - 1) * $limit,
			'limit'           => $limit
		];

		$this->load->model('cms/article');

		$article_total = $this->model_cms_article->getTotalArticles($filter_data);

		$results = $this->model_cms_article->getArticles($filter_data);

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'))) {
				$image = $this->model_tool_image->resize(html_entity_decode($result['image'], ENT_QUOTES, 'UTF-8'), $this->config->get('config_image_blog_width'), $this->config->get('config_image_blog_height'));
			} else {
				$image = '';
			}

			$data['articles'][] = [
				'article_id'    => $result['article_id'],
				'image'         => $image,
				'name'          => $result['name'],
				'description'   => oc_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('config_article_description_length')) . '..',
				'author'        => $result['author'],
				'comment_total' => $this->model_cms_article->getTotalComments($result['article_id']),
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'          => $this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $result['article_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['search'])) {
			$url .= '&search=' . $this->request->get['search'];
		}

		if (isset($this->request->get['topic_id'])) {
			$url .= '&topic_id=' . $this->request->get['topic_id'];
		}

		if (isset($this->request->get['author'])) {
			$url .= '&author=' . (string)$this->request->get['author'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $article_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($article_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($article_total - $limit)) ? $article_total : ((($page - 1) * $limit) + $limit), $article_total, ceil($article_total / $limit));

		// http://googlewebmastercentral.articlespot.com/2011/09/pagination-with-relnext-and-relprev.html
		if ($page == 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language')), 'canonical');
		} else {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. $page), 'canonical');
		}

		if ($page > 1) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . (($page - 2) ? '&page='. ($page - 1) : '')), 'prev');
		}

		if (ceil($article_total / $limit) > $page) {
			$this->document->addLink($this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&page='. ($page + 1)), 'next');
		}

		$data['search'] = $search;
		$data['topic_id'] = $topic_id;

		$data['topics'] = [];

		$data['topics'][] = [
			'name' => $this->language->get('text_all'),
			'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language'))
		];

		$results = $this->model_cms_topic->getTopics();

		foreach ($results as $result) {
			$data['topics'][] = [
				'name' => $result['name'],
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language') . '&topic_id='. $result['topic_id'])
			];
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('cms/blog_list', $data));
	}

	public function info(): object|null {
		$this->load->language('cms/blog');

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['topic_id'])) {
			$topic_id = (int)$this->request->get['topic_id'];
		} else {
			$topic_id = 0;
		}

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if ($article_info) {
			$this->document->setTitle($article_info['meta_title']);
			$this->document->setDescription($article_info['meta_description']);
			$this->document->setKeywords($article_info['meta_keyword']);

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_blog'),
				'href' => $this->url->link('cms/blog', 'language=' . $this->config->get('config_language'))
			];

			$url = '';

			if (isset($this->request->get['topic_id'])) {
				$url .= '&topic_id=' . $this->request->get['topic_id'];
			}

			if (isset($this->request->get['author'])) {
				$url .= '&author=' . (string)$this->request->get['author'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->load->model('cms/topic');

			$topic_info = $this->model_cms_topic->getTopic($topic_id);

			if ($topic_info) {
				$data['breadcrumbs'][] = [
					'text' => $topic_info['name'],
					'href' => $this->url->link('cms/article', 'language=' . $this->config->get('config_language') . $url)
				];
			}

			$data['breadcrumbs'][] = [
				'text' => $article_info['name'],
				'href' => $this->url->link('cms/article.info', 'language=' . $this->config->get('config_language') . '&article_id=' .  $article_id . $url)
			];

			$data['heading_title'] = $article_info['name'];

			$data['description'] = html_entity_decode($article_info['description'], ENT_QUOTES, 'UTF-8');
			$data['author'] = $article_info['author'];
			$data['date_added'] = $article_info['date_added'];

			$data['comment'] = $this->getComments();

			$data['continue'] = $this->url->link('cms/article', 'language=' . $this->config->get('config_language') . $url);

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('cms/blog_info', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}

	/**
	 * @return void
	 */
	public function comment() {
		$this->load->language('cms/blog');

		$this->response->setOutput($this->getComments());
	}

	/**
	 * @return string
	 */
	public function getComments(): string {
		if (isset($this->request->get['article_id'])) {
			$article_id = $this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['articles'] = [];

		$this->load->model('cms/article');

		$comment_total = $this->model_cms_article->getTotalComments($article_id);

		$results = $this->model_cms_article->getComments($article_id, ($page - 1) * (int)$this->config->get('config_pagination_admin'), (int)$this->config->get('config_pagination_admin'));

		foreach ($results as $result) {
			$data['articles'][] = [
				'text'       => nl2br($result['text']),
				'author'     => $result['author'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => 5,
			'url'   => $this->url->link('cms/blog.comment', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($comment_total - 5)) ? $comment_total : ((($page - 1) * 5) + 5), $comment_total, ceil($comment_total / 5));

		return $this->load->view('cms/comment', $data);
	}

	public function addComment(): void {
		$this->load->language('cms/article');

		$json = array();

		if (isset($this->request->get['article_id'])) {
			$article_id = $this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (!isset($this->request->get['comment_token']) || !isset($this->session->data['comment_token']) || $this->request->get['comment_token'] != $this->session->data['comment_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		$keys = [
			'comment',
			'author'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if (!$article_info) {
			$json['error']['warning'] = $this->language->get('error_article');
		}

		if (!$this->customer->isLogged() && !$this->config->get('config_comment_guest')) {
			$json['error']['warning'] = $this->language->get('error_guest');
		}

		if ((oc_strlen($this->request->post['author']) < 3) || (oc_strlen($this->request->post['author']) > 25)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		if ((utf8_strlen($this->request->post['comment']) < 2) || (utf8_strlen($this->request->post['comment']) > 1000)) {
			$json['error']['comment'] = $this->language->get('error_comment');
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

			if ($captcha) {
				$json['error']['captcha'] = $captcha;
			}
		}

		if (!$json) {
			// Anti-Spam
			$comment = str_replace(' ', '', $this->request->post['comment']);

			$this->load->model('cms/antispam');

			$spam = $this->model_cms_antispam->getSpam($comment);

			if (!$this->customer->isCommentor() || $spam) {
				$status = 0;
			} else {
				$status = 1;
			}

			$this->model_cms_article->addComment($article_id, $this->request->post + ['status' => $status]);

			if (!$status) {
				$json['success'] = $this->language->get('text_queue');
			} else {
				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}