<?php
namespace Opencart\Admin\Controller\Marketing;
class Coupon extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('marketing/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

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

		$data['add'] = $this->url->link('marketing/coupon|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('marketing/coupon|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/coupon', $data));
	}

	public function list(): void {
		$this->load->language('marketing/coupon');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['action'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . $url);

		$data['coupons'] = [];

		$filter_data = [
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit' => $this->config->get('config_pagination_admin')
		];

		$this->load->model('marketing/coupon');

		$coupon_total = $this->model_marketing_coupon->getTotalCoupons();

		$results = $this->model_marketing_coupon->getCoupons($filter_data);

		foreach ($results as $result) {
			$data['coupons'][] = [
				'coupon_id'  => $result['coupon_id'],
				'name'       => $result['name'],
				'code'       => $result['code'],
				'discount'   => $result['discount'],
				'date_start' => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'   => date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'status'     => ($result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled')),
				'edit'       => $this->url->link('marketing/coupon|form', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $result['coupon_id'] . $url)
			];
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=name' . $url);
		$data['sort_code'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=code' . $url);
		$data['sort_discount'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=discount' . $url);
		$data['sort_date_start'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_start' . $url);
		$data['sort_date_end'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=date_end' . $url);
		$data['sort_status'] = $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . '&sort=status' . $url);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $coupon_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('marketing/coupon|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($coupon_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($coupon_total - $this->config->get('config_pagination_admin'))) ? $coupon_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $coupon_total, ceil($coupon_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('marketing/coupon_list', $data);
	}

	public function form(): void {
		$this->load->language('marketing/coupon');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !isset($this->request->get['coupon_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

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
			'href' => $this->url->link('marketing/coupon', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['save'] = $this->url->link('marketing/coupon|save', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['back'] = $this->url->link('marketing/coupon', 'user_token=' . $this->session->data['user_token'] . $url);

		if (isset($this->request->get['coupon_id'])) {
			$this->load->model('marketing/coupon');

			$coupon_info = $this->model_marketing_coupon->getCoupon($this->request->get['coupon_id']);
		}

		if (isset($this->request->get['coupon_id'])) {
			$data['coupon_id'] = (int)$this->request->get['coupon_id'];
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

		if (!empty($coupon_info)) {
			$products = $this->model_marketing_coupon->getProducts($this->request->get['coupon_id']);
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

		if (!empty($coupon_info)) {
			$categories = $this->model_marketing_coupon->getCategories($this->request->get['coupon_id']);
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

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('marketing/coupon_form', $data));
	}

	public function save(): void {
		$this->load->language('marketing/coupon');

		$json = [];

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 128)) {
			$json['error']['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen($this->request->post['code']) > 20)) {
			$json['error']['code'] = $this->language->get('error_code');
		}

		$this->load->model('marketing/coupon');

		$coupon_info = $this->model_marketing_coupon->getCouponByCode($this->request->post['code']);

		if ($coupon_info) {
			if (!isset($this->request->post['coupon_id'])) {
				$json['error']['warning'] = $this->language->get('error_exists');
			} elseif ($coupon_info['coupon_id'] != (int)$this->request->post['coupon_id']) {
				$json['error']['warning'] = $this->language->get('error_exists');
			}
		}

		if (!$json) {
			if (!$this->request->post['coupon_id']) {
				$json['coupon_id'] = $this->model_marketing_coupon->addCoupon($this->request->post);
			} else {
				$this->model_marketing_coupon->editCoupon($this->request->post['coupon_id'], $this->request->post);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete(): void {
		$this->load->language('marketing/coupon');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'marketing/coupon')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('marketing/coupon');

			foreach ($selected as $coupon_id) {
				$this->model_marketing_coupon->deleteCoupon($coupon_id);
			}

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history(): void {
		$this->load->language('marketing/coupon');

		if (isset($this->request->get['coupon_id'])) {
			$coupon_id = (int)$this->request->get['coupon_id'];
		} else {
			$coupon_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$this->load->model('marketing/coupon');

		$data['histories'] = [];

		$results = $this->model_marketing_coupon->getHistories($coupon_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = [
				'order_id'   => $result['order_id'],
				'customer'   => $result['customer'],
				'amount'     => $result['amount'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$history_total = $this->model_marketing_coupon->getTotalHistories($coupon_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('marketing/coupon|history', 'user_token=' . $this->session->data['user_token'] . '&coupon_id=' . $coupon_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('marketing/coupon_history', $data));
	}
}
