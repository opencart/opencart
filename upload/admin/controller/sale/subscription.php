<?php
namespace Opencart\Admin\Controller\Sale;
class Subscription extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('sale/subscription');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_subscription_id'])) {
			$filter_subscription_id = (int)$this->request->get['filter_subscription_id'];
		} else {
			$filter_subscription_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$filter_subscription_status_id = (int)$this->request->get['filter_subscription_status_id'];
		} else {
			$filter_subscription_status_id = '';
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

		$url = '';

		if (isset($this->request->get['filter_subscription_id'])) {
			$url .= '&filter_subscription_id=' . $this->request->get['filter_subscription_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$url .= '&filter_subscription_status_id=' . $this->request->get['filter_subscription_status_id'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

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
			'href' => $this->url->link('sale/subscription', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['add'] = $this->url->link('sale/subscription|form', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/subscription|delete', 'user_token=' . $this->session->data['user_token']);

		$data['list'] = $this->getList();

		$this->load->model('localisation/subscription_status');

		$data['subscription_statuses'] = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		$data['filter_subscription_id'] = $filter_subscription_id;
		$data['filter_order_id'] = $filter_order_id;
		$data['filter_customer'] = $filter_customer;
		$data['filter_subscription_status_id'] = $filter_subscription_status_id;
		$data['filter_date_from'] = $filter_date_from;
		$data['filter_date_to'] = $filter_date_to;

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/subscription', $data));
	}

	public function list(): void {
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getList());
	}

	protected function getList(): string {
		if (isset($this->request->get['filter_subscription_id'])) {
			$filter_subscription_id = (int)$this->request->get['filter_subscription_id'];
		} else {
			$filter_subscription_id = '';
		}

		if (isset($this->request->get['filter_order_id'])) {
			$filter_order_id = $this->request->get['filter_order_id'];
		} else {
			$filter_order_id = '';
		}

		if (isset($this->request->get['filter_customer'])) {
			$filter_customer = $this->request->get['filter_customer'];
		} else {
			$filter_customer = '';
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$filter_subscription_status_id = (int)$this->request->get['filter_subscription_status_id'];
		} else {
			$filter_subscription_status_id = '';
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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 's.subscription_id';
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

		if (isset($this->request->get['filter_subscription_id'])) {
			$url .= '&filter_subscription_id=' . $this->request->get['filter_subscription_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$url .= '&filter_subscription_status_id=' . $this->request->get['filter_subscription_status_id'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['subscriptions'] = [];

		$filter_data = [
			'filter_subscription_id'        => $filter_subscription_id,
			'filter_order_id'               => $filter_order_id,
			'filter_customer'               => $filter_customer,
			'filter_subscription_status_id' => $filter_subscription_status_id,
			'filter_date_from'              => $filter_date_from,
			'filter_date_to'                => $filter_date_to,
			'order'                         => $order,
			'sort'                          => $sort,
			'start'                         => ($page - 1) * $this->config->get('config_pagination_admin'),
			'limit'                         => $this->config->get('config_pagination_admin')
		];

		$this->load->model('sale/subscription');

		$subscription_total = $this->model_sale_subscription->getTotalSubscriptions($filter_data);

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			$data['subscriptions'][] = [
				'subscription_id' => $result['subscription_id'],
				'order_id'        => $result['order_id'],
				'customer'        => $result['customer'],
				'status'          => $result['subscription_status'],
				'date_added'      => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'            => $this->url->link('sale/subscription|info', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $result['subscription_id'] . $url),
				'order'           => $this->url->link('sale/order|info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'])
			];
		}

		$url = '';

		if (isset($this->request->get['filter_subscription_id'])) {
			$url .= '&filter_subscription_id=' . $this->request->get['filter_subscription_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$url .= '&filter_subscription_status_id=' . $this->request->get['filter_subscription_status_id'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_subscription'] = $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.subscription_id' . $url);
		$data['sort_order'] = $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.order_id' . $url);
		$data['sort_customer'] = $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_status'] = $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . '&sort=subscription_status' . $url);
		$data['sort_date_added'] = $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.date_added' . $url);

		$url = '';

		if (isset($this->request->get['filter_subscription_id'])) {
			$url .= '&filter_subscription_id=' . $this->request->get['filter_subscription_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/subscription|list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($subscription_total - $this->config->get('config_pagination_admin'))) ? $subscription_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $subscription_total, ceil($subscription_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/subscription_list', $data);
	}

	public function info(): void {
		$this->load->language('sale/subscription');
		
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['filter_subscription_id'])) {
			$url .= '&filter_subscription_id=' . $this->request->get['filter_subscription_id'];
		}

		if (isset($this->request->get['filter_order_id'])) {
			$url .= '&filter_order_id=' . $this->request->get['filter_order_id'];
		}

		if (isset($this->request->get['filter_customer'])) {
			$url .= '&filter_customer=' . urlencode(html_entity_decode($this->request->get['filter_customer'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_subscription_status_id'])) {
			$url .= '&filter_subscription_status_id=' . $this->request->get['filter_subscription_status_id'];
		}

		if (isset($this->request->get['filter_date_from'])) {
			$url .= '&filter_date_from=' . $this->request->get['filter_date_from'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

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
			'href' => $this->url->link('sale/subscription', 'user_token=' . $this->session->data['user_token'] . $url)
		];

		$data['back'] = $this->url->link('sale/subscription', 'user_token=' . $this->session->data['user_token'] . $url);

		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if (!empty($subscription_info)) {
			$data['subscription_id'] = $subscription_info['subscription_id'];
		} else {
			$data['subscription_id'] = '';
		}

		// Order data
		if (!empty($subscription_info)) {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($subscription_info['order_id']);
		}

		if (!empty($order_info)) {
			$data['order_id'] = $order_info['order_id'];
		} else {
			$data['order_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['customer'] = $order_info['customer'];
		} else {
			$data['customer'] = '';
		}

		if (!empty($order_info)) {
			$data['order_status'] = $order_info['order_status'];
		} else {
			$data['order_status'] = '';
		}

		$this->load->model('catalog/subscription_plan');

		$data['subscription_plans'] = $this->model_catalog_subscription_plan->getSubscriptionPlans();

		if (!empty($subscription_info)) {
			$data['subscription_plan_id'] = $subscription_info['subscription_plan_id'];
		} else {
			$data['subscription_plan_id'] = 0;
		}

		$this->load->model('customer/customer');

		$data['payment_methods'] = $this->model_customer_customer->getPaymentMethods($order_info['customer_id']);

		if (!empty($subscription_info)) {
			$data['customer_payment_id'] = $subscription_info['customer_payment_id'];
		} else {
			$data['customer_payment_id'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['remaining'] = $subscription_info['remaining'];
		} else {
			$data['remaining'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['duration'] = $subscription_info['duration'];
		} else {
			$data['duration'] = 0;
		}

		if (!empty($subscription_info)) {
			$data['date_next'] = date($this->language->get('date_format_short'), strtotime($subscription_info['date_next']));
		} else {
			$data['date_next'] = '';
		}

		if (!empty($order_info)) {
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
		} else {
			$data['date_added'] = '';
		}

		// Product data
		if (!empty($subscription_info)) {
			$this->load->model('sale/order');

			$product_info = $this->model_sale_order->getProductByOrderProductId($subscription_info['order_id'], $subscription_info['order_product_id']);
		}

		if (!empty($product_info['name'])) {
			$data['product_name'] = $product_info['name'];
		} else {
			$data['product_name'] = '';
		}

		if (!empty($product_info)) {
			$data['product'] = $this->url->link('catalog/product|form', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product_info['product_id']);
		} else {
			$data['product'] = '';
		}

		$data['description'] = '';

		if (!empty($subscription_info)) {
			$trial_price = $this->currency->format($subscription_info['trial_price'], $this->config->get('config_currency'));
			$trial_cycle = $subscription_info['trial_cycle'];
			$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
			$trial_duration = $subscription_info['trial_duration'];

			if ($subscription_info['trial_status']) {
				$data['description'] .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
			}

			$price = $this->currency->format($subscription_info['price'], $this->config->get('config_currency'));
			$cycle = $subscription_info['cycle'];
			$frequency = $this->language->get('text_' . $subscription_info['frequency']);
			$duration = $subscription_info['duration'];

			if ($subscription_info['duration']) {
				$data['description'] .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
			} else {
				$data['description'] .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
			}
		}

		if (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = '';
		}

		$this->load->model('localisation/subscription_status');

		$data['subscription_statuses'] = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		if (!empty($subscription_info)) {
			$data['subscription_status_id'] = $subscription_info['subscription_status_id'];
		} else {
			$data['subscription_status_id'] = '';
		}

		$data['history'] = $this->getHistory();
		$data['transaction'] = $this->getTransaction();

		// Additional tabs that are payment gateway specific
		$data['tabs'] = [];

		// Extension Order Tabs can are called here.
		$this->load->model('setting/extension');

		if (!empty($order_info)) {
			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $order_info['payment_code']);

			if ($extension_info && $this->user->hasPermission('access', 'extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'])) {
				$output = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '|subscription');

				if (!$output instanceof \Exception) {
					$this->load->language('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'], 'extension');

					$data['tabs'][] = [
						'code'    => $extension_info['code'],
						'title'   => $this->language->get('extension_heading_title'),
						'content' => $output
					];
				}
			}
		}

		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/subscription_info', $data));
	}

	public function save(): void {
		$this->load->language('sale/subscription');

		$json = [];

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('catalog/subscription_plan');

		$subscription_plan_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($this->request->post['subscription_plan_id']);

		if (!$subscription_plan_info) {
			$json['error'] = $this->language->get('error_subscription_plan');
		}

		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if (!$subscription_info) {
			$this->load->model('customer/customer');

			$payment_method_info = $this->model_customer_customer->getPaymentMethod($subscription_info['customer_id'], $this->request->post['customer_payment_id']);

			if (!$payment_method_info) {
				$json['error'] = $this->language->get('error_payment_method');
			}
		} else {
			$json['error'] = $this->language->get('error_subscription');
		}

		if (!$json) {
			$this->load->model('sale/subscription');

			$this->model_sale_subscription->editSubscriptionPlan($subscription_id, $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history(): void {
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getHistory());
	}

	public function getHistory(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = [];

		$this->load->model('sale/subscription');

		$results = $this->model_sale_subscription->getHistories($subscription_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = [
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$subscription_total = $this->model_sale_subscription->getTotalHistories($subscription_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('sale/subscription|history', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $subscription_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($subscription_total - 10)) ? $subscription_total : ((($page - 1) * 10) + 10), $subscription_total, ceil($subscription_total / 10));

		return $this->load->view('sale/subscription_history', $data);
	}

	public function addHistory(): void {
		$this->load->language('sale/subscription');

		$json = [];

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('sale/subscription');

			$this->model_sale_subscription->addHistory($subscription_id, $this->request->post['subscription_status_id'], $this->request->post['comment'], $this->request->post['notify']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function transaction(): void {
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getTransaction());
	}

	public function getTransaction(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['transactions'] = [];

		$this->load->model('sale/subscription');

		$results = $this->model_sale_subscription->getTransactions($subscription_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['transactions'][] = [
				'amount'      => $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'description' => nl2br($result['description']),
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		//$data['balance'] = $this->currency->format($this->model_sale_subscription->getTransactionTotal($subscription_id), $this->config->get('config_currency'));

		$transaction_total = $this->model_sale_subscription->getTotalTransactions($subscription_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $transaction_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('sale/subscription|transaction|transaction', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $subscription_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_total - 10)) ? $transaction_total : ((($page - 1) * 10) + 10), $transaction_total, ceil($transaction_total / 10));

		return $this->load->view('sale/subscription_transaction', $data);
	}

	public function addTransaction(): void {
		$this->load->language('sale/subscription');

		$json = [];

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if (!$subscription_info) {
			$json['error'] = $this->language->get('error_subscription');
		}

		if (!$json) {
			$this->load->model('sale/subscription');

			$this->model_sale_subscription->addTransaction($subscription_id, (string)$this->request->post['description'], (float)$this->request->post['amount']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
