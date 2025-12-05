<?php
namespace Opencart\Admin\Controller\Catalog;
/**
 * Class Review
 *
 * Can be loaded using $this->load->controller('catalog/review');
 *
 * @package Opencart\Admin\Controller\Catalog
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('catalog/review');

		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$allowed = [
			'filter_product',
			'filter_author',
			'filter_status',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('catalog/review.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('catalog/review.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('catalog/review.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('catalog/review.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['filter_product'] = $filter_product;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('catalog/review');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['filter_product'])) {
			$filter_product = $this->request->get['filter_product'];
		} else {
			$filter_product = '';
		}

		if (isset($this->request->get['filter_author'])) {
			$filter_author = $this->request->get['filter_author'];
		} else {
			$filter_author = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = '';
		}

		if (isset($this->request->get['filter_date_from'])) {
			$filter_date_from = $this->request->get['filter_date_from'];
		} else {
			$filter_date_from = '';
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = '';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['sort'])) {
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$allowed = [
			'filter_product',
			'filter_author' ,
			'filter_status',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['action'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Reviews
		$data['reviews'] = [];

		$filter_data = [
			'filter_product'   => $filter_product,
			'filter_author'    => $filter_author,
			'filter_status'    => $filter_status,
			'filter_date_from' => $filter_date_from,
			'filter_date_to'   => $filter_date_to,
			'sort'             => $sort,
			'order'            => $order,
			'start'            => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'            => $this->config->get('config_pagination_admin')
		];

		$this->load->model('catalog/review');

		$results = $this->model_catalog_review->getReviews($filter_data);

		foreach ($results as $result) {
			$data['reviews'][] = [
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'enable'  => $this->url->link('catalog/review.enable', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'] . $url),
				'disable' => $this->url->link('catalog/review.disable', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'] . $url),
				'edit'       => $this->url->link('catalog/review.form', 'user_token=' . $this->session->data['user_token'] . '&review_id=' . $result['review_id'] . $url)
			] + $result;
		}

		$allowed = [
			'filter_name',
			'filter_model' ,
			'filter_category_id',
			'filter_manufacturer_id',
			'filter_price_from',
			'filter_price_to',
			'filter_quantity_from',
			'filter_quantity_to',
			'filter_store_id',
			'filter_language_id',
			'filter_status'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		// Sorts
		$data['sort_product'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_author'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . '&sort=author' . $url);
		$data['sort_rating'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . '&sort=rating' . $url);
		$data['sort_date_added'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_added' . $url);
		$data['sort_status'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);

		$allowed = [
			'filter_name',
			'filter_model' ,
			'filter_category_id',
			'filter_manufacturer_id',
			'filter_price_from',
			'filter_price_to',
			'filter_quantity_from',
			'filter_quantity_to',
			'filter_store_id',
			'filter_language_id',
			'filter_status',
			'sort',
			'order'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		// Total Reviews
		$review_total = $this->model_catalog_review->getTotalReviews($filter_data);

		// Pagination
		$data['total'] = $review_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('catalog/review.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($review_total - $this->config->get('config_pagination_admin'))) ? $review_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $review_total, ceil($review_total / $this->config->get('config_pagination_admin')));

		$data['filter_product'] = $filter_product;
		$data['filter_author'] = $filter_author;
		$data['filter_status'] = $filter_status;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('catalog/review_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('catalog/review');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$allowed = [
			'filter_product',
			'filter_author' ,
			'filter_status',
			'filter_date_from',
			'filter_date_to',
			'sort',
			'order',
			'page'
		];

		$url = '&' . http_build_query(array_intersect_key($this->request->get, array_flip($allowed)));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('catalog/review.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('catalog/review', 'user_token=' . $this->session->data['user_token'] . $url);

		// Review
		if (isset($this->request->get['review_id'])) {
			$this->load->model('catalog/review');

			$review_info = $this->model_catalog_review->getReview((int)$this->request->get['review_id']);
		}

		if (!empty($review_info)) {
			$data['review_id'] = $review_info['review_id'];
		} else {
			$data['review_id'] = 0;
		}

		if (!empty($review_info)) {
			$data['product_id'] = $review_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (!empty($review_info)) {
			$data['product'] = $review_info['product'];
		} else {
			$data['product'] = '';
		}

		if (!empty($review_info)) {
			$data['author'] = $review_info['author'];
		} else {
			$data['author'] = '';
		}

		if (!empty($review_info)) {
			$data['text'] = $review_info['text'];
		} else {
			$data['text'] = '';
		}

		if (!empty($review_info)) {
			$data['rating'] = $review_info['rating'];
		} else {
			$data['rating'] = '';
		}

		if (!empty($review_info)) {
			$data['date_added'] = ($review_info['date_added'] != '0000-00-00 00:00:00' ? $review_info['date_added'] : date('Y-m-d H:i:s'));
		} else {
			$data['date_added'] = date('Y-m-d H:i:s');
		}

		if (!empty($review_info)) {
			$data['status'] = $review_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/review_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('catalog/review');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'review_id'  => 0,
			'author'     => '',
			'product_id' => 0,
			'text'       => '',
			'rating'     => 0,
			'status'     => 0
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['author'], 3, 64)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		if (!$post_info['product_id']) {
			$json['error']['product'] = $this->language->get('error_product');
		}

		if (oc_strlen($post_info['text']) < 1) {
			$json['error']['text'] = $this->language->get('error_text');
		}

		if (!isset($post_info['rating']) || $post_info['rating'] < 0 || $post_info['rating'] > 5) {
			$json['error']['rating'] = $this->language->get('error_rating');
		}

		if (isset($json['error']) && !isset($json['error']['warning'])) {
			$json['error']['warning'] = $this->language->get('error_warning');
		}

		if (!$json) {
			// Review
			$this->load->model('catalog/review');

			if (!$post_info['review_id']) {
				$json['review_id'] = $this->model_catalog_review->addReview($post_info);
			} else {
				$this->model_catalog_review->editReview($post_info['review_id'], $post_info);
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
		$this->load->language('catalog/review');

		$json = [];

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$task_data = [
				'code'   => 'review',
				'action' => 'task/report/rating',
				'args'   => []
			];

			$this->load->model('setting/task');

			$this->model_setting_task->addTask($task_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Enable
	 *
	 * @return void
	 */
	public function enable(): void {
		$this->load->language('catalog/review');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// review
			$this->load->model('catalog/review');

			foreach ($selected as $review_id) {
				$this->model_catalog_review->editStatus((int)$review_id, true);
			}

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
		$this->load->language('catalog/review');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// review
			$this->load->model('catalog/review');

			foreach ($selected as $review_id) {
				$this->model_catalog_review->editStatus((int)$review_id, false);
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
		$this->load->language('catalog/review');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'catalog/review')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Review
			$this->load->model('catalog/review');

			foreach ($selected as $review_id) {
				$this->model_catalog_review->deleteReview($review_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
