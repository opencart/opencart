<?php
namespace Opencart\Catalog\Controller\Checkout;
class Confirm extends \Opencart\System\Engine\Controller {
	public function index(): string {
		$this->load->language('checkout/confirm');

		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$status = false;
		}

		// Order Totals
		$totals = [];
		$taxes = $this->cart->getTaxes();
		$total = 0;

		$this->load->model('checkout/cart');

		($this->model_checkout_cart->getTotals)($totals, $taxes, $total);

		$status = true;

		// Validate customer data is set
		if (!isset($this->session->data['customer'])) {
			$status = false;
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$status = false;
		}

		// Validate minimum quantity requirements.
		$products = $this->model_checkout_cart->getProducts();

		foreach ($products as $product) {
			if (!$product['minimum']) {
				$status = false;

				break;
			}
		}

		// Validate if payment address has been set.
		if ($this->config->get('config_checkout_address') && !isset($this->session->data['payment_address'])) {
			$status = false;
		}

		// Shipping
		if ($this->cart->hasShipping()) {
			// Validate shipping address
			if (!isset($this->session->data['shipping_address'])) {
				$status = false;
			}

			// Validate shipping method
			if (isset($this->session->data['shipping_method']) && isset($this->session->data['shipping_methods'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
					$status = false;
				}
			} else {
				$status = false;
			}
		} else {
			unset($this->session->data['shipping_address']);
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate Payment methods
		if (!isset($this->session->data['payment_method']) || !isset($this->session->data['payment_methods']) || !isset($this->session->data['payment_methods'][$this->session->data['payment_method']])) {
			$status = false;
		}

		// Validate checkout terms
		if ($this->config->get('config_checkout_id') && !isset($this->session->data['agree'])) {
			$status = false;
		}

		// Generate order if payment method is set
		if ($status) {
			$order_data = [];

			// Store Details
			$order_data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$order_data['store_id'] = $this->config->get('config_store_id');
			$order_data['store_name'] = $this->config->get('config_name');
			$order_data['store_url'] = $this->config->get('config_url');

			// Customer Details
			$order_data['customer_id'] = $this->session->data['customer']['customer_id'];
			$order_data['customer_group_id'] = $this->session->data['customer']['customer_group_id'];
			$order_data['firstname'] = $this->session->data['customer']['firstname'];
			$order_data['lastname'] = $this->session->data['customer']['lastname'];
			$order_data['email'] = $this->session->data['customer']['email'];
			$order_data['telephone'] = $this->session->data['customer']['telephone'];
			$order_data['custom_field'] = $this->session->data['customer']['custom_field'];

			// Payment Details
			if ($this->config->get('config_checkout_address')) {
				$order_data['payment_firstname'] = $this->session->data['payment_address']['firstname'];
				$order_data['payment_lastname'] = $this->session->data['payment_address']['lastname'];
				$order_data['payment_company'] = $this->session->data['payment_address']['company'];
				$order_data['payment_address_1'] = $this->session->data['payment_address']['address_1'];
				$order_data['payment_address_2'] = $this->session->data['payment_address']['address_2'];
				$order_data['payment_city'] = $this->session->data['payment_address']['city'];
				$order_data['payment_postcode'] = $this->session->data['payment_address']['postcode'];
				$order_data['payment_zone'] = $this->session->data['payment_address']['zone'];
				$order_data['payment_zone_id'] = $this->session->data['payment_address']['zone_id'];
				$order_data['payment_country'] = $this->session->data['payment_address']['country'];
				$order_data['payment_country_id'] = $this->session->data['payment_address']['country_id'];
				$order_data['payment_address_format'] = $this->session->data['payment_address']['address_format'];
				$order_data['payment_custom_field'] = isset($this->session->data['payment_address']['custom_field']) ? $this->session->data['payment_address']['custom_field'] : [];
			} else {
				$order_data['payment_firstname'] = '';
				$order_data['payment_lastname'] = '';
				$order_data['payment_company'] = '';
				$order_data['payment_address_1'] = '';
				$order_data['payment_address_2'] = '';
				$order_data['payment_city'] = '';
				$order_data['payment_postcode'] = '';
				$order_data['payment_zone'] = '';
				$order_data['payment_zone_id'] = 0;
				$order_data['payment_country'] = '';
				$order_data['payment_country_id'] = 0;
				$order_data['payment_address_format'] = '';
				$order_data['payment_custom_field'] = [];
			}

			$payment_method_info = $this->session->data['payment_methods'][$this->session->data['payment_method']];

			$order_data['payment_method'] = $payment_method_info['title'];
			$order_data['payment_code'] = $payment_method_info['code'];

			// Shipping Details
			if ($this->cart->hasShipping()) {
				$order_data['shipping_firstname'] = $this->session->data['shipping_address']['firstname'];
				$order_data['shipping_lastname'] = $this->session->data['shipping_address']['lastname'];
				$order_data['shipping_company'] = $this->session->data['shipping_address']['company'];
				$order_data['shipping_address_1'] = $this->session->data['shipping_address']['address_1'];
				$order_data['shipping_address_2'] = $this->session->data['shipping_address']['address_2'];
				$order_data['shipping_city'] = $this->session->data['shipping_address']['city'];
				$order_data['shipping_postcode'] = $this->session->data['shipping_address']['postcode'];
				$order_data['shipping_zone'] = $this->session->data['shipping_address']['zone'];
				$order_data['shipping_zone_id'] = $this->session->data['shipping_address']['zone_id'];
				$order_data['shipping_country'] = $this->session->data['shipping_address']['country'];
				$order_data['shipping_country_id'] = $this->session->data['shipping_address']['country_id'];
				$order_data['shipping_address_format'] = $this->session->data['shipping_address']['address_format'];
				$order_data['shipping_custom_field'] = isset($this->session->data['shipping_address']['custom_field']) ? $this->session->data['shipping_address']['custom_field'] : [];
			} else {
				$order_data['shipping_firstname'] = '';
				$order_data['shipping_lastname'] = '';
				$order_data['shipping_company'] = '';
				$order_data['shipping_address_1'] = '';
				$order_data['shipping_address_2'] = '';
				$order_data['shipping_city'] = '';
				$order_data['shipping_postcode'] = '';
				$order_data['shipping_zone'] = '';
				$order_data['shipping_zone_id'] = 0;
				$order_data['shipping_country'] = '';
				$order_data['shipping_country_id'] = 0;
				$order_data['shipping_address_format'] = '';
				$order_data['shipping_custom_field'] = [];
			}

			if (isset($this->session->data['shipping_method'])) {
				$shipping = explode('.', $this->session->data['shipping_method']);

				$shipping_method_info = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

				$order_data['shipping_method'] = $shipping_method_info['title'];
				$order_data['shipping_code'] = $shipping_method_info['code'];
			} else {
				$order_data['shipping_method'] = '';
				$order_data['shipping_code'] = '';
			}

			// Products
			$order_data['products'] = [];

			foreach ($products as $product) {
				$option_data = [];

				foreach ($product['option'] as $option) {
					$option_data[] = [
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
						'type'                    => $option['type']
					];
				}

				$order_data['products'][] = [
					'product_id'   => $product['product_id'],
					'master_id'    => $product['master_id'],
					'name'         => $product['name'],
					'model'        => $product['model'],
					'option'       => $option_data,
					'subscription' => $product['subscription'],
					'download'     => $product['download'],
					'quantity'     => $product['quantity'],
					'subtract'     => $product['subtract'],
					'price'        => $product['price'],
					'total'        => $product['total'],
					'tax'          => $this->tax->getTax($product['price'], $product['tax_class_id']),
					'reward'       => $product['reward']
				];
			}

			// Gift Voucher
			$order_data['vouchers'] = [];

			if (!empty($this->session->data['vouchers'])) {
				$order_data['vouchers'] = $this->session->data['vouchers'];
			}

			if (isset($this->session->data['comment'])) {
				$order_data['comment'] = $this->session->data['comment'];
			} else {
				$order_data['comment'] = '';
			}

			$total_data = [
				'totals' => $totals,
				'taxes'  => $taxes,
				'total'  => $total
			];

			$order_data = array_merge($order_data, $total_data);

			$order_data['affiliate_id'] = 0;
			$order_data['commission'] = 0;
			$order_data['marketing_id'] = 0;
			$order_data['tracking'] = '';

			if ($this->config->get('config_affiliate_status') && isset($this->session->data['tracking'])) {
				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($this->session->data['tracking']);

				if ($affiliate_info) {
					$order_data['affiliate_id'] = $affiliate_info['customer_id'];
					$order_data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
					$order_data['tracking'] = $this->session->data['tracking'];
				}
			}

			$order_data['language_id'] = $this->config->get('config_language_id');
			$order_data['language_code'] = $this->config->get('config_language');

			$order_data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$order_data['currency_code'] = $this->session->data['currency'];
			$order_data['currency_value'] = $this->currency->getValue($this->session->data['currency']);

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

			if (!isset($this->session->data['order_id'])) {
				$this->session->data['order_id'] = $this->model_checkout_order->addOrder($order_data);
			} else {
				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

				if ($order_info && !$order_info['order_status_id']) {
					$this->model_checkout_order->editOrder($this->session->data['order_id'], $order_data);
				}
			}
		}

		$this->load->model('tool/upload');

		$data['products'] = [];

		foreach ($products as $product) {
			$description = '';

			if ($product['subscription']) {
				$trial_price = $this->currency->format($this->tax->calculate($product['subscription']['trial_price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$trial_cycle = $product['subscription']['trial_cycle'];
				$trial_frequency = $this->language->get('text_' . $product['subscription']['trial_frequency']);
				$trial_duration = $product['subscription']['trial_duration'];

				if ($product['subscription']['trial_status']) {
					$description .= sprintf($this->language->get('text_subscription_trial'), $trial_price, $trial_cycle, $trial_frequency, $trial_duration);
				}

				$price = $this->currency->format($this->tax->calculate($product['subscription']['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$cycle = $product['subscription']['cycle'];
				$frequency = $this->language->get('text_' . $product['subscription']['frequency']);
				$duration = $product['subscription']['duration'];

				if ($duration) {
					$description .= sprintf($this->language->get('text_subscription_duration'), $price, $cycle, $frequency, $duration);
				} else {
					$description .= sprintf($this->language->get('text_subscription_cancel'), $price, $cycle, $frequency);
				}
			}

			$data['products'][] = [
				'cart_id'      => $product['cart_id'],
				'product_id'   => $product['product_id'],
				'name'         => $product['name'],
				'model'        => $product['model'],
				'option'       => $product['option'],
				'subscription' => $description,
				'quantity'     => $product['quantity'],
				'price'        => $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'total'        => $this->currency->format($this->tax->calculate($product['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
				'href'         => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id'])
			];
		}

		// Gift Voucher
		$data['vouchers'] = [];

		$vouchers = $this->model_checkout_cart->getVouchers();

		foreach ($vouchers as $voucher) {
			$data['vouchers'][] = [
				'description' => $voucher['description'],
				'amount'      => $this->currency->format($voucher['amount'], $this->session->data['currency'])
			];
		}

		$data['totals'] = [];

		foreach ($totals as $total) {
			$data['totals'][] = [
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
			];
		}

		// Validate if payment method has been set.
		if (isset($this->session->data['payment_method'])) {
			$code = $this->session->data['payment_method'];
		} else {
			$code = '';
		}

		$extension_info = $this->model_setting_extension->getExtensionByCode('payment', $code);

		if ($status && $extension_info) {
			$data['payment'] = $this->load->controller('extension/' . $extension_info['extension'] . '/payment/' . $extension_info['code']);
		} else {
			$data['payment'] = '';
		}

		return $this->load->view('checkout/confirm', $data);
	}

	public function confirm(): void {
		$this->response->setOutput($this->index());
	}
}
