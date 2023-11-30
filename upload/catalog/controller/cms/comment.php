<?php
namespace Opencart\Catalog\Controller\Cms;
/**
 * Class Comment
 *
 * @package Opencart\Catalog\Controller\Cms
 */
class Comment extends \Opencart\System\Engine\Controller {
	/**
	 * @return string
	 */
	public function index(): string {
		$this->load->language('cms/comment');

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', 'language=' . $this->config->get('config_language')), $this->url->link('account/register', 'language=' . $this->config->get('config_language')));

		$data['list'] = $this->getList();

		$data['article_id'] = $article_id;

		if ($this->customer->isLogged() || $this->config->get('config_comment_guest')) {
			$data['comment_guest'] = true;
		} else {
			$data['comment_guest'] = false;
		}

		if ($this->customer->isLogged()) {
			$data['customer'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		} else {
			$data['customer'] = '';
		}

		// Create a login token to prevent brute force attacks
		$this->session->data['comment_token'] = oc_token(32);

		$data['comment_token'] = $this->session->data['comment_token'];

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('comment', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('cms/comment', $data);
	}

	/**
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/comment');

		$this->response->setOutput($this->getList());
	}

	/**
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['comments'] = [];

		$this->load->model('cms/article');

		$results = $this->model_cms_article->getComments($article_id, ($page - 1) * (int)$this->config->get('config_pagination_admin'), (int)$this->config->get('config_pagination_admin'));

		foreach ($results as $result) {
			$data['comments'][] = [
				'article_comment_id' => $result['article_comment_id'],
				'comment'            => nl2br($result['comment']),
				'author'             => $result['author'],
				'date_added'         => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$comment_total = $this->model_cms_article->getTotalComments($article_id);
		
		$limit = 5;
		
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('cms/blog.comment.list', 'language=' . $this->config->get('config_language') . '&article_id=' . $article_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($comment_total - $limit)) ? $comment_total : ((($page - 1) * $limit) + $limit), $comment_total, ceil($comment_total / $limit));

		return $this->load->view('cms/comment_list', $data);
	}

	/**
     * @return void
     */
	public function write(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->get['article_id'])) {
			$article_id = (int)$this->request->get['article_id'];
		} else {
			$article_id = 0;
		}

		if (!isset($this->request->get['comment_token']) || !isset($this->session->data['comment_token']) || $this->request->get['comment_token'] != $this->session->data['comment_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		$keys = [
			'author',
			'comment'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if (!$this->config->get('config_comment_status')) {
			$json['error']['warning'] = $this->language->get('error_status');
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

		if ((oc_strlen($this->request->post['comment']) < 2) || (oc_strlen($this->request->post['comment']) > 1000)) {
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
			$this->load->model('cms/antispam');

			$spam = $this->model_cms_antispam->getSpam($this->request->post['comment']);

			if (!$this->customer->isCommenter() && $spam) {
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
