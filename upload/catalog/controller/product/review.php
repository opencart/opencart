<?php
namespace Opencart\Catalog\Controller\Product;
use \Opencart\System\Helper as Helper;
class Review extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('product/review');

		$data['list'] = $this->getList();

		if (isset($this->request->get['product_id'])) {
			$data['product_id'] = $this->request->get['product_id'];
		} else {
			$data['product_id'] = 0;
		}

		if ($this->customer->isLogged() || $this->config->get('config_review_guest')) {
			$data['review_guest'] = true;
		} else {
			$data['review_guest'] = false;
		}

		if ($this->customer->isLogged()) {
			$data['customer_name'] = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
		} else {
			$data['customer_name'] = '';
		}

		// Create a login token to prevent brute force attacks
		$this->session->data['review_token'] = Helper\General\token(32);

		$data['review_token'] = $this->session->data['review_token'];

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$data['captcha'] = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code']);
		} else {
			$data['captcha'] = '';
		}

		$data['language'] = $this->config->get('config_language');

		return $this->load->view('product/review', $data);
	}

	public function write(): void {
		$this->load->language('product/review');

		$json = [];

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (!isset($this->request->get['review_token']) || !isset($this->session->data['review_token']) || $this->request->get['review_token'] != $this->session->data['review_token']) {
			$json['error']['warning'] = $this->language->get('error_token');
		}

		$keys = [
			'name',
			'text',
			'rating'
		];

		foreach ($keys as $key) {
			if (!isset($this->request->post[$key])) {
				$this->request->post[$key] = '';
			}
		}

		if ((Helper\Utf8\strlen($this->request->post['name']) < 3) || (Helper\Utf8\strlen($this->request->post['name']) > 25)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if ((Helper\Utf8\strlen($this->request->post['text']) < 25) || (Helper\Utf8\strlen($this->request->post['text']) > 1000)) {
			$json['error']['text'] = $this->language->get('error_text');
		}

		if ($this->request->post['rating'] < 1 || $this->request->post['rating'] > 5) {
			$json['error']['rating']  = $this->language->get('error_rating');
		}

		if (!$this->customer->isLogged() && !$this->config->get('config_review_guest')) {
			$json['error']['warning']  = $this->language->get('error_guest');
		}

		if ($this->customer->isLogged() && $this->config->get('config_review_purchased')) {
			$this->load->model('account/order');

			if (!$this->model_account_order->getTotalOrdersByProductId($product_id)) {
				$json['error']['purchased']  = $this->language->get('error_purchased');
			}
		}

		// Captcha
		$this->load->model('setting/extension');

		$extension_info = $this->model_setting_extension->getExtensionByCode('captcha', $this->config->get('config_captcha'));

		if ($extension_info && $this->config->get('captcha_' . $this->config->get('config_captcha') . '_status') && in_array('review', (array)$this->config->get('config_captcha_page'))) {
			$captcha = $this->load->controller('extension/'  . $extension_info['extension'] . '/captcha/' . $extension_info['code'] . '|validate');

			if ($captcha) {
				$json['error']['captcha'] = $captcha;
			}
		}

		if (!$json) {
			$this->load->model('catalog/review');

			$this->model_catalog_review->addReview($product_id, $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function list(): void {
		$this->load->language('product/review');

		$this->response->setOutput($this->getList());
	}

	public function getList(): string {
		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = [];

		$this->load->model('catalog/review');

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($product_id);

		$results = $this->model_catalog_review->getReviewsByProductId($product_id, ($page - 1) * 5, 5);

		foreach ($results as $result) {
			$data['reviews'][] = [
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $review_total,
			'page'  => $page,
			'limit' => 5,
			'url'   => $this->url->link('product/review|list', 'language=' . $this->config->get('config_language') . '&product_id=' . $product_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 5) + 1 : 0, ((($page - 1) * 5) > ($review_total - 5)) ? $review_total : ((($page - 1) * 5) + 5), $review_total, ceil($review_total / 5));

		return $this->load->view('product/review_list', $data);
	}
}
