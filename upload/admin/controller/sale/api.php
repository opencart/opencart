<?php
namespace Opencart\Admin\Controller\Sale;
class Api extends \Opencart\System\Engine\Controller {
	private object $store;

	public function __construct($registry) {
		parent::__construct($registry);
		/*
		 * 1. Create a store instance using loader class to call controllers, models, views.
		 */

		// Autoloader
		$autoloader = new \Opencart\System\Engine\Autoloader();
		$autoloader->register('Opencart\Catalog', DIR_CATALOG);
		$autoloader->register('Opencart\Extension', DIR_EXTENSION);
		$autoloader->register('Opencart\System', DIR_SYSTEM);

		// Registry
		$registry = new \Opencart\System\Engine\Registry();
		$registry->set('autoloader', $autoloader);

		// Config
		$config = new \Opencart\System\Engine\Config();
		$config->addPath(DIR_CONFIG);
		$registry->set('config', $config);

		// Load the default config
		$config->load('default');
		$config->load('catalog');
		$config->set('application', 'Catalog');

		// Logging
		$registry->set('log', $this->log);

		// Event
		$event = new \Opencart\System\Engine\Event($registry);
		$registry->set('event', $event);

		// Event Register
		if ($config->has('action_event')) {
			foreach ($config->get('action_event') as $key => $value) {
				foreach ($value as $priority => $action) {
					$event->register($key, new \Opencart\System\Engine\Action($action), $priority);
				}
			}
		}

		// Loader
		$loader = new \Opencart\System\Engine\Loader($registry);
		$registry->set('load', $loader);

		// Create a dummy request class so we can feed the data to the order editor
		$request = new \stdClass();
		$request->get = [];
		$request->post = [];
		$request->server = $this->request->server;
		$request->cookie = [];

		// Request
		$registry->set('request', $request);

		// Response
		$response = new \Opencart\System\Library\Response();
		$registry->set('response', $response);

		// Database
		$registry->set('db', $this->db);

		// Cache
		$registry->set('cache', $this->cache);


		// Create a dummy session class so we can feed the data to the order editor
		$session = new \stdClass();
		$session->data = [];
		$registry->set('session', $session);

		// To use the order API it requires an API ID.
		$session->data['api_id'] = (int)$this->config->get('config_api_id');

		// Template
		$template = new \Opencart\System\Library\Template($config->get('template_engine'));
		$template->addPath(DIR_CATALOG . 'view/template/');
		$registry->set('template', $template);

		// Language
		$language = new \Opencart\System\Library\Language($config->get('language_code'));
		$language->addPath(DIR_LANGUAGE);
		$language->load($config->get('language_code'));
		$registry->set('language', $language);

		// Store
		if (isset($this->request->post['store_id'])) {
			$config->set('config_store_id', $this->request->post['store_id']);
		} else {
			$config->set('config_store_id', 0);
		}

		// Url
		$registry->set('url', new \Opencart\System\Library\Url($config->get('site_url')));

		// Document
		$registry->set('document', new \Opencart\System\Library\Document());

		$pre_actions = [
			'startup/setting',
			'startup/extension',
			'startup/startup',
			'startup/event'
		];

		// Pre Actions
		foreach ($pre_actions as $pre_action) {
			$loader->controller($pre_action);
		}

		// Customer
		$customer = new \Opencart\System\Library\Cart\Customer($this->registry);
		$registry->set('customer', $customer);

		$this->store = $registry;
	}

	public function add(): void {
		// 1. We set some defaults so there are no undefined indexes.
		$defaults = [
			'store_id'              => 0,

			'customer_id'           => 0,
			'customer_group_id'     => (int)$this->config->get('config_customer_group_id'),
			'firstname'             => '',
			'lastname'              => '',
			'email'                 => '',
			'telephone'             => '',
			'custom_field'          => [],

			'payment_firstname'     => '',
			'payment_lastname'      => '',
			'payment_company'       => '',
			'payment_address_1'     => '',
			'payment_address_2'     => '',
			'payment_city'          => '',
			'payment_postcode'      => '',
			'payment_country_id'    => 0,
			'payment_zone_id'       => 0,
			'payment_custom_field'  => [],

			'payment_method'        => '',
			'payment_code'          => '',

			'shipping_firstname'    => '',
			'shipping_lastname'     => '',
			'shipping_company'      => '',
			'shipping_address_1'    => '',
			'shipping_address_2'    => '',
			'shipping_city'         => '',
			'shipping_postcode'     => '',
			'shipping_country_id'   => 0,
			'shipping_zone_id'      => 0,
			'shipping_custom_field' => [],

			'shipping_method'       => '',
			'shipping_code'         => '',

			'order_status_id'       => 0,
			'comment'               => '',

			'affiliate_id'          => 0,

			'currency_id'           => 0,
			'currency_code'         => (string)$this->config->get('config_currency'),
			'currency_value'        => (string)$this->config->get('config_currency'),

			'coupon'                => '',
			'voucher'               => '',
			'reward'                => '',
		];

		// 2. Merge the old order data with the new data
		foreach ($defaults as $key => $value) {
			if (isset($this->request->post[$key])) {
				$order[$key] = $this->request->post[$key];
			} elseif (isset($order_data[$key])) {
				$order[$key] = $order_info[$key];
			} else {
				$order[$key] = $value;
			}
		}

	}

