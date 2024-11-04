<?php
namespace Opencart\Admin\Controller\Sale;
/**
 * Class Subscription
 *
 * @package Opencart\Admin\Controller\Sale
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('sale/subscription');

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

		$data['add'] = $this->url->link('sale/subscription.info', 'user_token=' . $this->session->data['user_token'] . $url);
		$data['delete'] = $this->url->link('sale/subscription.delete', 'user_token=' . $this->session->data['user_token']);

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

	/**
	 * List
	 *
	 * @return void
	 */
	public function list(): void {
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getList());
	}

	/**
	 * Get List
	 *
	 * @return string
	 */
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
			$sort = (string)$this->request->get['sort'];
		} else {
			$sort = 's.subscription_id';
		}

		if (isset($this->request->get['order'])) {
			$order = (string)$this->request->get['order'];
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

		$data['action'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . $url);

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

		$results = $this->model_sale_subscription->getSubscriptions($filter_data);

		foreach ($results as $result) {
			$data['subscriptions'][] = [
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('sale/subscription.info', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $result['subscription_id'] . $url),
				'order'      => $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'])
			] + $result;
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

		$data['sort_subscription'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.subscription_id' . $url);
		$data['sort_order'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.order_id' . $url);
		$data['sort_customer'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . '&sort=customer' . $url);
		$data['sort_status'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . '&sort=subscription_status' . $url);
		$data['sort_date_added'] = $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . '&sort=s.date_added' . $url);

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

		$subscription_total = $this->model_sale_subscription->getTotalSubscriptions($filter_data);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => $this->config->get('config_pagination_admin'),
			'url'   => $this->url->link('sale/subscription.list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $this->config->get('config_pagination_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_pagination_admin')) > ($subscription_total - $this->config->get('config_pagination_admin'))) ? $subscription_total : ((($page - 1) * $this->config->get('config_pagination_admin')) + $this->config->get('config_pagination_admin')), $subscription_total, ceil($subscription_total / $this->config->get('config_pagination_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		return $this->load->view('sale/subscription_list', $data);
	}

	/**
	 * Info
	 *
	 * @return void
	 */
	public function info(): void {
		$this->load->language('sale/subscription');

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$data['text_form'] = !$subscription_id ? $this->language->get('text_add') : sprintf($this->language->get('text_edit'), $subscription_id);

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
		$data['customer_add'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token']);

		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if (!empty($subscription_info)) {
			$data['subscription_id'] = $subscription_info['subscription_id'];
		} else {
			$data['subscription_id'] = '';
		}

		if (!empty($subscription_info)) {
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($subscription_info['date_added']));
		} else {
			$data['date_added'] = '';
		}

		// Order
		$this->load->model('sale/order');

		if (!empty($subscription_info)) {
			$order_info = $this->model_sale_order->getOrder($subscription_info['order_id']);
		}

		if (!empty($subscription_info)) {
			$data['order_id'] = $subscription_info['order_id'];
		} else {
			$data['order_id'] = 0;
		}

		if (!empty($order_info)) {
			$data['order_edit'] = $this->url->link('sale/order.info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_info['order_id']);
		} else {
			$data['order_edit'] = '';
		}

		// Customer
		$this->load->model('customer/customer');

		if (!empty($subscription_info)) {
			$customer_info = $this->model_customer_customer->getCustomer($subscription_info['customer_id']);
		}

		if (!empty($customer_info)) {
			$data['customer_id'] = $customer_info['customer_id'];
		} else {
			$data['customer_id'] = '';
		}

		if (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (!empty($customer_info)) {
			$data['customer_group_id'] = $customer_info['customer_group_id'];
		} else {
			$data['customer_group_id'] = $this->config->get('config_customer_group_id');
		}

		if (!empty($customer_info)) {
			$data['addresses'] = $this->model_customer_customer->getAddresses($customer_info['customer_id']);
		} else {
			$data['addresses'] = [];
		}

		if (!empty($customer_info)) {
			$data['customer_edit'] = $this->url->link('customer/customer.form', 'user_token=' . $this->session->data['user_token'] . '&customer_id=' . $customer_info['customer_id']);
		} else {
			$data['customer_edit'] = '';
		}

		// Store
		$data['stores'] = [];

		$data['stores'][] = [
			'store_id' => 0,
			'name'     => $this->config->get('config_name')
		];

		$this->load->model('setting/store');

		$data['stores'] = $data['stores'] + $this->model_setting_store->getStores();

		if (!empty($subscription_info)) {
			$data['store_id'] = $subscription_info['store_id'];
		} else {
			$data['store_id'] = (int)$this->config->get('config_store_id');
		}

		// Language
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (!empty($subscription_info)) {
			$data['language_id'] = $subscription_info['language_id'];
		} else {
			$data['language_id'] = $this->config->get('config_language_id');
		}

		// Currency
		$this->load->model('localisation/currency');

		$data['currencies'] = $this->model_localisation_currency->getCurrencies();

		if (!empty($subscription_info)) {
			$data['currency_id'] = $subscription_info['currency_id'];
		} else {
			$data['currency_id'] = 0;
		}

		$currency_info = $this->model_localisation_currency->getCurrency($data['currency_id']);

		if ($currency_info) {
			$currency = $currency_info['code'];
		} else {
			$currency = $this->config->get('config_currency');
		}

		// Products
		$data['subscription_products'] = [];

		$this->load->model('catalog/product');
		$this->load->model('tool/upload');

		$results = $this->model_sale_subscription->getProducts($subscription_id);

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {
				$option_data = [];

				foreach ($result['option'] as $product_option_id => $value) {
					$option_info = $this->model_catalog_product->getOption($product_info['product_id'], $product_option_id);

					if ($option_info) {
						if ($option_info['type'] != 'file') {
							$data['options'][] = ['value' => $value] + $option_info;
						} else {
							$upload_info = $this->model_tool_upload->getUploadByCode($value);

							if ($upload_info) {
								$data['options'][] = [
									'value' => $value,
									'href'  => $this->url->link('tool/upload.download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'])
								] + $option_info;
							}
						}
					}
				}

				$data['subscription_products'][] = [
					'option'           => $option_data,
					'price_text'       => $this->currency->format($result['price'], $currency),
					'trial_price_text' => $this->currency->format($result['trial_price'], $currency),
					'product_edit'     => $this->url->link('catalog/product.form', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $result['product_id'])
				] + $result;
			}
		}

		// Subscription
		$data['subscription_plans'] = [];

		if (!empty($subscription_info)) {
			$data['subscription_plan_id'] = $subscription_info['subscription_plan_id'];
		} else {
			$data['subscription_plan_id'] = 0;
		}

		$this->load->model('catalog/subscription_plan');

		$subscriptions = $this->model_sale_subscription->getProducts($subscription_id);

		foreach ($subscriptions as $subscription) {
			$subscription_plan_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($data['subscription_plan_id']);

			if ($subscription_plan_info) {
				$description = '';

				if ($subscription_plan_info['trial_status']) {
					$trial_price = $this->currency->format($subscription['trial_price'], $this->config->get('config_currency'));
					$trial_cycle = $subscription_plan_info['trial_cycle'];
					$trial_frequency = $this->language->get('text_' . $subscription_plan_info['trial_frequency']);
					$trial_duration = $subscription_plan_info['trial_duration'];

					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($subscription['price'], $this->config->get('config_currency'));
				$cycle = $subscription_plan_info['cycle'];
				$frequency = $this->language->get('text_' . $subscription_plan_info['frequency']);
				$duration = $subscription_plan_info['duration'];

				if ($subscription_plan_info['duration']) {
					$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}

				$data['subscription_plans'][] = [
					'name'        => $subscription_plan_info['name'],
					'description' => $description
				] + $subscription;
			}
		}

		// Date next
		if (!empty($subscription_info)) {
			$data['date_next'] = date($this->language->get('date_format_short'), strtotime($subscription_info['date_next']));
		} else {
			$data['date_next'] = '';
		}

		if (!empty($subscription_info)) {
			$data['remaining'] = $subscription_info['remaining'];
		} else {
			$data['remaining'] = 0;
		}

		// Payment Address
		if (!empty($subscription_info)) {
			$address_info  = $this->model_customer_customer->getAddress($subscription_info['payment_address_id']);
		} else {
			$address_info = [];
		}

		if ($address_info) {
			$data['payment_address_id'] = $address_info['address_id'];
			$data['payment_firstname'] = $address_info['firstname'];
			$data['payment_lastname'] = $address_info['lastname'];
			$data['payment_company'] = $address_info['company'];
			$data['payment_address_1'] = $address_info['address_1'];
			$data['payment_address_2'] = $address_info['address_2'];
			$data['payment_city'] = $address_info['city'];
			$data['payment_postcode'] = $address_info['postcode'];
			$data['payment_country_id'] = $address_info['country_id'];
			$data['payment_country'] = $address_info['country'];
			$data['payment_zone_id'] = $address_info['zone_id'];
			$data['payment_zone'] = $address_info['zone'];
			$data['payment_custom_field'] = $address_info['custom_field'];
		} else {
			$data['payment_address_id'] = 0;
			$data['payment_firstname'] = '';
			$data['payment_lastname'] = '';
			$data['payment_company'] = '';
			$data['payment_address_1'] = '';
			$data['payment_address_2'] = '';
			$data['payment_city'] = '';
			$data['payment_postcode'] = '';
			$data['payment_country_id'] = 0;
			$data['payment_country'] = '';
			$data['payment_zone_id'] = 0;
			$data['payment_zone'] = '';
			$data['payment_custom_field'] = [];
		}

		// Shipping Address
		if (!empty($subscription_info)) {
			$address_info  = $this->model_customer_customer->getAddress($subscription_info['shipping_address_id']);
		} else {
			$address_info = [];
		}

		if ($address_info) {
			$data['shipping_address_id'] = $address_info['address_id'];
			$data['shipping_firstname'] = $address_info['firstname'];
			$data['shipping_lastname'] = $address_info['lastname'];
			$data['shipping_company'] = $address_info['company'];
			$data['shipping_address_1'] = $address_info['address_1'];
			$data['shipping_address_2'] = $address_info['address_2'];
			$data['shipping_city'] = $address_info['city'];
			$data['shipping_postcode'] = $address_info['postcode'];
			$data['shipping_country_id'] = $address_info['country_id'];
			$data['shipping_country'] = $address_info['country'];
			$data['shipping_zone_id'] = $address_info['zone_id'];
			$data['shipping_zone'] = $address_info['zone'];
			$data['shipping_custom_field'] = $address_info['custom_field'];
		} else {
			$data['shipping_address_id'] = 0;
			$data['shipping_firstname'] = '';
			$data['shipping_lastname'] = '';
			$data['shipping_company'] = '';
			$data['shipping_address_1'] = '';
			$data['shipping_address_2'] = '';
			$data['shipping_city'] = '';
			$data['shipping_postcode'] = '';
			$data['shipping_country_id'] = 0;
			$data['shipping_country'] = '';
			$data['shipping_zone_id'] = 0;
			$data['shipping_zone'] = '';
			$data['shipping_custom_field'] = [];
		}

		// Payment Method
		if (!empty($order_info['payment_method'])) {
			$data['payment_method_name'] = $order_info['payment_method']['name'];
			$data['payment_method_code'] = $order_info['payment_method']['code'];
		} else {
			$data['payment_method_name'] = '';
			$data['payment_method_code'] = '';
		}

		// Shipping method
		if (!empty($order_info['shipping_method'])) {
			$data['shipping_method_name'] = $order_info['shipping_method']['name'];
			$data['shipping_method_code'] = $order_info['shipping_method']['code'];
			$data['shipping_method_cost'] = $order_info['shipping_method']['cost'];
			$data['shipping_method_tax_class_id'] = $order_info['shipping_method']['tax_class_id'];
		} else {
			$data['shipping_method_name'] = '';
			$data['shipping_method_code'] = '';
			$data['shipping_method_cost'] = 0.00;
			$data['shipping_method_tax_class_id'] = 0;
		}

		// Countries
		$this->load->model('localisation/country');

		$data['countries'] = $this->model_localisation_country->getCountries();

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$data['subscription_statuses'] = $this->model_localisation_subscription_status->getSubscriptionStatuses();

		if (!empty($subscription_info)) {
			$data['subscription_status_id'] = $subscription_info['subscription_status_id'];
		} else {
			$data['subscription_status_id'] = '';
		}

		$data['history'] = $this->getHistory();
		$data['orders'] = $this->getOrder();

		// Additional tabs that are payment gateway specific
		$data['tabs'] = [];

		// Extension Order Tabs can be called here.
		/*
		$this->load->model('setting/extension');

		if (!empty($order_info)) {
			$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $order_info['payment_method']['code']);

			if ($extension_info && $this->user->hasPermission('access', 'extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code'])) {
				$output = $this->load->controller('extension/payment/' . $order_info['payment_code'] . '.subscription');

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
		*/
		$data['user_token'] = $this->session->data['user_token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('sale/subscription_info', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('sale/subscription');

		$json = [];

		if (!$this->user->hasPermission('modify', 'sale/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		// Customer
		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($this->request->post['customer_id']);

		if (!$customer_info) {
			$json['error']['customer'] = $this->language->get('error_customer');
		}



		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($this->request->post['store_id']);

		if (!$store_info) {
			$json['error']['store'] = $this->language->get('error_store');
		}



		// Product
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);

		if (!$product_info) {
			$json['error']['product'] = $this->language->get('error_product');
		}

		// Subscription Plan
		$this->load->model('catalog/subscription_plan');

		$subscription_plan_info = $this->model_catalog_subscription_plan->getSubscriptionPlan($this->request->post['subscription_plan_id']);

		if (!$subscription_plan_info) {
			$json['error']['subscription_plan'] = $this->language->get('error_subscription_plan');
		}

		// Payment Address
		$address_info = $this->model_customer_customer->getAddress($this->request->post['payment_address_id']);

		if ($this->config->get('config_checkout_payment_address') && !$address_info) {
			$json['error']['payment_address'] = $this->language->get('error_payment_address');
		}

		// Shipping Address
		$address_info = $this->model_customer_customer->getAddress($this->request->post['shipping_address_id']);

		if (!$address_info) {
			$json['error']['shipping_address'] = $this->language->get('error_shipping_address');
		}


		// 5. Validate payment Method
		if (empty($this->request->post['payment_method_name'])) {
			$json['error'] = $this->language->get('error_name');
		}

		if (empty($this->request->post['payment_method_code'])) {
			$json['error'] = $this->language->get('error_code');
		}

		/*
		$this->session->data['payment_method'] = [
			'name' => $this->request->post['payment_method_name'],
			'code' => $this->request->post['payment_method_code']
		];
		*/




		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if ($subscription_info) {
			$filter_data = [
				'filter_customer_id'         => $subscription_info['customer_id'],
				'filter_customer_payment_id' => $this->request->post['customer_payment_id']
			];

			$payment_method_info = $this->model_sale_subscription->getSubscriptions($filter_data);

			if (!$payment_method_info) {
				$json['error'] = $this->language->get('error_payment_method');
			}
		} else {
			$json['error'] = $this->language->get('error_subscription');
		}

		if (!$json) {
			$this->model_sale_subscription->editSubscription($subscription_id, $this->request->post['subscription_plan_id']);

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
		$this->load->language('sale/subscription');

		$json = [];

		if (isset($this->request->post['selected'])) {
			$selected = $this->request->post['selected'];
		} else {
			$selected = [];
		}

		if (!$this->user->hasPermission('modify', 'sale/subscription')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('sale/subscription');

			foreach ($selected as $subscription_id) {
				$this->model_sale_subscription->deleteSubscription($subscription_id);
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
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	public function getHistory(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'sale/subscription.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['histories'] = [];

		$this->load->model('sale/subscription');

		$results = $this->model_sale_subscription->getHistories($subscription_id, ($page - 1) * $limit, $limit);

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
			'limit' => $limit,
			'url'   => $this->url->link('sale/subscription.history', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $subscription_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($subscription_total - $limit)) ? $subscription_total : ((($page - 1) * $limit) + $limit), $subscription_total, ceil($subscription_total / $limit));

		return $this->load->view('sale/subscription_history', $data);
	}

	/**
	 * Add History
	 *
	 * @return void
	 */
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

		// Subscription
		$this->load->model('sale/subscription');

		$subscription_info = $this->model_sale_subscription->getSubscription($subscription_id);

		if (!$subscription_info) {
			$json['error'] = $this->language->get('error_subscription');
		}

		// Subscription Plan
		$this->load->model('localisation/subscription_status');

		$subscription_status_info = $this->model_localisation_subscription_status->getSubscriptionStatus($this->request->post['subscription_status_id']);

		if (!$subscription_status_info) {
			$json['error'] = $this->language->get('error_subscription_status');
		}

		if (!$json) {
			$this->model_sale_subscription->addHistory($subscription_id, $this->request->post['subscription_status_id'], $this->request->post['comment'], $this->request->post['notify']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Order
	 *
	 * @return void
	 */
	public function order(): void {
		$this->load->language('sale/subscription');

		$this->response->setOutput($this->getOrder());
	}

	/**
	 * Get Order
	 *
	 * @return string
	 */
	public function getOrder(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'sale/subscription.order') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		$data['orders'] = [];

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getOrdersBySubscriptionId($subscription_id);

		foreach ($results as $result) {
			$data['orders'][] = [
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('sale/subscription.order', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $result['order_id'] . '&page={page}')
			] + $result;
		}

		$order_total = $this->model_sale_order->getTotalOrdersBySubscriptionId($subscription_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $order_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('sale/subscription.order', 'user_token=' . $this->session->data['user_token'] . '&subscription_id=' . $subscription_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($order_total - $limit)) ? $order_total : ((($page - 1) * $limit) + $limit), $order_total, ceil($order_total / $limit));

		return $this->load->view('sale/subscription_order', $data);
	}
}
