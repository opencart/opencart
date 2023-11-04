<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Comments
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Comment extends \Opencart\System\Engine\Controller {
	/**
	 * @return void
	 */

	public function index(): void {
		$this->load->language('cms/comment');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('cms/comment', 'user_token=' . $this->session->data['user_token'])
		];

		$data['approve'] = $this->url->link('cms/comment.approve', 'user_token=' . $this->session->data['user_token']);
		$data['spam'] = $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token']);
		$data['delete'] = $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('cms/comment', $data));
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
		if (isset($this->request->get['filter_keyword'])) {
			$filter_keyword = (string)$this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['filter_article'])) {
			$filter_article = (string)$this->request->get['filter_article'];
		} else {
			$filter_article = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = (string)$this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = (int)$this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = (string)$this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode((string)$this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_article'])) {
			$url .= '&filter_article=' . urlencode(html_entity_decode((string)$this->request->get['filter_article'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode((string)$this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . (int)$this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . (string)$this->request->get['filter_date_added'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . (int)$this->request->get['page'];
		}

		$data['action'] = $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['comments'] = [];

		$filter_data = [
			'filter_keyword'    => $filter_keyword,
			'filter_article'    => $filter_article,
			'filter_customer'   => $filter_customer,
			'filter_status'     => $filter_status,
			'filter_date_added' => $filter_date_added,
			'start'             => ($page - 1) * 10,
			'limit'             => 10
		];

		$this->load->model('cms/article');

		$results = $this->model_cms_article->getComments($filter_data);

		foreach ($results as $result) {
			$article_info = $this->model_cms_article->getArticle($result['article_id']);

			if ($article_info) {
				$article = $article_info['name'];
			} else {
				$article = '';
			}

			if (!$result['status']) {
				$approve = $this->url->link('cms/comment.approve', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url);
			} else {
				$approve = '';
			}

			$data['comments'][] = [
				'article_comment_id' => $result['article_comment_id'],
				'article'            => $article,
				'article_edit'       => $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id']),
				'author'             => $result['author'],
				'customer_edit'      => $result['customer_id'] ? $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']) : '',
				'comment'            => nl2br($result['comment']),
				'status'             => $result['status'],
				'date_added'         => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'approve'            => $approve,
				'spam'               => $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'delete'             => $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url)
			];
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_article'])) {
			$url .= '&filter_article=' . urlencode(html_entity_decode($this->request->get['filter_article'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_added'])) {
			$url .= '&filter_date_added=' . $this->request->get['filter_date_added'];
		}

		$comment_total = $this->model_cms_article->getTotalComments($filter_data);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($comment_total - $this->config->get('config_pagination_admin'))) ? $comment_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $comment_total, ceil($comment_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('cms/comment_list', $data);
	}

	/**
	 * @return void
	 */
	public function approve(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/article');
			$this->load->model('customer/customer');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_article->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_article->editCommentStatus($article_comment_id, 1);

					if ($comment_info['customer_id']) {
						$this->model_customer_customer->editCommenter($comment_info['customer_id'], 1);

						$filter_data = [
							'filter_customer_id' => $comment_info['customer_id'],
							'filter_status'      => 0
						];

						$results = $this->model_cms_article->getComments($filter_data);

						foreach ($results as $result) {
							$this->model_cms_article->editCommentStatus($result['article_comment_id'], 1);
						}
					}
				}
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * @return void
	 */
	public function spam(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/article');
			$this->load->model('customer/customer');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_article->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_article->editCommentStatus($article_comment_id, 0);

					if ($comment_info['customer_id']) {
						$this->model_customer_customer->editCommenter($comment_info['customer_id'], 0);
						$this->model_customer_customer->addHistory($comment_info['customer_id'], 'SPAMMER!!!');

						// Delete all customer comments
						$results = $this->model_cms_article->getComments(['filter_customer_id' => $comment_info['customer_id']]);

						foreach ($results as $result) {
							$this->model_cms_article->deleteComment($result['article_comment_id']);
						}
					}
				}
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
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('cms/article');

			foreach ($selected as $article_comment_id) {
				$this->model_cms_article->deleteComment($article_comment_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
