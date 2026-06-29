<?php
namespace Opencart\Catalog\Controller\Product;
/**
 * Class Review
 *
 * Can be loaded using $this->load->controller('product/review');
 *
 * @package Opencart\Catalog\Controller\Product
 */
class Review extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return string
	 */
	public function index(): string {
		$this->load->language('product/review');

		$this->document->addScript('catalog/view/javascript/review.js');

		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = (int)$this->request->get['product_id'];
		} else {
			$data['product_id'] = 0;
		}

		$data['text_login'] = sprintf($this->language->get('text_login'), $this->url->link('account/login', 'language=' . $this->config->get('config_language')), $this->url->link('account/register', 'language=' . $this->config->get('config_language')));

		$data['list'] = $this->getList();

		if ($this->customer->isLogged() || $this->config->get('config_review_guest')) {
			$data['review_guest'] = true;
		} else {
			$data['review_guest'] = false;
		}

		if ($this->customer->isLogged()) {
			$data['customer'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		} else {
			$data['customer'] = '';
		}

		// Create a login token to prevent brute force attacks
		$data['review_token'] = $this->session->data['review_token'] = oc_token(32);

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('product/review', $data);
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('product/review');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 5;

		// Reviews
		$data['reviews'] = [];

		$this->load->model('catalog/review');

		$results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['reviews'][] = [
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Reviews
		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $review_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('product/review.list', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($review_total - $limit)) ? $review_total : ((($page - 1) * $limit) + $limit), $review_total, ceil($review_total / $limit));

		return $this->load->view('product/review_list', $data);
	}

	/**
	 * Write
	 *
	 * @return void
	 */
	public function write(): void {
		$this->load->language('product/review');

		$json = [];

		if (isset($this->request->get['product_id'])) {
			$product_id = (int)$this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (!isset($this->request->get['review_token']) || !isset($this->session->data['review_token']) || $this->request->get['review_token'] != $this->session->data['review_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		$required = [
			'author',
			'text',
			'rating'
		];

		$post_info = $this->request->post + $required;

		if (!$this->config->get('config_review_status')) {
			$json['error']['warning'] = $this->language->get('error_status');
		}

		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if (!$product_info) {
			$json['error']['warning'] = $this->language->get('error_product');
		}

		if (!oc_validate_length($post_info['author'], 3, 25)) {
			$json['error']['author'] = $this->language->get('error_author');
		}

		if (!oc_validate_length($post_info['text'], 25, 1000)) {
			$json['error']['text'] = $this->language->get('error_text');
		}

		if ($post_info['rating'] < 1 || $post_info['rating'] > 5) {
			$json['error']['rating'] = $this->language->get('error_rating');
		}

		if (!$this->customer->isLogged() && !$this->config->get('config_review_guest')) {
			$json['error']['warning'] = $this->language->get('error_login');
		}

		// Order
		if ($this->customer->isLogged() && $this->config->get('config_review_purchased')) {
			$this->load->model('account/order');

			if (!$this->model_account_order->getTotalOrdersByProductId($product_id)) {
				$json['error']['purchased'] = $this->language->get('error_purchased');
			}
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/' . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '.validate');

			if ($captcha) {
				$json['error']['captcha'] = $captcha;
			}
		}

		if (!$json) {
			// Review
			$this->load->model('catalog/review');

			$this->model_catalog_review->addReview($product_id, $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
