<?php
namespace Opencart\Admin\Controller\Marketing;
/**
 * Class Coupon
 *
 * @package Opencart\Admin\Controller\Marketing
 */
class Coupon extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('marketing/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/coupon', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('marketing/coupon.form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('marketing/coupon.delete', 'user_token=' . $this->session->data['user_token']);
		$data['enable']	= $this->url->link('marketing/coupon.enable', 'user_token=' . $this->session->data['user_token']);
		$data['disable'] = $this->url->link('marketing/coupon.disable', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/coupon', $data));
	}

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('marketing/coupon');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
	public function getList(): string {
		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('marketing/coupon.list', 'user_token=' . $this->session->data['user_token'] . $url);

		// Coupons
		$data['coupons'] = [];

		$filter_data = [
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('marketing/coupon');

		$results = $this->model_marketing_coupon->getCoupons($filter_data);

		foreach ($results as $result) {
			$data['coupons'][] = ['edit' => $this->url->link('marketing/coupon.form', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $result['coupon_id'] . $url)] + $result;
		}

		// Total Coupons
		$coupon_total = $this->model_marketing_coupon->getTotalCoupons();

		// Pagination
		$data['total'] = $coupon_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('marketing/coupon.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($coupon_total - $this->config->get('config_pagination_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_pagination_admin')));

		return $this->load->view('marketing/coupon_list', $data);
	}

	/**
	 * Form
	 *
	 * @return void
	 */
	public function form(): void {
		$this->load->language('marketing/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['coupon_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('marketing/coupon', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('marketing/coupon.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketing/coupon', 'user_token=' . $this->session->data['user_token'] . $url);

		// Coupon
		if (isset($this->request->get['coupon_id'])) {
			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($this->request->get['coupon_id']);
		}

		if (!empty($coupon_info)) {
			$data['coupon_id'] = $coupon_info['coupon_id'];
		} else {
			$data['coupon_id'] = 0;
		}

		if (!empty($coupon_info)) {
			$data['name'] = $coupon_info['name'];
		} else {
			$data['name'] = '';
		}

		if (!empty($coupon_info)) {
			$data['code'] = $coupon_info['code'];
		} else {
			$data['code'] = '';
		}

		if (!empty($coupon_info)) {
			$data['type'] = $coupon_info['type'];
		} else {
			$data['type'] = '';
		}

		if (!empty($coupon_info)) {
			$data['discount'] = $coupon_info['discount'];
		} else {
			$data['discount'] = '';
		}

		if (!empty($coupon_info)) {
			$data['logged'] = $coupon_info['logged'];
		} else {
			$data['logged'] = '';
		}

		if (!empty($coupon_info)) {
			$data['shipping'] = $coupon_info['shipping'];
		} else {
			$data['shipping'] = '';
		}

		if (!empty($coupon_info)) {
			$data['total'] = $coupon_info['total'];
		} else {
			$data['total'] = '';
		}

		// Product
		if (!empty($coupon_info)) {
			$products = $this->model_marketing_coupon->getProducts($coupon_info['coupon_id']);
		} else {
			$products = [];
		}

		$this->load->model('catalog/product');

		$data['coupon_products'] = [];

		foreach ($products as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);

			if ($product_info) {
				$data['coupon_products'][] = [
					'product_id' => $product_info['product_id'],
					'name'       => $product_info['name']
				];
			}
		}

		// Category
		if (!empty($coupon_info)) {
			$categories = $this->model_marketing_coupon->getCategories($coupon_info['coupon_id']);
		} else {
			$categories = [];
		}

		$this->load->model('catalog/category');

		$data['coupon_categories'] = [];

		foreach ($categories as $category_id) {
			$category_info = $this->model_catalog_category->getCategory($category_id);

			if ($category_info) {
				$data['coupon_categories'][] = [
					'category_id' => $category_info['category_id'],
					'name'        => ($category_info['path'] ? $category_info['path'] . ' &gt; ' : '') . $category_info['name']
				];
			}
		}

		if (!empty($coupon_info)) {
			$data['date_start'] = ($coupon_info['date_start'] != '0000-00-00' ? $coupon_info['date_start'] : '');
		} else {
			$data['date_start'] = date('Y-m-d', time());
		}

		if (!empty($coupon_info)) {
			$data['date_end'] = ($coupon_info['date_end'] != '0000-00-00' ? $coupon_info['date_end'] : '');
		} else {
			$data['date_end'] = date('Y-m-d', strtotime('+1 month'));
		}

		if (!empty($coupon_info)) {
			$data['uses_total'] = $coupon_info['uses_total'];
		} else {
			$data['uses_total'] = 1;
		}

		if (!empty($coupon_info)) {
			$data['uses_customer'] = $coupon_info['uses_customer'];
		} else {
			$data['uses_customer'] = 1;
		}

		if (!empty($coupon_info)) {
			$data['status'] = $coupon_info['status'];
		} else {
			$data['status'] = true;
		}

		$data['history'] = $this->getHistory();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/coupon_form', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('marketing/coupon');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		$required = [
			'coupon_id'  => 0,
			'name'       => '',
			'code'       => '',
			'discount'   => 0.0,
			'type'       => '',
			'total'      => 0.0,
			'logged'     => 0,
			'shipping'   => 0,
			'date_start' => '',
			'date_end'   => ''
		];

		$post_info = $this->request->post + $required;

		if (!oc_validate_length($post_info['name'], 3, 128)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if (!oc_validate_length($post_info['code'], 3, 20)) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		// Coupon
		$this->load->model('marketing/coupon');

		$coupon_info = $this->model_marketing_coupon->getCouponByCode($post_info['code']);

		if ($coupon_info && (!$post_info['coupon_id'] || ($coupon_info['coupon_id'] != (int)$post_info['coupon_id']))) {
			$json['error']['code'] = $this->language->get('error_exists');
		}

		if (!$json) {
			if (!$post_info['coupon_id']) {
				$json['coupon_id'] = $this->model_marketing_coupon->addCoupon($post_info);
			} else {
				$this->model_marketing_coupon->editCoupon($post_info['coupon_id'], $post_info);
			}

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
		$this->load->language('marketing/coupon');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('marketing/coupon');

			foreach ($selected as $coupon_id) {
				$this->model_marketing_coupon->editStatus((int)$coupon_id, true);
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
		$this->load->language('marketing/coupon');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('marketing/coupon');

			foreach ($selected as $coupon_id) {
				$this->model_marketing_affiliate->editStatus((int)$coupon_id, false);
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
		$this->load->language('marketing/coupon');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = (array)$this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Coupon
			$this->load->model('marketing/coupon');

			foreach ($selected as $coupon_id) {
				$this->model_marketing_coupon->deleteCoupon($coupon_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * History
	 *
	 * @return void
	 */
	public function history(): void {
		$this->load->language('marketing/coupon');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		if (isset($this->request->get['coupon_id'])) {
			$coupon_id = (int)$this->request->get['coupon_id'];
		} else {
			$coupon_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'marketing/coupon.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$this->load->model('marketing/coupon');

		$data['histories'] = [];

		$results = $this->model_marketing_coupon->getHistories($coupon_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		// Total Histories
		$history_total = $this->model_marketing_coupon->getTotalHistories($coupon_id);

		// Pagination
		$data['total'] = $history_total;
		$data['page'] = $page;
		$data['limit'] = $this->config->get('config_pagination_admin');
		$data['pagination'] = $this->url->link('marketing/coupon.history', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $coupon_id . '&page={page}');

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($history_total - $limit)) ? $history_total : ((($page - 1) * $limit) + $limit), $history_total, ceil($history_total / $limit));

		return $this->load->view('marketing/coupon_history', $data);
	}
}
