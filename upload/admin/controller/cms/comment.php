<?php
namespace Opencart\Admin\Controller\Cms;
/**
 * Class Comments
 *
 * @package Opencart\Admin\Controller\Cms
 */
class Comment extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
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
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('cms/comment');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
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
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = (string)$this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = (string)$this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_keyword',
			'filter_article',
			'filter_customer',
			'filter_status',
			'filter_date_from',
			'filter_date_to',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['comments'] = [];

		// Article
		$filter_data = [
			'filter_keyword'   => $filter_keyword,
			'filter_article'   => $filter_article,
			'filter_customer'  => $filter_customer,
			'filter_status'    => $filter_status,
			'filter_date_from' => $filter_date_from,
			'filter_date_to'   => $filter_date_to,
			'start'            => ($page - 1) * 10,
			'limit'            => 10
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
				'article'       => $article,
				'article_edit'  => $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id']),
				'customer_edit' => $result['customer_id'] ? $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']) : '',
				'comment'       => nl2br($result['comment']),
				'date_added'    => date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'approve'       => $approve,
				'spam'          => $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'delete'        => $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url)
			] + $result;
		}

		$allowed = [
			'filter_keyword',
			'filter_article',
			'filter_customer',
			'filter_status',
			'filter_date_from',
			'filter_date_to'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Comments
		$comment_total = $this->model_cms_article->getTotalComments($filter_data);

		// Pagination
		$data['total'] = $comment_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($comment_total - $this->config->get('config_pagination_admin'))) ? $comment_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $comment_total, ceil($comment_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('cms/comment_list', $data);
	}

	/**
	 * Approve
	 *
	 * @return void
	 */
	public function approve(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (isset($this->request->get['article_comment_id'])) {
			$selected[] = (int)$this->request->get['article_comment_id'];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Article
			$this->load->model('cms/article');

			// Customer
			$this->load->model('customer/customer');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_article->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_article->editCommentStatus($article_comment_id, true);

					if ($comment_info['customer_id']) {
						$this->model_customer_customer->editCommenter($comment_info['customer_id'], true);

						$filter_data = [
							'filter_customer_id' => $comment_info['customer_id'],
							'filter_status'      => 0
						];

						$results = $this->model_cms_article->getComments($filter_data);

						foreach ($results as $result) {
							$this->model_cms_article->editCommentStatus($result['article_comment_id'], true);
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
	 * Spam
	 *
	 * @return void
	 */
	public function spam(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (isset($this->request->get['article_comment_id'])) {
			$selected[] = (int)$this->request->get['article_comment_id'];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Article
			$this->load->model('cms/article');

			// Customer
			$this->load->model('customer/customer');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_article->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_article->editCommentStatus($article_comment_id, false);

					if ($comment_info['customer_id']) {
						$this->model_customer_customer->editCommenter($comment_info['customer_id'], false);
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
	 * Delete
	 *
	 * @return void
	 */
	public function delete(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (isset($this->request->get['article_comment_id'])) {
			$selected[] = (int)$this->request->get['article_comment_id'];
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Article
			$this->load->model('cms/article');

			foreach ($selected as $article_comment_id) {
				$this->model_cms_article->deleteComment($article_comment_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Refresh
	 *
	 * @return void
	 */
	public function rating(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$limit = 100;

			// Article
			$filter_data = [
				'sort'  => 'date_added',
				'order' => 'ASC',
				'start' => ($page - 1) * $limit,
				'limit' => $limit
			];

			$this->load->model('cms/article');

			$results = $this->model_cms_article->getComments($filter_data);

			foreach ($results as $result) {
				$like = 0;
				$dislike = 0;

				$ratings = $this->model_cms_article->getRatings($result['article_id'], $result['article_comment_id']);

				foreach ($ratings as $rating) {
					if ($rating['rating'] == 1) {
						$like = $rating['total'];
					}

					if ($rating['rating'] == 0) {
						$dislike = $rating['total'];
					}
				}

				$this->model_cms_article->editCommentRating($result['article_id'], $result['article_comment_id'], $like - $dislike);
			}

			// Total Comments
			$comment_total = $this->model_cms_article->getTotalComments();

			$start = ($page - 1) * $limit;
			$end = ($start > ($comment_total - $limit)) ? $comment_total : ($start + $limit);

			if ($end < $comment_total) {
				$json['text'] = sprintf($this->language->get('text_next'), $start ?: 1, $end, $comment_total);

				$json['next'] = $this->url->link('cms/comment.rating', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
