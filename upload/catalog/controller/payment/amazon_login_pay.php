<?php
class ControllerPaymentAmazonLoginPay extends Controller {
	public function address() {
		$this->load->language('payment/amazon_login_pay');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payment/amazon_login_pay');

		// capital L in Amazon cookie name is required, do not alter for coding standards
		if (!$this->customer->isLogged() || !isset($this->request->cookie['amazon_Login_state_cache'])) {
			$this->session->data['lpa']['error'] = $this->language->get('error_login');
			$this->response->redirect($this->url->link('payment/amazon_login_pay/failure', '', 'SSL'));
		}

		if ($this->config->get('amazon_login_pay_minimum_total') > 0 && $this->config->get('amazon_login_pay_minimum_total') > $this->cart->getTotal()) {
			$this->failure(sprintf($this->language->get('error_minimum'), $this->currency->format($this->config->get('amazon_login_pay_minimum_total'))));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_address'] = $this->language->get('heading_address');
		$data['text_back'] = $this->language->get('text_back');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_continue'] = $this->language->get('text_continue');
		$data['error_shipping'] = $this->language->get('error_shipping');
		$data['error_shipping_address'] = $this->language->get('error_shipping_address');
		$data['error_shipping_methods'] = $this->language->get('error_shipping_methods');
		$data['error_no_shipping_methods'] = $this->language->get('error_no_shipping_methods');

		$data['merchant_id'] = $this->config->get('amazon_login_pay_merchant_id');
		$data['amazon_payment'] = $this->url->link('payment/amazon_login_pay/orderreference', '', 'SSL');
		$data['order_reference'] = $this->url->link('payment/amazon_login_pay/shippingquotes', '', 'SSL');
		$data['shipping_quotes'] = $this->url->link('payment/amazon_login_pay/shippingquotes', '', 'SSL');
		$data['payment_method'] = $this->url->link('payment/amazon_login_pay/paymentmethod', '', 'SSL');

		$data['cart'] = $this->url->link('checkout/cart');
		$data['text_cart'] = $this->language->get('text_cart');

		$data['amazon_login_pay_merchant_id'] = $this->config->get('amazon_login_pay_merchant_id');
		$data['amazon_login_pay_client_id'] = $this->config->get('amazon_login_pay_client_id');
		$data['amazon_login_pay_client_secret'] = $this->config->get('amazon_login_pay_client_secret');
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			$data['amazon_login_pay_test'] = true;
		}

		$amazon_payment_js = $this->model_payment_amazon_login_pay->getWidgetJs();
		$this->document->addScript($amazon_payment_js);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_login_pay_address.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_login_pay_address.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_login_pay_address.tpl', $data));
		}
	}

	public function paymentMethod() {
		$this->load->language('payment/amazon_login_pay');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payment/amazon_login_pay');

		// capital L in Amazon cookie name is required, do not alter for coding standards
		if (!$this->customer->isLogged() || !isset($this->request->cookie['amazon_Login_state_cache'])) {
			$this->session->data['lpa']['error'] = $this->language->get('error_login');
			$this->response->redirect($this->url->link('payment/amazon_login_pay/failure', '', 'SSL'));
		}

		if ($this->config->get('amazon_login_pay_minimum_total') > 0 && $this->config->get('amazon_login_pay_minimum_total') > $this->cart->getTotal()) {
			$this->failure(sprintf($this->language->get('error_minimum'), $this->currency->format($this->config->get('amazon_login_pay_minimum_total'))));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_payment'] = $this->language->get('heading_payment');
		$data['text_back'] = $this->language->get('text_back');
		$data['text_continue'] = $this->language->get('text_continue');
		$data['error_payment_method'] = $this->language->get('error_payment_method');

		$data['amazon_login_pay_merchant_id'] = $this->config->get('amazon_login_pay_merchant_id');
		$data['amazon_login_pay_client_id'] = $this->config->get('amazon_login_pay_client_id');
		$data['amazon_login_pay_client_secret'] = $this->config->get('amazon_login_pay_client_secret');
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			$data['amazon_login_pay_test'] = true;
		}
		$data['confirm_order'] = $this->url->link('payment/amazon_login_pay/confirm', '', 'SSL');

		$amazon_payment_js = $this->model_payment_amazon_login_pay->getWidgetJs();
		$this->document->addScript($amazon_payment_js);

		$data['continue'] = $this->url->link('payment/amazon_login_pay/confirm', '', 'SSL');
		$data['back'] = $this->url->link('payment/amazon_login_pay/address', '', 'SSL');
		$data['text_back'] = $this->language->get('text_back');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_login_pay_payment.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_login_pay_payment.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_login_pay_payment.tpl', $data));
		}
	}

	public function confirm() {
		$this->load->language('payment/amazon_login_pay');
		$this->load->language('checkout/checkout');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/extension');
		$this->load->model('payment/amazon_login_pay');

		// capital L in Amazon cookie name is required, do not alter for coding standards
		if (!$this->customer->isLogged() || !isset($this->request->cookie['amazon_Login_state_cache'])) {
			$this->session->data['lpa']['error'] = $this->language->get('error_login');
			$this->response->redirect($this->url->link('payment/amazon_login_pay/loginFailure', '', 'SSL'));
		}

		if ($this->config->get('amazon_login_pay_minimum_total') > 0 && $this->config->get('amazon_login_pay_minimum_total') > $this->cart->getTotal()) {
			$this->failure(sprintf($this->language->get('error_minimum'), $this->currency->format($this->config->get('amazon_login_pay_minimum_total'))));
		}

		$data['amazon_login_pay_merchant_id'] = $this->config->get('amazon_login_pay_merchant_id');
		$data['amazon_login_pay_client_id'] = $this->config->get('amazon_login_pay_client_id');
		if ($this->config->get('amazon_login_pay_test') == 'sandbox') {
			$data['amazon_login_pay_test'] = true;
		}

		$amazon_payment_js = $this->model_payment_amazon_login_pay->getWidgetJs();
		$this->document->addScript($amazon_payment_js);

		if (isset($this->session->data['lpa']['AmazonOrderReferenceId'])) {
			$data['AmazonOrderReferenceId'] = $this->session->data['lpa']['AmazonOrderReferenceId'];
		} else {
			$this->failure($this->language->get('error_process_order'));
		}

		if (!empty($this->session->data['vouchers']) || !$this->cart->hasProducts() || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart'));
		}

		$data['heading_confirm'] = $this->language->get('heading_confirm');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_model'] = $this->language->get('column_model');
		$data['column_quantity'] = $this->language->get('column_quantity');
		$data['column_price'] = $this->language->get('column_price');
		$data['column_total'] = $this->language->get('column_total');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$this->response->redirect($this->url->link('checkout/cart'));
			}
		}

		if (!isset($this->session->data['lpa']['shipping_method']) || !isset($this->session->data['lpa']['address'])) {
			$this->response->redirect($this->url->link('payment/amazon_login_pay/address', '', 'SSL'));
		}

		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$old_taxes = $taxes;
		$lpa_tax = array();

		$sort_order = array();

		$results = $this->model_extension_extension->getExtensions('total');

		foreach ($results as $key => $value) {
			if (isset($value['code'])) {
				$code = $value['code'];
			} else {
				$code = $value['key'];
			}
			$sort_order[$key] = $this->config->get($code . '_sort_order');
		}

		array_multisort($sort_order, SORT_ASC, $results);

		foreach ($results as $result) {
			if (isset($result['code'])) {
				$code = $result['code'];
			} else {
				$code = $result['key'];
			}
			if ($this->config->get($code . '_status')) {
				$this->load->model('total/' . $code);

				$this->{'model_total_' . $code}->getTotal($total_data, $total, $taxes);

				if (!empty($total_data[count($total_data) - 1]) && !isset($total_data[count($total_data) - 1]['code'])) {
					$total_data[count($total_data) - 1]['code'] = $code;
				}

				$tax_difference = 0;

				foreach ($taxes as $tax_id => $value) {
					if (isset($old_taxes[$tax_id])) {
						$tax_difference += $value - $old_taxes[$tax_id];
					} else {
						$tax_difference += $value;
					}
				}

				if ($tax_difference != 0) {
					$lpa_tax[$code] = $tax_difference;
				}

				$old_taxes = $taxes;
			}
		}

		$sort_order = array();

		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];

			if (isset($lpa_tax[$value['code']])) {
				$total_data[$key]['lpa_tax'] = $lpa_tax[$value['code']];
			} else {
				$total_data[$key]['lpa_tax'] = '';
			}
		}

		array_multisort($sort_order, SORT_ASC, $total_data);

		$order_data = array();

		$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
		$order_data['store_id'] = $this->config->get('config_store_id');
		$order_data['store_name'] = $this->config->get('config_name');

		if ($order_data['store_id']) {
			$order_data['store_url'] = $this->config->get('config_url');
		} else {
			$order_data['store_url'] = HTTP_SERVER;
		}

		$address = $this->session->data['lpa']['address'];

		$order_data['customer_id'] = $this->customer->getId();
		$order_data['customer_group_id'] = $this->customer->getGroupId();
		$order_data['firstname'] = $this->customer->getFirstName();
		$order_data['lastname'] = $this->customer->getLastName();
		$order_data['email'] = $this->customer->getEmail();
		$order_data['telephone'] = $address['telephone'];
		$order_data['fax'] = '';

		if (isset($this->session->data['coupon'])) {
			$this->load->model('total/coupon');

			$coupon = $this->model_total_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon) {
				$order_data['coupon_id'] = $coupon['coupon_id'];
				$order_data['lpa_free_shipping'] = $coupon['shipping'];
			} else {
				$order_data['coupon_id'] = 0;
				$order_data['lpa_free_shipping'] = '0';
			}
		} else {
			$order_data['coupon_id'] = 0;
			$order_data['lpa_free_shipping'] = '0';
		}

		$order_data['payment_firstname'] = $this->customer->getFirstName();
		$order_data['payment_lastname'] = $this->customer->getLastName();
		$order_data['payment_company'] = $address['company'];
		$order_data['payment_company_id'] = $address['company_id'];
		$order_data['payment_tax_id'] = $address['tax_id'];
		$order_data['payment_address_1'] = $address['address_1'];
		$order_data['payment_address_2'] = $address['address_2'];
		$order_data['payment_city'] = $address['city'];
		$order_data['payment_postcode'] = $address['postcode'];
		$order_data['payment_zone'] = $address['zone'];
		$order_data['payment_zone_id'] = $address['zone_id'];
		$order_data['payment_country'] = $address['country'];
		$order_data['payment_country_id'] = $address['country_id'];
		$order_data['payment_address_format'] = $address['address_format'];

		$order_data['payment_method'] = $this->language->get('text_lpa');
		$order_data['payment_code'] = 'amazon_login_pay';

		$order_data['shipping_firstname'] = $address['firstname'];
		$order_data['shipping_lastname'] = $address['lastname'];
		$order_data['shipping_company'] = $address['company'];
		$order_data['shipping_address_1'] = $address['address_1'];
		$order_data['shipping_address_2'] = $address['address_2'];
		$order_data['shipping_city'] = $address['city'];
		$order_data['shipping_postcode'] = $address['postcode'];
		$order_data['shipping_zone'] = $address['zone'];
		$order_data['shipping_zone_id'] = $address['zone_id'];
		$order_data['shipping_country'] = $address['country'];
		$order_data['shipping_country_id'] = $address['country_id'];
		$order_data['shipping_address_format'] = $address['address_format'];
		$order_data['shipping_method'] = $this->session->data['lpa']['shipping_method']['title'];

		if (isset($this->session->data['lpa']['shipping_method']['code'])) {
			$order_data['shipping_code'] = $this->session->data['lpa']['shipping_method']['code'];
		} else {
			$order_data['shipping_code'] = '';
		}

		$product_data = array();

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$value = $this->encryption->decrypt($option['value']);
				}

				$option_data[] = array(
					'product_option_id' => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id' => $option['option_id'],
					'option_value_id' => $option['option_value_id'],
					'name' => $option['name'],
					'value' => $value,
					'type' => $option['type']
				);
			}

			$product_data[] = array(
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'model' => $product['model'],
				'option' => $option_data,
				'download' => $product['download'],
				'quantity' => $product['quantity'],
				'subtract' => $product['subtract'],
				'price' => $product['price'],
				'total' => $product['total'],
				'tax' => $this->tax->getTax($product['price'], $product['tax_class_id']),
				'reward' => $product['reward']
			);
		}

		$order_data['products'] = $product_data;
		$order_data['vouchers'] = array();
		$order_data['totals'] = $total_data;

		$order_data['comment'] = '';
		$order_data['total'] = $total;

		if (isset($this->request->cookie['tracking'])) {
			$order_data['tracking'] = $this->request->cookie['tracking'];

			$subtotal = $this->cart->getSubTotal();

			$this->load->model('affiliate/affiliate');

			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

			if ($affiliate_info) {
				$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			$this->load->model('checkout/marketing');

			$marketing_info = $this->model_checkout_marketing->getMarketingByCode($this->request->cookie['tracking']);

			if ($marketing_info) {
				$order_data['marketing_id'] = $marketing_info['marketing_id'];
			} else {
				$order_data['marketing_id'] = 0;
			}
		} else {
			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';
		}

		$order_data['language_id'] = $this->config->get('config_language_id');
		$order_data['currency_id'] = $this->currency->getId();
		$order_data['currency_code'] = $this->currency->getCode();
		$order_data['currency'] = $this->currency->getCode();
		$order_data['currency_value'] = $this->currency->getValue($this->currency->getCode());
		$order_data['value'] = $this->currency->getValue($this->currency->getCode());
		$order_data['ip'] = $this->request->server['REMOTE_ADDR'];

		if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
			$order_data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
		} else {
			$order_data['forwarded_ip'] = '';
		}

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$order_data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
		} else {
			$order_data['user_agent'] = '';
		}

		if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
			$order_data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
		} else {
			$order_data['accept_language'] = '';
		}

		$this->load->model('checkout/order');

		$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);

		$this->model_payment_amazon_login_pay->addTaxesForTotals($this->session->data['order_id'], $total_data);

		$this->session->data['lpa']['amazon_login_pay_order_id'] = $this->model_payment_amazon_login_pay->setOrderShipping($this->session->data['order_id'], $order_data['lpa_free_shipping']);

		$data['merchant_id'] = $this->config->get('amazon_login_pay_merchant_id');
		$data['process_order'] = $this->url->link('payment/amazon_login_pay/processorder', '', 'SSL');

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['value'];
				} else {
					$filename = $this->encryption->decrypt($option['value']);

					$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
				}

				$option_data[] = array(
					'name' => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			$data['products'][] = array(
				'product_id' => $product['product_id'],
				'name' => $product['name'],
				'model' => $product['model'],
				'option' => $option_data,
				'quantity' => $product['quantity'],
				'price' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'))),
				'total' => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')) * $product['quantity'])
			);
		}

		$data['vouchers'] = array();

		$data['totals'] = array();

		foreach ($total_data as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text' => $this->currency->format($total['value'])
			);
		}

		$data['back'] = $this->url->link('payment/amazon_login_pay/paymentMethod', '', 'SSL');
		$data['text_back'] = $this->language->get('text_back');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_login_pay_confirm.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_login_pay_confirm.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_login_pay_confirm.tpl', $data));
		}
	}

	public function processOrder() {
		$this->load->language('payment/amazon_login_pay');
		$this->load->model('checkout/order');
		$this->load->model('total/coupon');
		$this->load->model('account/order');
		$this->load->model('payment/amazon_login_pay');

		if (!isset($this->session->data['order_id'])) {
			$this->response->redirect($this->url->link('common/home'));
		}

		if (isset($this->session->data['coupon'])) {
			$coupon = $this->model_total_coupon->getCoupon($this->session->data['coupon']);
		} else {
			$coupon = array();
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		switch ($this->config->get('amazon_login_pay_marketplace')) {
			case 'us':
				$currency_code = 'USD';
				break;

			case 'uk':
				$currency_code = 'GBP';
				break;

			case 'de':
				$currency_code = 'EUR';
				break;
		}

		$ordered_products = $this->model_account_order->getOrderProducts($order_info['order_id']);

		$total = 0;

		$free_shipping = $this->model_payment_amazon_login_pay->hasFreeShipping($order_info['order_id']);
		$shipping_cost = $this->model_payment_amazon_login_pay->getShippingPrice($order_info['order_id']);

		if (!$free_shipping) {
			$total += $shipping_cost;
		}

		foreach ($ordered_products as $product) {
			$total += ($product['price'] + $product['tax']) * $product['quantity'];
		}

		$order_totals = $this->model_payment_amazon_login_pay->getAdditionalCharges($order_info['order_id']);

		foreach ($order_totals as $order_total) {
			$total += $order_total['price'];
		}
		if (!isset($this->session->data['lpa']['AmazonOrderReferenceId'])) {
			$this->failure($this->language->get('error_process_order'));
		}

		$total = $this->currency->format($total, $currency_code, false, false);

		$response = $this->model_payment_amazon_login_pay->sendOrder($order_info['order_id'], $total, $currency_code);
		$this->model_payment_amazon_login_pay->logger($response);

		if (isset($response['redirect'])) {
			$this->$response['redirect']($this->language->get('error_process_order'));
			$this->session->data['lpa']['error'] = $this->language->get('error_process_order');
			$this->response->redirect($this->url->link('payment/amazon_login_pay/' . $response['redirect'], '', 'SSL'));
		}

		if ($response['status'] == 'Closed' || $response['status'] == 'Open') {
			$this->model_payment_amazon_login_pay->addAmazonOrderId($order_info['order_id'], $response['amazon_authorization_id'], $response['capture_status'], $total, $currency_code);

			$this->model_payment_amazon_login_pay->addTransaction($this->session->data['lpa']['amazon_login_pay_order_id'], $response['amazon_authorization_id'], null, 'authorization', $response['status'], $total);

			if (isset($response['amazon_capture_id'])) {
				$this->model_payment_amazon_login_pay->closeOrderRef($this->session->data['lpa']['AmazonOrderReferenceId']);
				$this->model_payment_amazon_login_pay->addTransaction($this->session->data['lpa']['amazon_login_pay_order_id'], $response['amazon_authorization_id'], $response['amazon_capture_id'], 'capture', $response['status'], $total);
			}

			if (isset($response['billing_address'])) {
				$this->setBillingAddress($order_info, $response['billing_address']);
			}

			$this->model_checkout_order->addOrderHistory($order_info['order_id'], $this->config->get('amazon_login_pay_pending_status'));
			unset($this->session->data['lpa']);

			$this->response->redirect($this->url->link('payment/amazon_login_pay/success', '', 'SSL'));
		} else {
			$this->failure($this->language->get('error_process_order'));
		}
	}

	public function success() {
		unset($this->session->data['lpa']);
		$this->response->redirect($this->url->link('checkout/success', '', 'SSL'));
	}

	public function failure($error) {
		unset($this->session->data['lpa']);
		$this->session->data['error'] = $error;
		$this->response->redirect($this->url->link('checkout/cart', '', 'SSL'));
	}

	public function loginFailure() {
		$this->load->language('payment/amazon_login_pay');
		$this->document->setTitle($this->language->get('heading_title'));
		$data['heading_title'] = $this->language->get('heading_title');

		if (isset($this->session->data['lpa']['error'])) {
			$data['error'] = $this->session->data['lpa']['error'];
		} else {
			$data['error'] = '';
		}

		unset($this->session->data['lpa']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_login_pay_failure.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_login_pay_failure.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_login_pay_failure.tpl', $data));
		}
	}

	public function shippingQuotes() {
		$this->load->language('payment/amazon_login_pay');

		$this->load->model('extension/extension');
		$this->load->model('payment/amazon_login_pay');

		$json = array();

		if (isset($this->request->get['AmazonOrderReferenceId'])) {
			$this->session->data['lpa']['AmazonOrderReferenceId'] = $this->request->get['AmazonOrderReferenceId'];
		} else {
			$json['error'] = $this->language->get('error_shipping_methods');
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
		}

		$address_xml = $this->model_payment_amazon_login_pay->getAddress();

		if (isset($address_xml)) {

			$result = $this->model_payment_amazon_login_pay->getCountry((string)$address_xml->CountryCode);

			if (!empty($result)) {
				$iso_code2 = $result['iso_code_2'];
				$iso_code3 = $result['iso_code_3'];
				$address_format = $result['address_format'];
				$country_name = $result['name'];
				$country_id = (int)$result['country_id'];

				$zone = (string)$address_xml->StateOrRegion;

				$result = $this->model_payment_amazon_login_pay->getZone($zone, $country_id);

				if (isset($result['zone_id'])) {
					$zone_id = $result['zone_id'];
					$zone_code = $result['code'];
				} else {
					$zone_id = 0;
					$zone_code = '';
				}
			} else {
				$iso_code2 = '';
				$iso_code3 = '';
				$address_format = '';
				$country_name = '';
				$country_id = 0;
				$zone_id = 0;
				$zone_code = '';
				$zone = '';
			}

			$this->tax->setShippingAddress($country_id, $zone_id);

			$AddressLine1 = (string)$address_xml->AddressLine1;
			$AddressLine2 = (string)$address_xml->AddressLine2;
			$AddressLine3 = (string)$address_xml->AddressLine3;

			if ($AddressLine3 != '') {
				$address_1 = $AddressLine3;
				$address_2 = $AddressLine1 + $AddressLine2;
			} elseif ($AddressLine2 != '') {
				$address_1 = $AddressLine2;
				$address_2 = $AddressLine1;
			} elseif ($AddressLine1 != '') {
				$address_1 = $AddressLine1;
				$address_2 = '';
			}

			$full_name = explode(' ', (string)$address_xml->Name);
			$last_name = array_pop($full_name);
			$first_name = implode(' ', $full_name);

			$address = array(
				'firstname' => $first_name,
				'lastname' => $last_name,
				'company' => '',
				'company_id' => '',
				'tax_id' => '',
				'telephone' => (string)$address_xml->Phone,
				'address_1' => $address_1,
				'address_2' => $address_2,
				'postcode' => (string)$address_xml->PostalCode,
				'city' => (string)$address_xml->City,
				'zone_id' => $zone_id,
				'zone' => (string)$address_xml->StateOrRegion,
				'zone_code' => $zone_code,
				'country_id' => $country_id,
				'country' => $country_name,
				'iso_code_2' => $iso_code2,
				'iso_code_3' => $iso_code3,
				'address_format' => $address_format
			);

			$quotes = array();

			$results = $this->model_extension_extension->getExtensions('shipping');

			foreach ($results as $result) {

				if (isset($result['code'])) {
					$code = $result['code'];
				} else {
					$code = $result['key'];
				}

				if ($this->config->get($code . '_status')) {
					$this->load->model('shipping/' . $code);

					$quote = $this->{'model_shipping_' . $code}->getQuote($address);

					if ($quote && empty($quote['error'])) {
						$quotes[$code] = array(
							'title' => $quote['title'],
							'quote' => $quote['quote'],
							'sort_order' => $quote['sort_order'],
							'error' => $quote['error']
						);
					}
				}
			}

			$sort_order = array();

			foreach ($quotes as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $quotes);

			$this->session->data['lpa']['shipping_methods'] = $quotes;
			$this->session->data['lpa']['address'] = $address;

			if (empty($quotes)) {
				$json['error'] = $this->language->get('error_no_shipping_methods');
			} else {
				$json['quotes'] = $quotes;
			}

			if (isset($this->session->data['lpa']['shipping_method']) && !empty($this->session->data['lpa']['shipping_method']) && isset($this->session->data['lpa']['shipping_method']['code'])) {
				$json['selected'] = $this->session->data['lpa']['shipping_method']['code'];
			} else {
				$json['selected'] = '';
			}
		} else {
			$json['error'] = $this->language->get('error_shipping_methods');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setShipping() {
		$json = array();

		if (isset($this->request->post['shipping_method'])) {
			$shipping_method = explode('.', $this->request->post['shipping_method']);

			if (!isset($shipping_method[0]) || !isset($shipping_method[1]) || !isset($this->session->data['lpa']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]])) {
				$this->response->redirect($this->url->link('common/home'));
			}

			$this->session->data['lpa']['shipping_method'] = $this->session->data['lpa']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]];
			$this->session->data['shipping_method'] = $this->session->data['lpa']['shipping_method'];
			$this->session->data['shipping_address'] = $this->session->data['lpa']['address'];
			$this->session->data['shipping_country_id'] = $this->session->data['lpa']['address']['country_id'];
			$this->session->data['shipping_zone_id'] = $this->session->data['lpa']['address']['zone_id'];

			$json['redirect'] = $this->url->link('payment/amazon_login_pay/paymentMethod', '', 'SSL');
		} else {
			$json['redirect'] = $this->url->link('payment/amazon_login_pay/paymentMethod', '', 'SSL');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function setBillingAddress($order_info, $billing_address) {
		$this->load->model('payment/amazon_login_pay');

		$full_name = explode(' ', $billing_address->Name);
		$last_name = array_pop($full_name);
		$first_name = implode(' ', $full_name);

		$AddressLine1 = (string)$billing_address->AddressLine1;
		$AddressLine2 = (string)$billing_address->AddressLine2;
		$AddressLine3 = (string)$billing_address->AddressLine3;

		if ($AddressLine3 != '') {
			$address_1 = $AddressLine3;
			$address_2 = $AddressLine1 + $AddressLine2;
		} elseif ($AddressLine2 != '') {
			$address_1 = $AddressLine2;
			$address_2 = $AddressLine1;
		} elseif ($AddressLine1 != '') {
			$address_1 = $AddressLine1;
			$address_2 = '';
		}

		$result = $this->model_payment_amazon_login_pay->getCountry((string)$billing_address->CountryCode);

		if (!empty($result)) {
			$iso_code2 = $result['iso_code_2'];
			$iso_code3 = $result['iso_code_3'];
			$address_format = $result['address_format'];
			$country_name = $result['name'];
			$country_id = (int)$result['country_id'];
		}

		$order_info['payment_firstname'] = $first_name;
		$order_info['payment_lastname'] = $last_name;
		$order_info['payment_address_1'] = $address_1;
		$order_info['payment_address_2'] = $address_2;
		$order_info['payment_city'] = $billing_address->City;
		$order_info['payment_country'] = $country_name;
		$order_info['payment_country_id'] = $country_id;
		$order_info['payment_postcode'] = $billing_address->PostalCode;
		$order_info['address_format'] = $address_format;
		$order_info['iso_code_2'] = $iso_code2;
		$order_info['iso_code_3'] = $iso_code3;

		$this->model_payment_amazon_login_pay->editOrder($order_info['order_id'], $order_info);
	}

	public function ipn() {
		$this->load->model('payment/amazon_login_pay');
		$this->model_payment_amazon_login_pay->logger('IPN received');
		if (isset($this->request->get['token']) && $this->request->get['token'] == $this->config->get('amazon_login_pay_ipn_token')) {
			$body = file_get_contents('php://input');
			if ($body) {
				$ipn_details_xml = $this->model_payment_amazon_login_pay->parseRawMessage($body);
				switch ($ipn_details_xml->getName()) {
					case 'AuthorizationNotification':
						$this->model_payment_amazon_login_pay->authorizationIpn($ipn_details_xml);
						break;
					case 'CaptureNotification':
						$this->model_payment_amazon_login_pay->captureIpn($ipn_details_xml);
						break;
					case 'RefundNotification':
						$this->model_payment_amazon_login_pay->refundIpn($ipn_details_xml);
						break;
				}
			}
		} else {
			$this->model_payment_amazon_login_pay->logger('Incorrect security token');
		}

		$this->response->addHeader('HTTP/1.1 200 OK');
		$this->response->addHeader('Content-Type: application/json');
	}

	public function capture($order_id) {
		$this->load->model('payment/amazon_login_pay');
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($order_id);
		if ($order_info['order_status_id'] == $this->config->get('amazon_login_pay_capture_status')) {
			$amazon_login_pay_order = $this->model_payment_amazon_login_pay->getOrder($order_id);

			$capture_response = $this->model_payment_amazon_login_pay->capture($amazon_login_pay_order);

			if (isset($capture_response['status']) && ($capture_response['status'] == 'Completed' || $capture_response['status'] == 'Pending')) {
				$this->model_payment_amazon_login_pay->closeOrderRef($amazon_login_pay_order['amazon_order_reference_id']);
				$this->model_payment_amazon_login_pay->addTransaction($amazon_login_pay_order['amazon_login_pay_order_id'], $amazon_login_pay_order['amazon_authorization_id'], $capture_response['amazon_capture_id'], 'capture', $capture_response['status'], $amazon_login_pay_order['total']);
				$this->model_payment_amazon_login_pay->updateCaptureStatus($amazon_login_pay_order['amazon_login_pay_order_id'], 1);
			}
		}
	}
}
