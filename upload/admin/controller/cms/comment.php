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

		if (isset($this->request->get['filter_article'])) {
			$filter_article = (string)$this->request->get['filter_article'];
		} else {
			$filter_article = '';
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = (string)$this->request->get['filter_author'];
		} else {
			$filter_author = '';
		}

		if (isset($this->request->get['filter_comment'])) {
			$filter_comment = (string)$this->request->get['filter_comment'];
		} else {
			$filter_comment = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = (string)$this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_admin'])) {
			$filter_admin = $this->request->get['filter_admin'];
		} else {
			$filter_admin = '';
		}

		if (isset($this->request->get['filter_comment_type'])) {
			$filter_comment_type = $this->request->get['filter_comment_type'];
		} else {
			$filter_comment_type = '';
		}

		if (isset($this->request->get['filter_rating_from'])) {
			$filter_rating_from = (int)$this->request->get['filter_rating_from'];
		} else {
			$filter_rating_from = '';
		}

		if (isset($this->request->get['filter_rating_to'])) {
			$filter_rating_to = (int)$this->request->get['filter_rating_to'];
		} else {
			$filter_rating_to = '';
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

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_article'])) {
			$url .= '&filter_article=' . urlencode(html_entity_decode($this->request->get['filter_article'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_comment'])) {
			$url .= '&filter_comment=' . urlencode(html_entity_decode($this->request->get['filter_comment'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_admin'])) {
			$url .= '&filter_admin=' . $this->request->get['filter_admin'];
		}

		if (isset($this->request->get['filter_comment_type'])) {
			$url .= '&filter_comment_type=' . $this->request->get['filter_comment_type'];
		}

		if (isset($this->request->get['filter_rating_from'])) {
			$url .= '&filter_rating_from=' . $this->request->get['filter_rating_from'];
		}

		if (isset($this->request->get['filter_rating_to'])) {
			$url .= '&filter_rating_to=' . $this->request->get['filter_rating_to'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		// Sort
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('cms/comment', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['enable'] = $this->url->link('cms/comment.enable', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['disable'] = $this->url->link('cms/comment.disable', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['spam'] = $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['save'] = $this->url->link('cms/comment.save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['list'] = $this->getList();

		$data['filter_article'] = $filter_article;
		$data['filter_author'] = $filter_author;
		$data['filter_comment'] = $filter_comment;
		$data['filter_customer'] = $filter_customer;
		$data['filter_admin'] = $filter_admin;
		$data['filter_comment_type'] = $filter_comment_type;
		$data['filter_rating_from'] = $filter_rating_from;
		$data['filter_rating_to'] = $filter_rating_to;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;
		$data['filter_status'] = $filter_status;

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
	 * Request filters:
	 *
	 * $this->request->get = [
	 *     'filter_article'   		=> 'Article Name',
	 *     'filter_author'    		=> 'John Doe',
	 *     'filter_comment'   		=> 'I love OpenCart',
	 *     'filter_customer'  		=> 'John Doe',
	 *     'filter_status'    		=> 1,
	 *     'filter_admin'    		=> 1,
	 *     'filter_comment_type'	=> 1,
	 *     'filter_rating_from' 	=> 1,
	 *     'filter_rating_to'   	=> 5,
	 *     'filter_date_from' 		=> '2021-01-01',
	 *     'filter_date_to'   		=> '2021-01-31',
	 *     'start'            		=> 0,
	 *     'limit'            		=> 10
	 * ];
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_article'])) {
			$filter_article = (string)$this->request->get['filter_article'];
		} else {
			$filter_article = '';
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = (string)$this->request->get['filter_author'];
		} else {
			$filter_author = '';
		}

		if (isset($this->request->get['filter_comment'])) {
			$filter_comment = (string)$this->request->get['filter_comment'];
		} else {
			$filter_comment = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = (string)$this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_admin'])) {
			$filter_admin = $this->request->get['filter_admin'];
		} else {
			$filter_admin = '';
		}

		if (isset($this->request->get['filter_comment_type'])) {
			$filter_comment_type = $this->request->get['filter_comment_type'];
		} else {
			$filter_comment_type = '';
		}

		if (isset($this->request->get['filter_rating_from'])) {
			$filter_rating_from = (int)$this->request->get['filter_rating_from'];
		} else {
			$filter_rating_from = '';
		}

		if (isset($this->request->get['filter_rating_to'])) {
			$filter_rating_to = (int)$this->request->get['filter_rating_to'];
		} else {
			$filter_rating_to = '';
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

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		// Sort
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_article'])) {
			$url .= '&filter_article=' . urlencode(html_entity_decode((string)$this->request->get['filter_article'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode((string)$this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_comment'])) {
			$url .= '&filter_comment=' . urlencode(html_entity_decode((string)$this->request->get['filter_comment'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode((string)$this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_admin'])) {
			$url .= '&filter_admin=' . $this->request->get['filter_admin'];
		}

		if (isset($this->request->get['filter_comment_type'])) {
			$url .= '&filter_comment_type=' . $this->request->get['filter_comment_type'];
		}

		if (isset($this->request->get['filter_rating_from'])) {
			$url .= '&filter_rating_from=' . $this->request->get['filter_rating_from'];
		}

		if (isset($this->request->get['filter_rating_to'])) {
			$url .= '&filter_rating_to=' . $this->request->get['filter_rating_to'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		// Sort Menu
		$data['sorts'] = [];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_asc'),
			'value' => 'date_added-ASC',
			'href'  => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_added&order=ASC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_date_added_desc'),
			'value' => 'date_added-DESC',
			'href'  => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=date_added&order=DESC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_asc'),
			'value' => 'rating-ASC',
			'href'  => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=rating&order=ASC')
		];

		$data['sorts'][] = [
			'text'  => $this->language->get('text_rating_desc'),
			'value' => 'rating-DESC',
			'href'  => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&sort=rating&order=DESC')
		];

		$data['sort'] = $sort;
		$data['order'] = $order;

		// Action
		$data['action'] = $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Comments
		$data['comments'] = [];

		$filter_data = [
			'filter_article'   		=> $filter_article,
			'filter_author'			=> $filter_author,
			'filter_comment'		=> $filter_comment,
			'filter_customer'		=> $filter_customer,
			'filter_admin'			=> $filter_admin,
			'filter_comment_type'	=> $filter_comment_type,
			'filter_rating_from'	=> $filter_rating_from,
			'filter_rating_to'		=> $filter_rating_to,
			'filter_date_from'		=> $filter_date_from,
			'filter_date_to'		=> $filter_date_to,
			'filter_status'			=> $filter_status,
			'sort'      			=> $sort,
			'order'     			=> $order,
			'start'					=> ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'					=> $this->config->get('config_pagination_admin')
		];

		$this->load->model('cms/article');
		$this->load->model('cms/comment');
		$this->load->model('customer/customer');

		$results = $this->model_cms_comment->getComments($filter_data);

		foreach ($results as $result) {
			$article_info = $this->model_cms_article->getArticle($result['article_id']);

			if ($article_info) {
				$article = $article_info['name'];
			} else {
				$article = '';
			}

			$parent_customer_id = 0;
			$parent_customer = $this->language->get('text_admin_customer');
			$parent_author = $this->language->get('text_admin_author');

			if ((int)$result['parent_id'] > 0) {
				// Parent Comment
				$parent_comment = $this->model_cms_comment->getCommentInfo($result['parent_id']);

				if ($parent_comment && $parent_comment['customer_id']) {
					// Customer
					$customer = $this->model_customer_customer->getCustomer($parent_comment['customer_id']);

					$parent_customer_id = $customer['customer_id'];
					$parent_customer = $customer['firstname'] . ' ' . $customer['lastname'];
					$parent_author = $customer['author'];
				}
			}

			$data['comments'][] = [
				'article'       		=> $article,
				'article_edit'  		=> $this->url->link('cms/article.form', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id']),
				'customer_edit' 		=> $result['customer_id'] ? $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']) : '',
				'comment'       		=> nl2br($result['comment']),
				'date_added'    		=> date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'status'        		=> $result['status'],
				'enable'        		=> $this->url->link('cms/comment.enable', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'disable'       		=> $this->url->link('cms/comment.disable', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'spam'          		=> $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'edit_url'  			=> $this->url->link('cms/comment.getComment', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'save'          		=> $this->url->link('cms/comment.save', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'delete'        		=> $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token'] . '&article_comment_id=' . $result['article_comment_id'] . $url),
				'parent_customer'   	=> $parent_customer,
				'parent_author'   		=> $parent_author,
				'parent_customer_edit'	=> ($parent_customer_id) ? $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $parent_customer_id) : '',
				'base_filter_url' 		=> $this->url->link('cms/comment', 'user_token=' . $this->session->data['user_token'])
			] + $result;
		}

		$url = '';

		if (isset($this->request->get['filter_article'])) {
			$url .= '&filter_article=' . urlencode(html_entity_decode($this->request->get['filter_article'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_author'])) {
			$url .= '&filter_author=' . urlencode(html_entity_decode($this->request->get['filter_author'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_comment'])) {
			$url .= '&filter_comment=' . urlencode(html_entity_decode($this->request->get['filter_comment'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_admin'])) {
			$url .= '&filter_admin=' . $this->request->get['filter_admin'];
		}

		if (isset($this->request->get['filter_comment_type'])) {
			$url .= '&filter_comment_type=' . $this->request->get['filter_comment_type'];
		}

		if (isset($this->request->get['filter_rating_from'])) {
			$url .= '&filter_rating_from=' . $this->request->get['filter_rating_from'];
		}

		if (isset($this->request->get['filter_rating_to'])) {
			$url .= '&filter_rating_to=' . $this->request->get['filter_rating_to'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		// Sort
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		// Total Comments
		$comment_total = $this->model_cms_comment->getTotalComments($filter_data);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $comment_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($comment_total - $this->config->get('config_pagination_admin'))) ? $comment_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $comment_total, ceil($comment_total / $this->config->get('config_pagination_admin')));

		$data['user_token'] = $this->session->data['user_token'];

		return $this->load->view('cms/comment_list', $data);
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
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
			// Comment
			$this->load->model('cms/comment');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_comment->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_comment->editCommentStatus($article_comment_id, true);
				}
			}

			$this->cache->delete('comment');

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
			// Comment
			$this->load->model('cms/comment');

			foreach ($selected as $article_comment_id) {
				$this->model_cms_comment->editCommentStatus($article_comment_id, false);
			}

			$this->cache->delete('comment');

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
			// Comment
			$this->load->model('cms/comment');

			// Customer
			$this->load->model('customer/customer');

			foreach ($selected as $article_comment_id) {
				$comment_info = $this->model_cms_comment->getComment($article_comment_id);

				if ($comment_info) {
					$this->model_cms_comment->editCommentStatus($article_comment_id, false);

					if ($comment_info['customer_id']) {
						$this->model_customer_customer->editCommenter($comment_info['customer_id'], false);
						$this->model_customer_customer->addHistory($comment_info['customer_id'], $this->language->get('text_spammer_history'));

						// Delete all customer comments
						$results = $this->model_cms_comment->getComments(['filter_customer_id' => $comment_info['customer_id']]);

						foreach ($results as $result) {
							$this->model_cms_comment->deleteComment($result['article_comment_id']);
						}
					} else {
						$this->model_cms_comment->deleteComment($article_comment_id);
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
			// Comment
			$this->load->model('cms/comment');

			foreach ($selected as $article_comment_id) {
				$this->model_cms_comment->deleteComment($article_comment_id);
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
	public function refresh(): void {
		$this->load->language('cms/comment');

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
			$this->load->model('cms/article');
			$this->load->model('cms/comment');

			$limit = 100;

			$filter_data = [
				'sort'  => 'ad.name',
				'order' => 'ASC'
			];

			// Articles
			$articles = $this->model_cms_article->getArticles($filter_data);

			foreach ($articles as $article) {
				$total_rating = 0;

				// Comment Ratings
				$ratings = $this->model_cms_comment->getCommentRatings($article['article_id']);

				foreach ($ratings as $rating) {
					$total_rating += $rating['rating'];
				}

				if (count($ratings)) {
					$total_rating = ($total_rating / (count($ratings)));
				}

				$this->model_cms_article->editRating($article['article_id'], $total_rating);
			}

			// Total Comments
			$comment_total = $this->model_cms_comment->getTotalComments();

			$start = ($page - 1) * $limit;
			$end = ($start > ($comment_total - $limit)) ? $comment_total : ($start + $limit);

			if ($end < $comment_total) {
				$json['text'] = sprintf($this->language->get('text_next'), $start ?: 1, $end, $comment_total);

				$json['next'] = $this->url->link('cms/comment.refresh', 'user_token=' . $this->session->data['user_token'] . '&page=' . ($page + 1), true);
			} else {
				$json['success'] = $this->language->get('text_success');

				$json['next'] = '';
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Save
	 *
	 * Example POST data:
	 *
	 * Array [
	 *   [article_id] => 1
	 *   [customer_name] => Filby
	 *   [rating] => 3
	 *   [comment] => Filby says "Hey". Added via Admin as customer  "Filby" (3069)
	 *   [status] => 0
	 *   [parent_id] => 0
	 *   [article_comment_id] => 0
	 *   [customer_id] => 3069
	 * ]
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('cms/comment');

		$json = [];

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'ip'     		=> ''
		];

		$post_info = $this->request->post + $required;

		$warning = '';

		// If article_comment_id == 0, then we are Adding
		$adding = (!$post_info['article_comment_id']);

		if (!isset($post_info['status'])) {
			$json['error']['status'] = $this->language->get('error_status');
			$warning = $json['error']['status'];
		}

		if (!$post_info['article_id']) {
			$json['error']['article_id'] = $this->language->get('error_article_id');
			$warning = $json['error']['article_id'];
		}

		if (!isset($post_info['article_comment_id'])) {
			$json['error']['comment_id'] = $this->language->get('error_comment_id');
			$warning = $json['error']['comment_id'];
		}

		if (!isset($post_info['parent_id'])) {
			$json['error']['parent_id'] = $this->language->get('error_parent_id');
			$warning = $json['error']['parent_id'];
		} else {
			// If parent_id > 0, then it is a reply. No Ratings!
			if ($post_info['parent_id']) {
				$post_info['rating'] = 0;
			} else {
				// Comment requires rating.
				if (!isset($post_info['rating'])) {
					$json['error']['rating'] = $this->language->get('error_rating');
					$warning = $json['error']['rating'];
				} else if ($post_info['rating'] < 1 || $post_info['rating'] > 5) {
					$json['error']['rating'] = $this->language->get('error_rating');
					$warning = $json['error']['rating'];
				}
			}
		}

		if ($adding) {
			// Customer Name length of 0 is allowed. Creates an Admin comment.
			if (!oc_validate_length($post_info['customer_name'], 0, 64)) {
				$json['error']['customer_name'] = $this->language->get('error_customer_name');
				$warning = $json['error']['customer_name'];
			}
		}

		if (!oc_validate_length($post_info['comment'], 25, 1000)) {
			$json['error']['comment'] = $this->language->get('error_comment');
			$warning = $json['error']['comment'];
		}

		if (!empty($warning)) {
			$json['error']['warning'] = $warning;
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Comment
			$this->load->model('cms/comment');

			if ($adding) {
				$json['article_comment_id'] = $this->model_cms_comment->addComment($post_info);
			} else {
				$this->model_cms_comment->editComment($post_info['article_comment_id'], $post_info);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Get Comment
	 *
	 * @return void
	 */
	public function getComment(): void {
		$this->load->language('cms/comment');

		$this->load->model('cms/comment');

		$json = [];

		if (isset($this->request->get['article_comment_id'])) {
			$id = $this->request->get['article_comment_id'];
		} else {
			$id = 0;
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$info = $this->model_cms_comment->getCommentInfo($id);

			if (!$info) {
				$json['warning'] = $this->language->get('error_not_found') . ' - ' . $id;
			} else {
				$json['success'] = $this->language->get('text_success');
				$json['item_info'] = $info;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Get menu select-options.
	 *
	 * @return void
	 */
	public function getArticleSelectOptions(): void {
		$this->load->model('cms/comment');

		$articles = $this->model_cms_comment->getArticleSelectOptions();

		$select_options = '';

		foreach ($articles as $article) {
			$select_options .= '<option value="' . $article['article_id'] . '">' . $article['name'] . '</option>';
		}

		$this->response->addHeader('Content-Type: text/html; charset=utf-8');
		$this->response->setOutput($select_options);
	}

	/**
	 * Autocomplete
	 *
	 * @return void
	 */
	public function autocomplete(): void {
		$json = [];

		$this->load->model('cms/comment');

		if (isset($this->request->get['filter_customers'])) {
	 		$this->load->model('customer/customer');

			// Customer
			$filter_data = [
				'filter_name'		=> $this->request->get['filter_customers'],
				'filter_status'		=> 1,
				'sort'       		=> 'name',
				'order'     		=> 'ASC',
				'start'       		=> 0,
				'limit'       		=> $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_customer_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$name = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));

				$names = [];

				foreach ($json as $key => $value) {
					$names[] = $value['name'];
				}

				if (!in_array($name, $names)) {
					$json[] = [
						'customer_id'   => $result['customer_id'],
						'name'          => $name
					];
				}
			}

		} else if (isset($this->request->get['filter_article'])) {
			// Article
			$filter_data = [
				'filter_article'	=> $this->request->get['filter_article'],
				'sort'       		=> 'name',
				'order'     		=> 'ASC',
				'start'       		=> 0,
				'limit'       		=> $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_cms_comment->getComments($filter_data);

			foreach ($results as $result) {
				$name = strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'));

				$names = [];

				foreach ($json as $key => $value) {
					$names[] = $value['name'];
				}

				if (!in_array($name, $names)) {
					$json[] = [
						'article_comment_id'	=> $result['article_comment_id'],
						'name'         			=> $name
					];
				}
			}
		} else if (isset($this->request->get['filter_author'])) {
			// Author
			$filter_data = [
				'filter_author'		=> $this->request->get['filter_author'],
				'sort'       		=> 'author',
				'order'     		=> 'ASC',
				'start'       		=> 0,
				'limit'       		=> $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_cms_comment->getComments($filter_data);

			foreach ($results as $result) {
				$author = strip_tags(html_entity_decode($result['author'], ENT_QUOTES, 'UTF-8'));

				$authors = [];

				foreach ($json as $key => $value) {
					$authors[] = $value['name'];
				}

				if (!in_array($author, $authors)) {
					$json[] = [
						'customer_id'   => $result['customer_id'],
						'name'          => $author
					];
				}
			}
		} else if (isset($this->request->get['filter_customer'])) {
			// Customer
			$filter_data = [
				'filter_customer'	=> $this->request->get['filter_customer'],
				'sort'       		=> 'customer',
				'order'     		=> 'ASC',
				'start'       		=> 0,
				'limit'       		=> $this->config->get('config_autocomplete_limit')
			];

			$results = $this->model_cms_comment->getComments($filter_data);

			foreach ($results as $result) {
				$customer = strip_tags(html_entity_decode($result['customer'], ENT_QUOTES, 'UTF-8'));

				$customers = [];

				foreach ($json as $key => $value) {
					$customers[] = $value['name'];
				}

				if ($result['customer_id'] != '0') {
					if (!in_array($customer, $customers)) {
						$json[] = [
							'customer_id' => $result['customer_id'],
							'name'        => $customer
						];
					}
				}
			}
		}

		$sort_order = [];

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