	public function edit(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['store_id'])) {
			$store_id = (int)$this->request->get['store_id'];
		} else {
			$store_id = 0;
		}

		// Orders
		$this->load->model('sale/order');

		$order_info = $this->model_sale_order->getOrder($order_id);

		if ($order_id && !$order_info)  {
			$json['error']['warning'] = $this->langage->get('error_order');
		}

		// Store
		$this->load->model('setting/store');

		$store_info = $this->model_setting_store->getStore($store_id);

		if ($store_id && !$store_info) {
			$json['error']['warning'] = $this->langage->get('error_store');
		}

		$order = array();

		if (!$json) {

			// 1. Merge the old order data with the new data
			foreach ($order_info as $key => $value) {
				if (isset($this->request->post[$key])) {
					$order[$key] = $this->request->post[$key];
				} else {
					$order[$key] = $order_info[$key];
				}
			}

			$data['order_id'] = $order_id;

			// Payment method
			$data['payment_method'] = $order_info['payment_method'];
			$data['payment_code'] = $order_info['payment_code'];

			// Shipping method
			$data['shipping_method'] = $order_info['shipping_method'];
			$data['shipping_code'] = $order_info['shipping_code'];

			// Coupon, Voucher, Reward
			$data['coupon'] = '';
			$data['voucher'] = '';
			$data['reward'] = '';

			$order_totals = $this->model_sale_order->getTotals($order_id);

			foreach ($order_totals as $order_total) {
				// If coupon, voucher or reward points
				$start = strpos($order_total['title'], '(') + 1;
				$end = strrpos($order_total['title'], ')');

				if ($start && $end) {
					$data[$order_total['code']] = substr($order_total['title'], $start, $end - $start);
				}
			}

			// Vouchers
			$data['order_vouchers'] = $this->model_sale_order->getVouchers($order_id);

			// Reward Points
			$data['reward'] = $order_info['reward'];

			// Affiliate
			$data['affiliate_id'] = $order_info['affiliate_id'];

			// Addresses




			// Products
			$data['order_products'] = [];

			$products = $this->model_sale_order->getProducts($order_id);

			foreach ($products as $product) {
				$option_data = [];

				$options = $this->model_sale_order->getOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$option_data[] = [
							'name'  => $option['name'],
							'value' => $option['value'],
							'type'  => $option['type']
						];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$option_data[] = [
								'name'  => $option['name'],
								'value' => $upload_info['name'],
								'type'  => $option['type'],
								'href'  => $this->url->link('tool/upload|download', 'user_token=' . $this->session->data['user_token'] . '&code=' . $upload_info['code'])
							];
						}
					}
				}




				$data['order_products'][] = [
					'order_product_id' => $product['order_product_id'],
					'product_id'       => $product['product_id'],
					'name'             => $product['name'],
					'model'            => $product['model'],
					'option'           => $option_data,
					'quantity'         => $product['quantity'],
					'price'            => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'            => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reward'           => $product['reward'],
					'href'             => $this->url->link('catalog/product|edit', 'user_token=' . $this->session->data['user_token'] . '&product_id=' . $product['product_id'])
				];
			}

			// Vouchers
			$data['order_vouchers'] = [];

			$vouchers = $this->model_sale_order->getVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$data['order_vouchers'][] = [
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value']),
					'href'        => $this->url->link('sale/voucher|edit', 'user_token=' . $this->session->data['user_token'] . '&voucher_id=' . $voucher['voucher_id'])
				];
			}

			// Totals
			$data['order_totals'] = [];

			$totals = $this->model_sale_order->getTotals($order_id);

			foreach ($totals as $total) {
				$data['order_totals'][] = [
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'])
				];
			}

			// Order History
			$data['comment'] = $order_info['comment'];

			$data['order_status_id'] = $order_info['order_status_id'];

			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput($store->response->getOutput());
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}



	public function createInvoiceNo(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$invoice_no = $this->model_sale_order->createInvoiceNo($order_id);

			if ($invoice_no) {
				$json['invoice_no'] = $invoice_no;
			} else {
				$json['error'] = $this->language->get('error_action');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function customer(): void {
		$this->store->request->post = [
			'customer_id'       => $this->request->post['customer_id'],
			'customer_group_id' => $this->request->post['customer_group_id'],
			'firstname'         => $this->request->post['firstname'],
			'lastname'          => $this->request->post['lastname'],
			'email'             => $this->request->post['email'],
			'telephone'         => $this->request->post['telephone'],
			'custom_field'      => $this->request->post['custom_field']
		];

		$this->store->load->controller('api/customer');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function currency(): void {
		$this->store->request->post = ['currency' => $this->request->post['currency']];

		$this->store->load->controller('api/currency');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function language(): void {
		$this->store->request->post = ['language' => $this->request->post['language']];

		$this->store->load->controller('api/language');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function coupon(): void {
		$this->store->request->post = ['coupon' => $this->request->post['coupon']];

		$this->store->load->controller('api/coupon');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function voucher(): void {
		$this->store->request->post = ['voucher' => $this->request->post['voucher']];

		$this->store->load->controller('api/voucher');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function reward(): void {
		$this->store->request->post = ['reward' => $this->request->post['reward']];

		$this->store->load->controller('api/reward');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function setPaymentMethod(): void {
		$this->store->request->post = ['payment_method' => $this->request->post['payment_method']];

		$this->store->load->controller('api/payment_method');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function getPaymentMethods(): array {
		$this->store->load->controller('api/payment_method|getPaymentMethods');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function shippingmethod(): void {
		$this->store->request->post = ['shipping_method' => $this->request->post['shipping_method']];

		$this->store->load->controller('api/shipping_method');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function getShippingMethods(): array {
		$this->store->load->controller('api/shipping_method|getShippingMethods');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function addReward(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info && $order_info['customer_id'] && ($order_info['reward'] > 0)) {
				$this->load->model('customer/customer');

				$reward_total = $this->model_customer_customer->getTotalRewardsByOrderId($order_id);

				if (!$reward_total) {
					$this->model_customer_customer->addReward($order_info['customer_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['reward'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_reward_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeReward(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteReward($order_id);
			}

			$json['success'] = $this->language->get('text_reward_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function affiliate(): void {
		$json = [];

		if (isset($this->request->get['affiliate_id'])) {
			$affiliate_id = (int)$this->request->get['affiliate_id'];
		} else {
			$affiliate_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('marketing/affiliate');

			$affiliate_info = $this->model_marketing_affiliate->getAffiliate($affiliate_id);

			if ($affiliate_info) {
				$this->store->request->post = ['shipping_method' => $this->request->post['shipping_method']];

				$this->store->load->controller('api/affiliate');

				$this->response->addHeader('Content-Type: application/json');
				$this->response->setOutput($this->store->response->getOutput());


			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function addCommission(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$affiliate_total = $this->model_customer_customer->getTotalTransactionsByOrderId($order_id);

				if (!$affiliate_total) {
					$this->model_customer_customer->addTransaction($order_info['affiliate_id'], $this->language->get('text_order_id') . ' #' . $order_id, $order_info['commission'], $order_id);
				}
			}

			$json['success'] = $this->language->get('text_commission_added');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function removeCommission(): void {
		$this->load->language('sale/order');

		$json = [];

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->user->hasPermission('modify', 'sale/order')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('sale/order');

			$order_info = $this->model_sale_order->getOrder($order_id);

			if ($order_info) {
				$this->load->model('customer/customer');

				$this->model_customer_customer->deleteTransactionByOrderId($order_id);
			}

			$json['success'] = $this->language->get('text_commission_removed');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function paymentaddress(): void {
		print_r($this->request->post);

		$this->store->request->post = [
			'firstname'    => $this->request->post['firstname'],
			'lastname'     => $this->request->post['lastname'],
			'company'      => $this->request->post['company'],
			'address_1'    => $this->request->post['address_1'],
			'address_2'    => $this->request->post['address_2'],
			'postcode'     => $this->request->post['postcode'],
			'city'         => $this->request->post['city'],
			'zone_id'      => $this->request->post['zone_id'],
			'country_id'   => $this->request->post['country_id'],
			'custom_field' => $this->request->post['custom_field']
		];

		$this->store->load->controller('api/payment_address');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function shippingaddress(): void {
		$this->store->request->post = [
			'firstname'    => $this->request->post['firstname'],
			'lastname'     => $this->request->post['lastname'],
			'company'      => $this->request->post['company'],
			'address_1'    => $this->request->post['address_1'],
			'address_2'    => $this->request->post['address_2'],
			'postcode'     => $this->request->post['postcode'],
			'city'         => $this->request->post['city'],
			'zone_id'      => $this->request->post['zone_id'],
			'country_id'   => $this->request->post['country_id'],
			'custom_field' => $this->request->post['custom_field']
		];

		$this->store->load->controller('api/shipping_address');

		$this->response->addHeader('Content-Type: application/json');
		$this->setOutput($this->store->response->getOutput());
	}

	public function addProduct(): void {
		$this->store->request->post = [
			'product_id' => $this->request->post['product_id'],
			'option'     => $this->request->post['option'],
			'quantity'   => $this->request->post['quantity']
		];

		$this->store->load->controller('api/cart/add');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function removeProduct(): void {
		$this->store->request->post = ['key' => $this->request->post['cart_id']];

		$this->store->load->controller('api/cart/remove');

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($this->store->response->getOutput());
	}

	public function history(): void {
		$this->load->language('sale/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = (int)$this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = [];

		$this->load->model('sale/order');

		$results = $this->model_sale_order->getHistories($order_id, ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['histories'][] = [
				'notify'     => $result['notify'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'     => $result['status'],
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			];
		}

		$history_total = $this->model_sale_order->getTotalHistories($order_id);

		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $history_total,
			'page'  => $page,
			'limit' => 10,
			'url'   => $this->url->link('sale/order|history', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $order_id . '&page={page}')
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('sale/order_history', $data));
	}
}
