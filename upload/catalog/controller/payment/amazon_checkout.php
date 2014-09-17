<?php
class ControllerPaymentAmazonCheckout extends Controller {
	public function address() {
		if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
			}
		} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
			}
		}

		$this->document->addScript($amazon_payment_js);

		// CBA supports up to 50 distinct products
		if (count($this->cart->getProducts()) > 50) {
			$this->response->redirect($this->url->link('common/home'));
		}

		// CBA does not allow to process orders with a total of 0.00
		if (count($this->cart->getTotal()) == 0) {
			$this->response->redirect($this->url->link('common/home'));
		}

		$this->load->model('account/address');
		$this->load->language('payment/amazon_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['contract_id'])) {
			$this->session->data['cba']['contract_id'] = $this->request->get['contract_id'];
		} elseif (!isset($this->session->data['cba']['contract_id']) || empty($this->session->data['cba']['contract_id'])) {
			$this->response->redirect($this->url->link('common/home'));
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

		$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
		$data['amazon_payment'] = $this->url->link('payment/amazon_checkout/payment', '', 'SSL');
		$data['shipping_quotes'] = $this->url->link('payment/amazon_checkout/shippingquotes', '', 'SSL');
		$data['payment_method'] = $this->url->link('payment/amazon_checkout/paymentmethod', '', 'SSL');

		$data['cart'] = $this->url->link('checkout/cart');
		$data['text_cart'] = $this->language->get('text_cart');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_checkout_address.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_checkout_address.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_checkout_address.tpl', $data));
		}
	}

	public function paymentMethod() {
		if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
			}
		} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
			}
		}

		$this->document->addScript($amazon_payment_js);

		$this->load->language('payment/amazon_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['cba'])) {
			$contract_id = $this->session->data['cba']['contract_id'];
		} else {
			$this->response->redirect($this->url->link('common/home'));
		}

		$this->load->language('payment/amazon_checkout');

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_payment'] = $this->language->get('heading_payment');
		$data['text_back'] = $this->language->get('text_back');
		$data['text_continue'] = $this->language->get('text_continue');
		$data['error_payment_method'] = $this->language->get('error_payment_method');

		$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
		$data['confirm_order'] = $this->url->link('payment/amazon_checkout/confirm', '', 'SSL');

		$data['continue'] = $this->url->link('payment/amazon_checkout/confirm', '', 'SSL');
		$data['back'] = $this->url->link('payment/amazon_checkout/address', '', 'SSL');
		$data['text_back'] = $this->language->get('text_back');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_checkout_payment.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_checkout_payment.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_checkout_payment.tpl', $data));
		}
	}

	public function confirm() {
		$this->load->model('extension/extension');
		$this->load->model('account/address');
		$this->load->model('account/custom_field');
		$this->load->model('payment/amazon_checkout');
		$this->load->library('cba');
		$this->load->language('checkout/checkout');
		$this->load->language('payment/amazon_checkout');

		if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
			}
		} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
			}
		}

		$this->document->addScript($amazon_payment_js);

		$this->document->setTitle($this->language->get('heading_title'));

		if (!isset($this->session->data['cba']) || !isset($this->session->data['cba']['shipping_method'])) {
			$this->response->redirect($this->url->link('common/home'));
		}

		// Validate cart has products and has stock.
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

		// Validate minimum quantity requirements.
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

		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();

		$old_taxes = $taxes;
		$cba_tax = array();

		$sort_order = array();

		$this->session->data['shipping_method'] = $this->session->data['cba']['shipping_method'];

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
					$cba_tax[$code] = $tax_difference;
				}

				$old_taxes = $taxes;
			}
		}

		$sort_order = array();

		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];

			if (isset($cba_tax[$value['code']])) {
				$total_data[$key]['cba_tax'] = $cba_tax[$value['code']];
			} else {
				$total_data[$key]['cba_tax'] = '';
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

		if ($this->customer->isLogged()) {
			$order_data['customer_id'] = $this->customer->getId();
			$order_data['customer_group_id'] = $this->customer->getGroupId();
			$order_data['firstname'] = '';
			$order_data['lastname'] = '';
			$order_data['email'] = '';
			$order_data['telephone'] = '';
			$order_data['fax'] = $this->customer->getFax();
		} else {
			$order_data['customer_id'] = 0;
			$order_data['customer_group_id'] = $this->config->get('config_customer_group_id');
			$order_data['firstname'] = '';
			$order_data['lastname'] = '';
			$order_data['email'] = '';
			$order_data['telephone'] = '';
			$order_data['fax'] = '';
		}

		if (isset($this->session->data['coupon'])) {
			$this->load->model('checkout/coupon');

			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);

			if ($coupon) {
				$order_data['coupon_id'] = $coupon['coupon_id'];
				$order_data['cba_free_shipping'] = $coupon['shipping'];
			} else {
				$order_data['coupon_id'] = 0;
				$order_data['cba_free_shipping'] = '0';
			}
		} else {
			$order_data['coupon_id'] = 0;
			$order_data['cba_free_shipping'] = '0';
		}

		$order_data['payment_firstname'] = '';
		$order_data['payment_lastname'] = '';
		$order_data['payment_company'] = '';
		$order_data['payment_company_id'] = '';
		$order_data['payment_tax_id'] = '';
		$order_data['payment_address_1'] = '';
		$order_data['payment_address_2'] = '';
		$order_data['payment_city'] = '';
		$order_data['payment_postcode'] = '';
		$order_data['payment_zone'] = '';
		$order_data['payment_zone_id'] = '';
		$order_data['payment_country'] = '';
		$order_data['payment_country_id'] = '';
		$order_data['payment_address_format'] = '';

		$order_data['payment_method'] = $this->language->get('text_cba');
		$order_data['payment_code'] = 'amazon_checkout';

		$order_data['shipping_firstname'] = '';
		$order_data['shipping_lastname'] = '';
		$order_data['shipping_company'] = '';
		$order_data['shipping_address_1'] = '';
		$order_data['shipping_address_2'] = '';
		$order_data['shipping_city'] = '';
		$order_data['shipping_postcode'] = '';
		$order_data['shipping_zone'] = '';
		$order_data['shipping_zone_id'] = '';
		$order_data['shipping_country'] = '';
		$order_data['shipping_country_id'] = '';
		$order_data['shipping_address_format'] = '';
		$order_data['shipping_method'] = $this->session->data['cba']['shipping_method']['title'];

		if (isset($this->session->data['cba']['shipping_method']['code'])) {
			$order_data['shipping_code'] = $this->session->data['cba']['shipping_method']['code'];
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
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],
					'name'                    => $option['name'],
					'value'                   => $value,
					'type'                    => $option['type']
				);
			}

			$product_data[] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
				'reward'     => $product['reward']
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

			// Affiliate
			$affiliate_info = $this->model_affiliate_affiliate->getAffiliateByCode($this->request->cookie['tracking']);

			if ($affiliate_info) {
				$order_data['affiliate_id'] = $affiliate_info['affiliate_id'];
				$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
			} else {
				$order_data['affiliate_id'] = 0;
				$order_data['commission'] = 0;
			}

			// Marketing
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

		$this->session->data['cba']['order_id'] = $this->model_checkout_order->addOrder($order_data);
		$this->model_payment_amazon_checkout->addTaxesForTotals($this->session->data['cba']['order_id'], $total_data);

		$this->model_payment_amazon_checkout->setOrderShipping($this->session->data['cba']['order_id'], $order_data['cba_free_shipping']);

		$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
		$data['process_order'] = $this->url->link('payment/amazon_checkout/processorder', '', 'SSL');

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
						'name'  => $option['name'],
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
				'text'  => $this->currency->format($total['value'])
			);
		}

		$data['back'] = $this->url->link('payment/amazon_checkout/payment_method', '', 'SSL');
		$data['text_back'] = $this->language->get('text_back');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_checkout_confirm.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_checkout_confirm.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_checkout_confirm.tpl', $data));
		}
	}

	public function processOrder() {
		if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
			}
		} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
			}
		}

		$this->document->addScript($amazon_payment_js);

		$this->load->library('cba');
		$this->load->model('checkout/order');
		$this->load->model('checkout/coupon');
		$this->load->model('extension/extension');
		$this->load->model('account/order');
		$this->load->model('payment/amazon_checkout');
		$this->load->language('payment/amazon_checkout');

		if (!isset($this->session->data['cba']['order_id'])) {
			$this->response->redirect($this->url->link('common/home'));
		}

		if (isset($this->session->data['coupon'])) {
			$coupon = $this->model_checkout_coupon->getCoupon($this->session->data['coupon']);
		} else {
			$coupon = array();
		}

		$order = $this->model_checkout_order->getOrder($this->session->data['cba']['order_id']);

		$parameters_items = array();

		$cba_marketplace = $this->config->get('amazon_checkout_marketplace');

		switch ($cba_marketplace) {
			case 'uk':
				$currency_code = 'GBP';
				break;

			case 'de':
				$currency_code = 'EUR';
				break;
		}

		$ordered_products = $this->model_account_order->getOrderProducts($order['order_id']);

		$total = 0;

		$free_shipping = $this->model_payment_amazon_checkout->hasFreeShipping($order['order_id']);
		$shipping_cost = $this->model_payment_amazon_checkout->getShippingPrice($order['order_id']);

		if (!$free_shipping) {
			$total += $shipping_cost;
		}

		foreach ($ordered_products as $product) {

			$parameters_items['products'][] = array(
				'title'    => html_entity_decode($product['name'], ENT_QUOTES, 'UTF-8'),
				'model'    => $product['order_product_id'],
				'quantity' => $product['quantity'],
				'price'    => $this->currency->format($product['price'] + $product['tax'], $currency_code, '', false)
			);

			$total += ($product['price'] + $product['tax']) * $product['quantity'];

		}

		$order_totals = $this->model_payment_amazon_checkout->getAdditionalCharges($order['order_id']);

		foreach ($order_totals as $order_total) {
			$parameters_items['products'][] = array(
				'title' => $order_total['title'],
				'model' => 'ot_' . $order_total['order_total_id'],
				'quantity' => 1,
				'price' => $this->currency->format($order_total['price'], $currency_code, '', false)
			);

			$total += $order_total['price'];
		}

		$parameters_items['currency'] = $currency_code;
		$parameters_items['contract_id'] = $this->session->data['cba']['contract_id'];

		$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'),
				$this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));
		$cba->setMode($this->config->get('amazon_checkout_mode'));

		if ($cba->setPurchaseItems($parameters_items) !== true) {
			$this->response->redirect($this->url->link('payment/amazon_checkout/failure', '', 'SSL'));
		}

		$order_discount = $order['total'] - $total;

		$parameters_charges = array();

		$parameters_charges['contract_id'] = $this->session->data['cba']['contract_id'];
		$parameters_charges['currency'] = $currency_code;

		if ($free_shipping) {
			$parameters_charges['shipping_price'] = '0.00';
		} else {
			$parameters_charges['shipping_price'] = $this->currency->format($shipping_cost, $currency_code, '', false);
		}

		if ($order_discount < 0) {
			$parameters_charges['discount'] = $this->currency->format(-$order_discount, $currency_code, '', false);
		}

		if (!$cba->setContractCharges($parameters_charges)) {
			$this->response->redirect($this->url->link('payment/amazon_checkout/failure', '', 'SSL'));
		}

		$complete_parameters = array();
		$complete_parameters['contract_id'] = $this->session->data['cba']['contract_id'];

		$amazon_order_ids = $cba->completePurchaseContracts($complete_parameters);

		unset($this->session->data['cba']);

		if ($amazon_order_ids) {
			$this->model_payment_amazon_checkout->addAmazonOrderId($order['order_id'], $amazon_order_ids[0]);
		} else {
			$this->response->redirect($this->url->link('payment/amazon_checkout/failure', '', 'SSL'));
		}

		$this->model_checkout_order->addOrderHistory($order['order_id'], $this->config->get('amazon_checkout_order_status_id'));

		$this->response->redirect($this->url->link('payment/amazon_checkout/success', 'amazon_order_id=' . $amazon_order_ids[0], 'SSL'));
	}

	public function success() {
		if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js';
			}
		} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
			if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
			} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
				$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js';
			}
		}

		$this->document->addScript($amazon_payment_js);

		$this->load->language('payment/amazon_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['heading_address'] = $this->language->get('heading_title');
		$data['heading_payment'] = $this->language->get('heading_title');
		$data['heading_confirm'] = $this->language->get('heading_title');

		$data['text_back'] = $this->language->get('text_back');
		$data['text_cart'] = $this->language->get('text_cart');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_continue'] = $this->language->get('text_continue');
		$data['text_cba'] = $this->language->get('text_cba');
		$data['text_enter_coupon'] = $this->language->get('text_enter_coupon');
		$data['text_coupon'] = $this->language->get('text_coupon');
		$data['text_tax_other'] = $this->language->get('text_tax_other');
		$data['text_payment_failed'] = $this->language->get('text_payment_failed');
		$data['text_success_title'] = $this->language->get('text_success_title');
		$data['text_payment_success'] = $this->language->get('text_payment_success');

		$data['error_payment_method'] = $this->language->get('error_payment_method');
		$data['error_shipping'] = $this->language->get('error_shipping');
		$data['error_shipping_address'] = $this->language->get('error_shipping_address');
		$data['error_shipping_methods'] = $this->language->get('error_shipping_methods');
		$data['error_no_shipping_methods'] = $this->language->get('error_no_shipping_methods');

		$data['amazon_order_id'] = $this->request->get['amazon_order_id'];
		$data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');

		$this->cart->clear();
		unset($this->session->data['cba']);
		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_checkout_success.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_checkout_success.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_checkout_success.tpl', $data));
		}
	}

	public function failure() {
		$this->load->language('payment/amazon_checkout');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_payment_failed'] = $this->language->get('text_payment_failed');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/amazon_checkout_failure.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/amazon_checkout_failure.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/amazon_checkout_failure.tpl', $data));
		}
	}

	public function shippingQuotes() {
		$this->load->model('extension/extension');

		$this->load->library('cba');
		$this->load->model('payment/amazon_checkout');

		$this->load->language('payment/amazon_checkout');

		if (!isset($this->session->data['cba'])) {
			$this->response->redirect($this->url->link('common/home'));
		}

		$contract_id = $this->session->data['cba']['contract_id'];

		$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'),
				$this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));
		$cba->setMode($this->config->get('amazon_checkout_mode'));

		$response = $cba->getPurchaseContract($contract_id);

		$xml = simplexml_load_string($response);

		$json = array();

		if (isset($xml->GetPurchaseContractResult->PurchaseContract->Destinations->Destination[0]->PhysicalDestinationAttributes->ShippingAddress)) {
			$address_xml = $xml->GetPurchaseContractResult->PurchaseContract->Destinations->Destination[0]->PhysicalDestinationAttributes->ShippingAddress;

			$result = $this->model_payment_amazon_checkout->getCountry((string)$address_xml->CountryCode);

			if (!empty($result)) {
				$iso_code2 = $result['iso_code_2'];
				$iso_code3 = $result['iso_code_3'];
				$address_format = $result['address_format'];
				$country_name = $result['name'];
				$country_id = (int)$result['country_id'];

				$zone = (string)$address_xml->StateOrProvinceCode;

				$result = $this->model_payment_amazon_checkout->getZone($zone, $country_id);

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

			$address = array(
				'firstname' => (string)$address_xml->Name,
				'lastname' => '',
				'company' => '',
				'company_id' => '',
				'tax_id' => '',
				'address_1' => '',
				'address_2' => '',
				'postcode' => (string)$address_xml->PostalCode,
				'city' => (string)$address_xml->City,
				'zone_id' => $zone_id,
				'zone' => (string)$address_xml->StateOrProvinceCode,
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

			$this->session->data['cba']['shipping_methods'] = $quotes;

			if (empty($quotes)) {
				$json['error'] = $this->language->get('error_no_shipping_methods');
			} else {
				$json['quotes'] = $quotes;
			}

			if (isset($this->session->data['cba']['shipping_method']) && !empty($this->session->data['cba']['shipping_method'])
					&& isset($this->session->data['cba']['shipping_method']['code'])) {
				$json['selected'] = $this->session->data['cba']['shipping_method']['code'];
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

			if (!isset($shipping_method[0]) || !isset($shipping_method[1]) || !isset($this->session->data['cba']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]]) ) {
				$this->response->redirect($this->url->link('common/home'));
			}

			$this->session->data['cba']['shipping_method'] = $this->session->data['cba']['shipping_methods'][$shipping_method[0]]['quote'][$shipping_method[1]];

			$json['redirect'] = $this->url->link('payment/amazon_checkout/paymentMethod', '', 'SSL');
		} else {
			$json['redirect'] = $this->url->link('payment/amazon_checkout/paymentMethod', '', 'SSL');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cron() {
		if (isset($this->request->get['token']) && $this->request->get['token'] == $this->config->get('amazon_checkout_cron_job_token') && $this->config->get('amazon_checkout_status') == 1) {
			$this->load->model('payment/amazon_checkout');
			$this->load->library('cba');

			$cba = new CBA($this->config->get('amazon_checkout_merchant_id'), $this->config->get('amazon_checkout_access_key'), $this->config->get('amazon_checkout_access_secret'), $this->config->get('amazon_checkout_marketplace'));
			$cba->setMode('live');

			$cba->processOrderReports($this->config, $this->db);
			$cba->processFeedResponses($this->config, $this->db);

			$this->model_payment_amazon_checkout->updateCronJobRunTime();
		}
	}
}