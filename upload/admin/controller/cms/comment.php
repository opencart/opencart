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

	public function index() {
		$this->load->language('cms/comment');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('cms/comment', 'user_token=' . $this->session->data['user_token'])
		);

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
			$filter_keyword = $this->request->get['filter_keyword'];
		} else {
			$filter_keyword = '';
		}

		if (isset($this->request->get['filter_title'])) {
			$filter_title = $this->request->get['filter_title'];
		} else {
			$filter_title = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = 0;
		}

		if (isset($this->request->get['filter_date_added'])) {
			$filter_date_added = $this->request->get['filter_date_added'];
		} else {
			$filter_date_added = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['comments'] = array();

		$filter_data = array(
			'filter_keyword'    => $filter_keyword,
			'filter_title'      => $filter_title,
			'filter_customer'   => $filter_customer,
			'filter_status'     => $filter_status,
			'filter_date_added' => $filter_date_added,
			'start'             => ($page - 1) * 10,
			'limit'             => 10
		);

		$this->load->model('cms/article');

		$comment_total = $this->model_cms_article->getTotalComments($filter_data);

		$results = $this->model_cms_article->getComments($filter_data);

		foreach ($results as $result) {
			if (!$result['status']) {
				$approve = $this->url->link('cms/comment.approve', 'user_token=' . $this->session->data['user_token'] . '&comment_id=' . $result['comment_id'] . $url);
			} else {
				$approve = '';
			}

			$data['comments'][] = array(
				'article'       => $result['article'],
				'article_edit'  => $this->url->link('cms/article.edit', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id']),
				'customer'      => $result['customer'],
				'customer_edit' => $this->url->link('customer/customer.edit', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $result['customer_id']),
				'comment'       => nl2br($result['comment']),
				'date_added'    => date('d/m/Y', strtotime($result['date_added'])),
				'approve'       => $approve,
				'spam'          => $this->url->link('cms/comment.spam', 'user_token=' . $this->session->data['user_token'] . '&comment_id=' . $result['comment_id'] . $url),
				'delete'        => $this->url->link('cms/comment.delete', 'user_token=' . $this->session->data['user_token'] . '&comment_id=' . $result['comment_id'] . $url)
			);
		}

		$url = '';

		if (isset($this->request->get['filter_keyword'])) {
			$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_title'])) {
			$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
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

		$data['pagination'] = $this->load->controller('common/pagination', array(
			'total' => $comment_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('cms/comment.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		));

		$data['results'] = sprintf($this->language->get('text_pagination'), ($comment_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($comment_total - $this->config->get('config_pagination_admin'))) ? $comment_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $comment_total, ceil($comment_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('cms/comment_list', $data);
	}

	public function approve() {
		$this->load->language('cms/comment');

		$json = array();

		if (isset($this->request->get['article_comment_id'])) {
			$article_comment_id = $this->request->get['article_comment_id'];
		} else {
			$article_comment_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('cms/article');

		$comment_info = $this->model_cms_article->getComment($article_comment_id);

		if (!$comment_info) {
			$json['error'] = $this->language->get('error_comment');
		}

		if (!$json) {
			// Approve Commentor
			$this->load->model('customer/customer');

			$this->model_customer_customer->editCommentor($comment_info['customer_id'], 1);

			// Approve all past comments
			$filter_data = array(
				'filter_customer_id' => $comment_info['customer_id'],
				'filter_status'      => 0
			);

			$results = $this->model_cms_comment->getComments($filter_data);

			foreach ($results as $result) {
				$this->model_cms_comment->editStatus($result['customer_id'], 1);
			}

			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$json['redirect'] = $this->url->link('cms/comment.comment', 'user_token=' . $this->session->data['user_token'] . $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function spam() {
		$this->load->language('cms/comment');

		$json = array();

		if (isset($this->request->get['comment_id'])) {
			$comment_id = $this->request->get['comment_id'];
		} else {
			$comment_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('cms/article');

		$comment_info = $this->model_cms_article->getComment($comment_id);

		if (!$comment_info) {
			$json['error'] = $this->language->get('error_comment');
		}

		if (!$json) {
			$this->load->model('customer/customer');

			$this->model_customer_customer->editCommentor($comment_info['customer_id'], 0);
			$this->model_customer_customer->editStatus($comment_info['customer_id'], 0);
			$this->model_customer_customer->addHistory($comment_info['customer_id'], 'SPAMMER!!!');

			// Delete all customer comments
			$results = $this->model_cms_comment->getComments(array('filter_customer_id' => $comment_info['customer_id']));

			foreach ($results as $result) {
				$this->model_cms_comment->deleteCommentsByCustomerId($result['comment_id']);
			}

			$json['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$json['redirect'] = $this->url->link('cms/comment/comment', 'user_token=' . $this->session->data['user_token'] . $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('cms/comment');

		$json = array();

		if (isset($this->request->get['comment_id'])) {
			$comment_id = $this->request->get['comment_id'];
		} else {
			$comment_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'cms/comment')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('cms/article');

		$comment_info = $this->model_cms_article->getComment($comment_id);

		if (!$comment_info) {
			$json['error'] = $this->language->get('error_comment');
		}

		if (!$json) {
			$this->model_cms_article->deleteComment($comment_id);

			$json['success'] = $this->language->get('error_success');

			$url = '';

			if (isset($this->request->get['filter_keyword'])) {
				$url .= '&filter_keyword=' . urlencode(html_entity_decode($this->request->get['filter_keyword'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_title'])) {
				$url .= '&filter_title=' . urlencode(html_entity_decode($this->request->get['filter_title'], ENT_QUOTES, 'UTF-8'));
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

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$json['redirect'] = $this->url->link('cms/comment.comment', 'user_token=' . $this->session->data['user_token'] . $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}