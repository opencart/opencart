<?php
namespace Opencart\Catalog\Controller\Cms;
/**
 * Class Comment
 *
 * Can be loaded using $this->load->controller('cms/comment');
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Comment extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('cms/comment');

		if (isset($this->request->get['article_id'])) {
			$data['article_id'] = (int)$this->request->get['article_id'];
		} else {
			$data['article_id'] = 0;
		}

		if (isset($this->request->get['sort']) && $this->request->get['route'] == 'cms/comment') {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order']) && $this->request->get['route'] == 'cms/comment') {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['logged'] = $this->customer->isLogged();
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language') . '&page=' . $page . '&redirect=' . urlencode($this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'], true)));

		$this->session->data['comment_token'] = oc_token(32);

		// Create a login token to prevent brute force attacks
		$data['comment_add'] = $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&comment_token=' . $this->session->data['comment_token'], true);
		$data['like'] = $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&rate=1&comment_token=' . $this->session->data['comment_token'], true);
		$data['dislike'] = $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&rate=0&comment_token=' . $this->session->data['comment_token'], true);

		// Article
		$this->load->model('cms/article');

		$data['list'] = $this->load->controller('cms/comment.getList');

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'date_added-ASC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=date_added&order=ASC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'date_added-DESC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=date_added&order=DESC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 'rating-ASC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=rating&order=ASC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 'rating-DESC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=rating&order=DESC')
		];

		$data['sort'] = $sort;
		$data['order'] = $order;

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('cms/comment', $data);
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/comment');

		$this->response->setOutput($this->load->controller('cms/comment.getList'));
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['sort']) && $this->request->get['route'] == 'cms/comment.list') {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order']) && $this->request->get['route'] == 'cms/comment.list') {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 5;

		$data['comments'] = [];

		$filter_data = [
			'parent_id' => 0,
			'sort'      => $sort,
			'order'     => $order,
			'start'     => ($page - 1) * $limit,
			'limit'     => $limit
		];

		// Article
		$this->load->model('cms/article');

		$results = $this->model_cms_article->getComments($article_id, $filter_data);

		foreach ($results as $result) {
			$data['comments'][] = [
				'comment'     => nl2br($result['comment']),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'like'        => $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'] . '&rate=1', true),
				'dislike'     => $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'] . '&rate=0', true),
				'reply'       => $this->url->link('cms/comment.reply', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $result['article_comment_id'], true),
				'reply_add'   => $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true),
				'reply_total' => $this->model_cms_article->getTotalComments($article_id, ['parent_id' => $result['article_comment_id']])
			] + $result;
		}

		// Total Articles
		$comment_total = $this->model_cms_article->getTotalComments($article_id, $filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($comment_total - $limit)) ? $comment_total : ((($page - 1) * $limit) + $limit), $comment_total, ceil($comment_total / $limit));

		$data['refresh'] = $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&page=' . $page, true);

		$data['logged'] = $this->customer->isLogged();
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language') . '&redirect=' . urlencode($this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id, true)));

		return $this->load->view('cms/comment_list', $data);
	}

	/**
	 * Reply
	 *
	 * @return void
	 */
	public function reply(): void {
		$this->load->language('cms/comment');

		$this->response->setOutput($this->load->controller('cms/comment.getReplies'));
	}

	/**
	 * Get Replies
	 *
	 * @return string
	 */
	public function getReplies(): string {
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = (int)$this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 5;

		$data['replies'] = [];

		$filter_data = [
			'parent_id' => $parent_id,
			'sort'      => 'date_added',
			'order'     => 'ASC',
			'start'     => ($page - 1) * $limit,
			'limit'     => $limit
		];

		// Article
		$this->load->model('cms/article');

		$results = $this->model_cms_article->getComments($article_id, $filter_data);

		foreach ($results as $result) {
			$data['replies'][] = [
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Articles
		$reply_total = $this->model_cms_article->getTotalComments($article_id, $filter_data);

		$data['refresh'] = $this->url->link('cms/comment.reply', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $parent_id . '&page=' . $page, true);

		if (($page * $limit) < $reply_total) {
			$data['next'] = $this->url->link('cms/comment.reply', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $parent_id . '&page=' . ($page + 1), true);
		} else {
			$data['next'] = '';
		}

		$data['parent_id'] = $parent_id;
		$data['page'] = $page;

		return $this->load->view('cms/comment_reply', $data);
	}

	/**
	 * Add
	 *
	 * @return void
	 */
	public function add(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['parent_id'])) {
			$parent_id = (int)$this->request->get['parent_id'];
		} else {
			$parent_id = 0;
		}

		$required = [
			'author'  => '',
			'comment' => ''
		];

		$post_info = $this->request->post + $required;

		if (!isset($this->request->get['comment_token']) || !isset($this->session->data['comment_token']) || $this->request->get['comment_token'] != $this->session->data['comment_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		if (!$this->customer->isLogged()) {
			$json['error']['warning'] = $this->language->get('error_login');
		}

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if (!$article_info) {
			$json['error']['warning'] = $this->language->get('error_article');
		}

		if (!oc_validate_length($post_info['author'], 3, 25)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		if (!oc_validate_length($post_info['comment'], 2, 1000)) {
			$json['error']['comment'] = $this->language->get('error_comment');
		}

		if ($this->config->get('config_comment_interval')) {
			$filter_data = [
				'customer_id' => $this->customer->getId(),
				'sort'        => 'date_added',
				'order'       => 'DESC',
				'start'       => 0,
				'limit'       => 1
			];

			$results = $this->model_cms_article->getComments($article_id, $filter_data);

			foreach ($results as $result) {
				if (strtotime('+' . $this->config->get('config_comment_interval') . ' minute', strtotime($result['date_added'])) >= time()) {
					$json['error']['warning'] = sprintf($this->language->get('error_interval'), $this->config->get('config_comment_interval'));

					break;
				}
			}
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

			if ($captcha) {
				$json['error']['captcha'] = $captcha;
			}
		}

		if (!$this->config->get('config_comment_status')) {
			$json['error']['warning'] = $this->language->get('error_status');
		}

		if (!$json) {
			// Anti-Spam
			$this->load->model('cms/antispam');

			$spam = $this->model_cms_antispam->getSpam($post_info['comment']);

			// If customer has been approved to make comments without moderation
			if ($this->customer->isCommenter()) {
				$status = 1;
				// If auto approve comments
			} elseif ($this->config->get('config_comment_approve') && !$spam) {
				$status = 1;
			} else {
				$status = 0;
			}

			$comment_data = $post_info + [
				'parent_id' => $parent_id,
				'status'    => $status
			];

			$this->model_cms_article->addComment($article_id, $comment_data);

			if ($status) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['success'] = $this->language->get('text_queue');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Rating
	 *
	 * @return void
	 */
	public function rate(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['article_comment_id'])) {
			$article_comment_id = (int)$this->request->get['article_comment_id'];
		} else {
			$article_comment_id = 0;
		}

		if (isset($this->request->get['rate'])) {
			$rating = (bool)$this->request->get['rate'];
		} else {
			$rating = 0;
		}

		if (!isset($this->request->get['comment_token']) || !isset($this->session->data['comment_token']) || $this->request->get['comment_token'] != $this->session->data['comment_token']) {
			$json['error'] = $this->language->get('error_token');
		}

		if (!$this->customer->isLogged()) {
			$json['error'] = $this->language->get('error_login');
		}

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if (!$article_info) {
			$json['error'] = $this->language->get('error_article');
		}

		// Comment to rate
		if ($article_comment_id) {
			$article_comment_info = $this->model_cms_article->getComment($article_comment_id);

			if (!$article_comment_info) {
				$json['error'] = $this->language->get('error_article_comment');
			}
		}

		if (!$json) {
			// Delete previous rating if there is one
			$this->model_cms_article->deleteRating($article_id, $article_comment_id);

			$this->model_cms_article->addRating($article_id, $article_comment_id, $rating);

			$like = 0;
			$dislike = 0;

			$results = $this->model_cms_article->getRatings($article_id, $article_comment_id);

			foreach ($results as $result) {
				if ($result['rating'] == 1) {
					$like = $result['total'];
				}

				if ($result['rating'] == 0) {
					$dislike = $result['total'];
				}
			}

			if (!$article_comment_id) {
				$this->model_cms_article->editRating($article_id, $like - $dislike);
			} else {
				$this->model_cms_article->editCommentRating($article_id, $article_comment_id, $like - $dislike);
			}

			$json['success'] = $this->language->get('text_rating');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
