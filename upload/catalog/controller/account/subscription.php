<?php
namespace Opencart\Catalog\Controller\Account;
/**
 * Class Subscription
 *
 * @package Opencart\Catalog\Controller\Account
 */
class Subscription extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('account/subscription');

		if (isset($this->request->get['page'])) {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->document->setTitle($this->language->get('heading_title'));

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/subscription', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . $url)
		];

		$limit = 10;

		$data['subscriptions'] = [];

		// Subscriptions
		$this->load->model('account/subscription');

		// Currency
		$this->load->model('localisation/currency');

		// Subscription Status
		$this->load->model('localisation/subscription_status');

		$results = $this->model_account_subscription->getSubscriptions(($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$description = '';

			if ($result['trial_status']) {
				$trial_price = $result['trial_price'];
				$trial_cycle = $result['trial_cycle'];
				$trial_frequency = $this->language->get('text_' . $result['trial_frequency']);
				$trial_duration = $result['trial_duration'];

				$description .= sprintf($this->language->get('text_subscription_trial'), $result['currency_code'], $trial_price, $result['currency_value'], $trial_cycle, $trial_frequency, $trial_duration);
			}

			$price = $result['price'];
			$cycle = $result['cycle'];
			$frequency = $this->language->get('text_' . $result['frequency']);
			$duration = $result['duration'];

			if ($duration) {
				$description .= sprintf($this->language->get('text_subscription_duration'), $result['currency'], $price, $result['currency_value'], $cycle, $frequency, $duration);
			} else {
				$description .= sprintf($this->language->get('text_subscription_cancel'), $result['currency'], $price, $result['currency_value'], $cycle, $frequency);
			}

			$subscription_status_info = $this->model_localisation_subscription_status->getSubscriptionStatus($result['subscription_status_id']);

			if ($subscription_status_info) {
				$subscription_status = $subscription_status_info['name'];
			} else {
				$subscription_status = '';
			}

			$data['subscriptions'][] = [
				'product_total' => $this->model_account_subscription->getTotalProducts($result['subscription_id']),
				'description'   => $description,
				'status'        => $subscription_status,
				'date_added'    => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'          => $this->url->link('account/subscription.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&subscription_id=' . $result['subscription_id'])
			] + $result;
		}

		// Total Subscriptions
		$subscription_total = $this->model_account_subscription->getTotalSubscriptions();

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/subscription', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($subscription_total - $limit)) ? $subscription_total : ((($page - 1) * $limit) + $limit), $subscription_total, ceil($subscription_total / $limit));

		$data['continue'] = $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/subscription_list', $data));
	}

	/**
	 * Info
	 *
	 * @return \Opencart\System\Engine\Action|null
	 */
	public function info() {
		$this->load->language('account/subscription');

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		// Subcription
		$this->load->model('account/subscription');

		$subscription_info = $this->model_account_subscription->getSubscription($subscription_id);

		if ($subscription_info) {
			$heading_title = sprintf($this->language->get('text_subscription'), $subscription_info['subscription_id']);

			$this->document->setTitle($heading_title);

			$this->document->addScript('catalog/view/javascript/subscription.js');

			$data['heading_title'] = $heading_title;

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'] = [];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'])
			];

			$data['breadcrumbs'][] = [
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/subscription', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . $url)
			];

			$data['breadcrumbs'][] = [
				'text' => $heading_title,
				'href' => $this->url->link('account/subscription.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&subscription_id=' . $this->request->get['subscription_id'] . $url)
			];

			$data['subscription_id'] = $subscription_info['subscription_id'];
			$data['order_id'] = $subscription_info['order_id'];

			// Payment Address
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getId(), $subscription_info['payment_address_id']);

			if ($address_info) {
				if ($address_info['address_format']) {
					$format = $address_info['address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				];

				$replace = [
					'firstname' => $address_info['firstname'],
					'lastname'  => $address_info['lastname'],
					'company'   => $address_info['company'],
					'address_1' => $address_info['address_1'],
					'address_2' => $address_info['address_2'],
					'city'      => $address_info['city'],
					'postcode'  => $address_info['postcode'],
					'zone'      => $address_info['zone'],
					'zone_code' => $address_info['zone_code'],
					'country'   => $address_info['country']
				];

				$pattern_1 = [
					"\r\n",
					"\r",
					"\n"
				];

				$pattern_2 = [
					"/\\s\\s+/",
					"/\r\r+/",
					"/\n\n+/"
				];

				$data['payment_address'] = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));
			} else {
				$data['payment_address'] = '';
			}

			// Shipping Address
			$this->load->model('account/address');

			$address_info = $this->model_account_address->getAddress($this->customer->getId(), $subscription_info['shipping_address_id']);

			if ($address_info) {
				if ($address_info['address_format']) {
					$format = $address_info['address_format'];
				} else {
					$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
				}

				$find = [
					'{firstname}',
					'{lastname}',
					'{company}',
					'{address_1}',
					'{address_2}',
					'{city}',
					'{postcode}',
					'{zone}',
					'{zone_code}',
					'{country}'
				];

				$replace = [
					'firstname' => $address_info['firstname'],
					'lastname'  => $address_info['lastname'],
					'company'   => $address_info['company'],
					'address_1' => $address_info['address_1'],
					'address_2' => $address_info['address_2'],
					'city'      => $address_info['city'],
					'postcode'  => $address_info['postcode'],
					'zone'      => $address_info['zone'],
					'zone_code' => $address_info['zone_code'],
					'country'   => $address_info['country']
				];

				$pattern_1 = [
					"\r\n",
					"\r",
					"\n"
				];

				$pattern_2 = [
					"/\\s\\s+/",
					"/\r\r+/",
					"/\n\n+/"
				];

				$data['shipping_address'] = str_replace($pattern_1, '<br/>', preg_replace($pattern_2, '<br/>', trim(str_replace($find, $replace, $format))));
			} else {
				$data['shipping_address'] = '';
			}

			if ($subscription_info['shipping_method']) {
				$data['shipping_method'] = $subscription_info['shipping_method']['name'];
			} else {
				$data['shipping_method'] = '';
			}

			if ($subscription_info['payment_method']) {
				$data['payment_method'] = $subscription_info['payment_method']['name'];
			} else {
				$data['payment_method'] = '';
			}

			// Products
			$data['products'] = [];

			$this->load->model('catalog/product');

			$results = $this->model_account_subscription->getProducts($subscription_id);

			foreach ($results as $result) {
				$option_data = [];

				$options = $this->model_account_subscription->getOptions($result['product_id'], $result['subscription_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = ['value' => (oc_strlen($value) > 20 ? oc_substr($value, 0, 20) . '..' : $value)] + $option;
				}

				$data['products'][] = [
					'option'      => $option_data,
					'trial_price' => $result['trial_price'] + ($this->config->get('config_tax') ? $result['trial_tax'] : 0),
					'price'       => $result['price'] + ($this->config->get('config_tax') ? $result['tax'] : 0),
					'view'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				] + $result;
			}

			$data['description'] = '';

			if ($subscription_info['trial_status']) {
				$trial_price = $subscription_info['trial_price'] + ($this->config->get('config_tax') ? $subscription_info['trial_tax'] : 0);
				$trial_cycle = $subscription_info['trial_cycle'];
				$trial_frequency = $this->language->get('text_' . $subscription_info['trial_frequency']);
				$trial_duration = $subscription_info['trial_duration'];

				$data['description'] .= sprintf($this->language->get('text_subscription_trial'), $subscription_info['currency_code'], $trial_price, $result['currency_value'], $subscription_info['currency_value'], $trial_cycle, $trial_frequency, $trial_duration);
			}

			$price = $subscription_info['price'] + ($this->config->get('config_tax') ? $result['trial_tax'] : 0);
			$cycle = $subscription_info['cycle'];
			$frequency = $this->language->get('text_' . $subscription_info['frequency']);
			$duration = $subscription_info['duration'];

			if ($duration) {
				$data['description'] .= sprintf($this->language->get('text_subscription_duration'), $subscription_info['currency_code'], $price, $result['currency_value'], $cycle, $frequency, $duration);
			} else {
				$data['description'] .= sprintf($this->language->get('text_subscription_cancel'), $subscription_info['currency_code'], $price, $result['currency_value'], $cycle, $frequency);
			}

			$data['date_next'] = date($this->language->get('date_format_short'), strtotime($subscription_info['date_next']));
			$data['duration'] = $subscription_info['duration'];
			$data['trial_duration'] = $subscription_info['trial_duration'];
			$data['remaining'] = $subscription_info['trial_remaining'] + $subscription_info['remaining'];

			// Orders
			$data['history'] = $this->getHistory();
			$data['order'] = $this->getOrders();

			if ($subscription_info['order_id']) {
				$data['order_link'] = $this->url->link('account/order.info', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&order_id=' . $subscription_info['order_id']);
			} else {
				$data['order_link'] = '';
			}

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['continue'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . $url);

			$data['language'] = $this->config->get('config_language');
			$data['currency_code'] = $subscription_info['currency_code'];
			$data['currency_value'] = $subscription_info['currency_value'];

			$data['customer_token'] = $this->session->data['customer_token'];

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('account/subscription_info', $data));
		} else {
			return new \Opencart\System\Engine\Action('error/not_found');
		}

		return null;
	}

	/**
	 * Cancel Subscription
	 *
	 * @return void
	 */
	public function cancel(): void {
		$this->load->language('account/subscription');

		$json = [];

		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language'));

			$json['redirect'] = $this->url->link('account/login', 'language=' . $this->config->get('config_language'), true);
		}

		if (!$json) {
			// Subscription
			$this->load->model('account/subscription');

			$subscription_info = $this->model_account_subscription->getSubscription($subscription_id);

			if ($subscription_info) {
				if ($subscription_info['trial_remaining']) {
					$json['error'] = sprintf($this->language->get('error_duration'), $subscription_info['trial_remaining'] + $subscription_info['remaining']);
				} elseif ($subscription_info['remaining']) {
					$json['error'] = sprintf($this->language->get('error_duration'), $subscription_info['remaining']);
				}

				if ($subscription_info['subscription_status_id'] == $this->config->get('config_subscription_canceled_status_id')) {
					$json['error'] = $this->language->get('error_canceled');
				}
			} else {
				$json['error'] = $this->language->get('error_subscription');
			}
		}

		if (!$json) {
			// Subscription
			$this->load->model('checkout/subscription');

			$this->model_checkout_subscription->addHistory($subscription_id, (int)$this->config->get('config_subscription_canceled_status_id'));

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
		$this->load->language('account/subscription');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->response->setOutput($this->getHistory());
	}

	/**
	 * Get History
	 *
	 * @return string
	 */
	protected function getHistory(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'account/subscription.history') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		$limit = 10;

		if (!$this->load->controller('account/login.validate')) {
			return '';
		}

		// Histories
		$data['histories'] = [];

		$this->load->model('account/subscription');

		$results = $this->model_account_subscription->getHistories($subscription_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['histories'][] = [
				'comment'    => nl2br($result['comment']),
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added']))
			] + $result;
		}

		// Total Histories
		$subscription_total = $this->model_account_subscription->getTotalHistories($subscription_id);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $subscription_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/subscription.history', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&subscription_id=' . $subscription_id . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($subscription_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($subscription_total - $limit)) ? $subscription_total : ((($page - 1) * $limit) + $limit), $subscription_total, ceil($subscription_total / $limit));

		return $this->load->view('account/subscription_history', $data);
	}

	/**
	 * Order
	 *
	 * @return void
	 */
	public function order(): void {
		$this->load->language('account/subscription');

		if (!$this->load->controller('account/login.validate')) {
			$this->session->data['redirect'] = $this->url->link('account/subscription', 'language=' . $this->config->get('config_language'));

			$this->response->redirect($this->url->link('account/login', 'language=' . $this->config->get('config_language'), true));
		}

		$this->response->setOutput($this->getOrders());
	}

	/**
	 * Get Orders
	 *
	 * @return string
	 */
	protected function getOrders(): string {
		if (isset($this->request->get['subscription_id'])) {
			$subscription_id = (int)$this->request->get['subscription_id'];
		} else {
			$subscription_id = 0;
		}

		if (isset($this->request->get['page']) && $this->request->get['route'] == 'account/subscription.order') {
			$page = (int)$this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!$this->load->controller('account/login.validate')) {
			return '';
		}

		$limit = 10;

		// Orders
		$data['orders'] = [];

		$this->load->model('account/order');

		$results = $this->model_account_order->getOrdersBySubscriptionId($subscription_id, ($page - 1) * $limit, $limit);

		foreach ($results as $result) {
			$data['orders'][] = [
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'view'       => $this->url->link('account/subscription.order', 'customer_token=' . $this->session->data['customer_token'] . '&order_id=' . $result['order_id'] . '&page={page}')
			] + $result;
		}

		// Total Orders
		$order_total = $this->model_account_order->getTotalOrdersBySubscriptionId($subscription_id);

		// Pagination
		$data['pagination'] = $this->load->controller('common/pagination', [
			'total' => $order_total,
			'page'  => $page,
			'limit' => $limit,
			'url'   => $this->url->link('account/subscription.order', 'language=' . $this->config->get('config_language') . '&customer_token=' . $this->session->data['customer_token'] . '&subscription_id=' . $subscription_id . '&page=' . $page)
		]);

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($order_total - $limit)) ? $order_total : ((($page - 1) * $limit) + $limit), $order_total, ceil($order_total / $limit));

		return $this->load->view('account/subscription_order', $data);
	}
}
