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
			$sort = 'date_modified';
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

		// Customer
		$this->load->model('account/customer');

		// Author
		$data['author'] = ($this->customer->isLogged()) ? $this->customer->getAuthor() : '';

		$data['logged'] = $this->customer->isLogged();
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language') . '&page=' . $page . '&redirect=' . urlencode($this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'], true)));
		$data['comment_add'] = $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&comment_token=' . $this->session->data['comment_token'], true);
		$data['like'] = $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&rate=1&comment_token=' . $this->session->data['comment_token'], true);
		$data['dislike'] = $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&rate=0&comment_token=' . $this->session->data['comment_token'], true);

		// Likes
		$data['article_comment_id'] = '0';
		$data['customer_can_like'] = '1';
		$data['customer_did_like'] = $this->model_cms_article->getCustomerRating($data['article_id'], 0);

		// Article
		$this->load->model('cms/article');

		$data['list'] = $this->load->controller('cms/comment.getList');

		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_modified_asc'),
			'value' => 'date_modified-ASC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=date_modified&order=ASC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_modified_desc'),
			'value' => 'date_modified-DESC',
			'href'  => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $data['article_id'] . '&sort=date_modified&order=DESC')
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
			$sort = 'date_modified';
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

		// Customer
		$this->load->model('account/customer');

		// Create the Edit and Delete urls only for comments posted by logged-in customer. 
		$customer_id = ($this->customer->isLogged()) ? $this->customer->getId() : -1;

		// Article Comments
		foreach ($results as $result) {
			$author_id = 0;
			$author = $this->language->get('text_admin_author');

			$customerInfo = $this->model_account_customer->getCustomer($result['customer_id']);
			
			// Author
			if ($customerInfo) {
				$author = ($customerInfo['author']) ? $customerInfo['author'] : $customerInfo['firstname'] . ' ' . $customerInfo['lastname'];
				$author_id = $customerInfo['customer_id'];
			}

			// Likes
			$likes = $this->calculateLikes($article_id, $result['article_comment_id']);
			$customer_can_like = (int)(!($result['customer_id'] == $customer_id));
			$customer_rating = $this->model_cms_article->getCustomerRating($article_id, $result['article_comment_id']);

			$data['comments'][] = [
				'comment'     			=> nl2br($result['comment']),
				'date_modified'  		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'like'       			=> $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&rate=1&comment_token=' . $this->session->data['comment_token'], true),
				'dislike'     			=> $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&rate=0&comment_token=' . $this->session->data['comment_token'], true),
				'total_likes' 			=> $likes,
				'customer_can_like' 	=> $customer_can_like,
				'customer_did_like' 	=> $customer_rating,
				'edit'		  			=> ($result['customer_id'] == $customer_id) ? $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true) : '',
				'delete'	  			=> ($result['customer_id'] == $customer_id) ? $this->url->link('cms/comment.delete', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true) : '',
				'reply'       			=> $this->url->link('cms/comment.reply', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true),
				'reply_add'   			=> $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true),
				'reply_total' 			=> $this->model_cms_article->getTotalComments($article_id, ['parent_id' => $result['article_comment_id']]),
				'author' 				=> $author,
				'is_admin' 				=> (int)($author_id == 0)
			] + $result;
		}

		$url = '';
		
		// Sort
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Articles
		$comment_total = $this->model_cms_article->getTotalComments($article_id, $filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&page={page}' . $url)
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

		// Customer
		$this->load->model('account/customer');

		// Create the Edit and Delete urls only for comments posted by logged-in customer. 
		$customer_id = ($this->customer->isLogged()) ? $this->customer->getId() : -1;

		// Comment Replies
		foreach ($results as $result) {
			$author_id = 0;
			$author = $this->language->get('text_admin_author');
			
			$customerInfo = $this->model_account_customer->getCustomer($result['customer_id']);
			
			// Author
			if ($customerInfo) {
				$author = ($customerInfo['author']) ? $customerInfo['author'] : $customerInfo['firstname'] . ' ' . $customerInfo['lastname'];
				$author_id = $customerInfo['customer_id'];
			}

			// Likes
			$likes = $this->calculateLikes($article_id, $result['article_comment_id']);
			$customer_can_like = (int)(!($result['customer_id'] == $customer_id));
			$customer_rating = $this->model_cms_article->getCustomerRating($article_id, $result['article_comment_id']);

			$data['replies'][] = [
				'comment'    			=> nl2br($result['comment']),
				'date_modified' 		=> date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
				'like'       			=> $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&rate=1&comment_token=' . $this->session->data['comment_token'], true),
				'dislike'    			=> $this->url->link('cms/comment.rate', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] .'&rate=0&comment_token=' . $this->session->data['comment_token'], true),
				'total_likes' 			=> $likes,
				'customer_can_like' 	=> $customer_can_like,
				'customer_did_like' 	=> $customer_rating,
				'edit'    	 			=> ($result['customer_id'] == $customer_id) ? $this->url->link('cms/comment.add', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&parent_id=' . $parent_id . '&comment_token=' . $this->session->data['comment_token'], true) : '',
				'delete'     			=> ($result['customer_id'] == $customer_id) ? $this->url->link('cms/comment.delete', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&article_comment_id=' . $result['article_comment_id'] . '&comment_token=' . $this->session->data['comment_token'], true) : '',
				'author' 				=> $author,
				'is_admin' 				=> (int)($author_id == 0)
			] + $result;
		}

		// Total Articles
		$reply_total = $this->model_cms_article->getTotalComments($article_id, $filter_data);

		$data['refresh'] = $this->url->link('cms/comment.reply', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&parent_id=' . $parent_id . '&page=' . $page, true);

		$data['logged'] = $this->customer->isLogged();
		$data['login'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language') . '&redirect=' . urlencode($this->url->link('cms/blog.info', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id, true)));

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
	 * Add or Edit either a Comment OR a Reply.
	 * If `article_comment_id` is 0, Add a New Comment, otherwise Edit existing Comment.
	 *
	 * Only Comments have a Rating. Replies to Comments do not have a rating. 
	 * If $post_info['rating'] equals 0, then we are adding or editing a Reply.
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

		if (isset($this->request->get['article_comment_id']) && $this->request->get['article_comment_id'] > 0) {
			$article_comment_id = (int)$this->request->get['article_comment_id'];
		} else {
			$article_comment_id = 0;
		}

		if (isset($this->request->post['rating'])) {
			$rating = (int)$this->request->post['rating'];
		} else {
			$rating = 0;
		}

		$required = [
			'comment' => ''
		];

		$post_info = $this->request->post + $required;

		$warning = '';

		if (!isset($this->request->get['comment_token']) || !isset($this->session->data['comment_token']) || $this->request->get['comment_token'] != $this->session->data['comment_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
			$warning = $json['error']['warning'];
		}

		if (!$this->customer->isLogged()) {
			$json['error']['warning'] = $this->language->get('error_login');
			$warning = $json['error']['warning'];
		}

		// Article
		$this->load->model('cms/article');

		$article_info = $this->model_cms_article->getArticle($article_id);

		if (!$article_info) {
			$json['error']['warning'] = $this->language->get('error_article');
			$warning = $json['error']['warning'];
		}

		if (!oc_validate_length($post_info['comment'], 25, 1000)) {
			$json['error']['comment'] = $this->language->get('error_comment');
			$warning = $json['error']['comment'];
		}

		if ($parent_id == 0 && ($rating < 1 || $rating > 5)) {
			$json['error']['rating'] = $this->language->get('error_rating');
			$warning = $json['error']['rating'];
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
					$warning = $json['error']['warning'];

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
				$warning = $json['error']['captcha'];
			}
		}

		if (!$this->config->get('config_comment_status')) {
			$json['error']['warning'] = $this->language->get('error_status');
			$warning = $json['error']['warning'];
		}

		if (!empty($warning)) {
			$json['error']['warning'] = $warning;
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
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

			$comment_data = [
				'comment' 	=> $post_info['comment'],
				'parent_id' => $parent_id,
				'rating'	=> $rating,
				'status'    => $status
			];

			if ($article_comment_id) {
				$this->model_cms_article->editComment($article_comment_id, $comment_data);
			} else {
				$this->model_cms_article->addComment($article_id, $comment_data);
			}

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
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
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

		if (!$json) {
			$this->model_cms_article->deleteComment($article_comment_id);

			$this->model_cms_article->deleteRating($article_id, $article_comment_id);

			$json['success'] = $this->language->get('text_delete_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Rate
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
			$rating = (int)$this->request->get['rate'];
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

		// Get the Comment to rate
		if ($article_comment_id) {
			$article_comment_info = $this->model_cms_article->getComment($article_comment_id);

			if (!$article_comment_info) {
				$json['error'] = $this->language->get('error_article_comment');
			}
		}

		if (!$json) {
			// Check for existing customer rating (likes)
			$customer_rating = $this->model_cms_article->getCustomerRating($article_id, $article_comment_id);

			if ($customer_rating == '') {
				// Add new rating
				$this->model_cms_article->addRating($article_id, $article_comment_id, $rating);
				$json['liked'] = $rating;
			} else {
				// Delete existing rating
				$this->model_cms_article->deleteRating($article_id, $article_comment_id);
				$json['liked'] = '';
			}

			$json['success'] = $this->language->get('text_like_success');

			// Re-get the total likes
			$json['total_likes'] = $this->calculateLikes($article_id, $article_comment_id);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Calculate Likes
	 *
	 * @return int
	 */
	public function calculateLikes(int $article_id, int $article_comment_id): int {
		$this->load->model('cms/article');

		$total_rating = 0;

		// Likes
		$ratings = $this->model_cms_article->getLikes($article_id, $article_comment_id);

		foreach ($ratings as $rating) {
			if ($rating['rating'] == 1) {
				$total_rating += $rating['total'];
			} else {
				$total_rating -= $rating['total'];
			}
		}

		return $total_rating;
	}
}
