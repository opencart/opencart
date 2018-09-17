<?php
class ControllerExtensionPaymentPPExpress extends Controller {
	public function index() {
		$this->load->language('extension/payment/pp_express');

		$data['payment_pp_express_incontext_disable'] = $this->config->get('payment_pp_express_incontext_disable');

		if ($this->config->get('payment_pp_express_test') == 1) {
			$data['paypal_environment'] = "sandbox";
		} else {
			$data['paypal_environment'] = "production";
		}

		$data['continue'] = $this->url->link('extension/payment/pp_express/checkout');

		unset($this->session->data['paypal']);

		return $this->load->view('extension/payment/pp_express', $data);
	}

	public function eventLoadCheckoutJs($route, &$data) {
		$this->document->addScript('https://www.paypalobjects.com/api/checkout.js');
	}

	public function express() {
		$this->load->model('extension/payment/pp_express');

		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		if ($this->customer->isLogged()) {
			/**
			 * If the customer is already logged in
			 */
			$this->session->data['paypal']['guest'] = false;

			unset($this->session->data['guest']);
		} else {
			if ($this->config->get('config_checkout_guest') && !$this->config->get('config_customer_price') && !$this->cart->hasDownload() && !$this->cart->hasRecurringProducts()) {
				/**
				 * If the guest checkout is allowed (config ok, no login for price and doesn't have downloads)
				 */
				$this->session->data['paypal']['guest'] = true;
			} else {
				/**
				 * If guest checkout disabled or login is required before price or order has downloads
				 *
				 * Send them to the normal checkout flow.
				 */
				unset($this->session->data['guest']);

				$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
			}
		}

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);

		$this->load->model('tool/image');

		if ($this->cart->hasShipping()) {
			$shipping = 2;
		} else {
			$shipping = 1;
		}

		$max_amount = $this->cart->getTotal() * 1.5;
		$max_amount = $this->currency->format($max_amount, $this->session->data['currency'], '', false);

		$data = array(
			'METHOD'             => 'SetExpressCheckout',
			'MAXAMT'             => $max_amount,
			'RETURNURL'          => $this->url->link('extension/payment/pp_express/expressReturn', 'language=' . $this->config->get('config_language')),
			'CANCELURL'          => $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')),
			'REQCONFIRMSHIPPING' => 0,
			'NOSHIPPING'         => $shipping,
			'ALLOWNOTE'          => $this->config->get('payment_pp_express_allow_note'),
			'LOCALECODE'         => 'EN',
			'LANDINGPAGE'        => 'Login',
			'HDRIMG'             => $this->model_tool_image->resize($this->config->get('payment_pp_express_logo'), 750, 90),
			'PAYFLOWCOLOR'       => $this->config->get('payment_pp_express_colour'),
			'CHANNELTYPE'        => 'Merchant'
		);

		if (isset($this->session->data['pp_login']['seamless']['access_token']) && (isset($this->session->data['pp_login']['seamless']['customer_id']) && $this->session->data['pp_login']['seamless']['customer_id'] == $this->customer->getId()) && $this->config->get('module_pp_login_seamless')) {
			$data['IDENTITYACCESSTOKEN'] = $this->session->data['pp_login']['seamless']['access_token'];
		}

		$data = array_merge($data, $this->model_extension_payment_pp_express->paymentRequestInfo());

		$result = $this->model_extension_payment_pp_express->call($data);

		/**
		 * If a failed PayPal setup happens, handle it.
		 */
		if (!isset($result['TOKEN'])) {
			$this->session->data['error'] = $result['L_LONGMESSAGE0'];
			/**
			 * Unable to add error message to user as the session errors/success are not
			 * used on the cart or checkout pages - need to be added?
			 * If PayPal debug log is off then still log error to normal error log.
			 */

			$this->log->write('Unable to create PayPal call: ' . json_encode($result));

			$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
		}

		$this->session->data['paypal']['token'] = $result['TOKEN'];

		if ($this->config->get('payment_pp_express_test') == 1) {
			header('Location: https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
		} else {
			header('Location: https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $result['TOKEN']);
		}
	}

	public function expressReturn() {
		/**
		 * This is the url when PayPal has completed the auth.
		 *
		 * It has no output, instead it sets the data and locates to checkout
		 */
		$this->load->model('extension/payment/pp_express');
		$data = array(
			'METHOD' => 'GetExpressCheckoutDetails',
			'TOKEN'  => $this->session->data['paypal']['token']
		);

		$result = $this->model_extension_payment_pp_express->call($data);
		$this->session->data['paypal']['payerid']   = $result['PAYERID'];
		$this->session->data['paypal']['result']    = $result;

		$this->session->data['comment'] = '';
		if (isset($result['PAYMENTREQUEST_0_NOTETEXT'])) {
			$this->session->data['comment'] = $result['PAYMENTREQUEST_0_NOTETEXT'];
		}

		if ($this->session->data['paypal']['guest'] == true) {

			$this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
			$this->session->data['guest']['firstname'] = trim($result['FIRSTNAME']);
			$this->session->data['guest']['lastname'] = trim($result['LASTNAME']);
			$this->session->data['guest']['email'] = trim($result['EMAIL']);

			if (isset($result['PHONENUM'])) {
				$this->session->data['guest']['telephone'] = $result['PHONENUM'];
			} else {
				$this->session->data['guest']['telephone'] = '';
			}

			$this->session->data['guest']['payment']['firstname'] = trim($result['FIRSTNAME']);
			$this->session->data['guest']['payment']['lastname'] = trim($result['LASTNAME']);

			if (isset($result['BUSINESS'])) {
				$this->session->data['guest']['payment']['company'] = $result['BUSINESS'];
			} else {
				$this->session->data['guest']['payment']['company'] = '';
			}

			$this->session->data['guest']['payment']['company_id'] = '';
			$this->session->data['guest']['payment']['tax_id'] = '';

			if ($this->cart->hasShipping()) {
				$shipping_name = explode(' ', trim($result['PAYMENTREQUEST_0_SHIPTONAME']));
				$shipping_first_name = $shipping_name[0];
				unset($shipping_name[0]);
				$shipping_last_name = implode(' ', $shipping_name);

				$this->session->data['guest']['payment']['address_1'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET'];
				if (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2'])) {
					$this->session->data['guest']['payment']['address_2'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET2'];
				} else {
					$this->session->data['guest']['payment']['address_2'] = '';
				}

				$this->session->data['guest']['payment']['postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];
				$this->session->data['guest']['payment']['city'] = $result['PAYMENTREQUEST_0_SHIPTOCITY'];

				$this->session->data['guest']['shipping']['firstname'] = $shipping_first_name;
				$this->session->data['guest']['shipping']['lastname'] = $shipping_last_name;
				$this->session->data['guest']['shipping']['company'] = '';
				$this->session->data['guest']['shipping']['address_1'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET'];

				if (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2'])) {
					$this->session->data['guest']['shipping']['address_2'] = $result['PAYMENTREQUEST_0_SHIPTOSTREET2'];
				} else {
					$this->session->data['guest']['shipping']['address_2'] = '';
				}

				$this->session->data['guest']['shipping']['postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];
				$this->session->data['guest']['shipping']['city'] = $result['PAYMENTREQUEST_0_SHIPTOCITY'];

				$this->session->data['shipping_postcode'] = $result['PAYMENTREQUEST_0_SHIPTOZIP'];

				$country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "' AND `status` = '1' LIMIT 1")->row;

				if ($country_info) {
					$this->session->data['guest']['shipping']['country_id'] = $country_info['country_id'];
					$this->session->data['guest']['shipping']['country'] = $country_info['name'];
					$this->session->data['guest']['shipping']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['shipping']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['shipping']['address_format'] = $country_info['address_format'];
					$this->session->data['guest']['payment']['country_id'] = $country_info['country_id'];
					$this->session->data['guest']['payment']['country'] = $country_info['name'];
					$this->session->data['guest']['payment']['iso_code_2'] = $country_info['iso_code_2'];
					$this->session->data['guest']['payment']['iso_code_3'] = $country_info['iso_code_3'];
					$this->session->data['guest']['payment']['address_format'] = $country_info['address_format'];
					$this->session->data['shipping_country_id'] = $country_info['country_id'];
				} else {
					$this->session->data['guest']['shipping']['country_id'] = '';
					$this->session->data['guest']['shipping']['country'] = '';
					$this->session->data['guest']['shipping']['iso_code_2'] = '';
					$this->session->data['guest']['shipping']['iso_code_3'] = '';
					$this->session->data['guest']['shipping']['address_format'] = '';
					$this->session->data['guest']['payment']['country_id'] = '';
					$this->session->data['guest']['payment']['country'] = '';
					$this->session->data['guest']['payment']['iso_code_2'] = '';
					$this->session->data['guest']['payment']['iso_code_3'] = '';
					$this->session->data['guest']['payment']['address_format'] = '';
					$this->session->data['shipping_country_id'] = '';
				}

				if (isset($result['PAYMENTREQUEST_0_SHIPTOSTATE'])) {
					$returned_shipping_zone = $result['PAYMENTREQUEST_0_SHIPTOSTATE'];
				} else {
					$returned_shipping_zone = '';
				}

				$zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE (`name` = '" . $this->db->escape($returned_shipping_zone) . "' OR `code` = '" . $this->db->escape($returned_shipping_zone) . "') AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "' LIMIT 1")->row;

				if ($zone_info) {
					$this->session->data['guest']['shipping']['zone'] = $zone_info['name'];
					$this->session->data['guest']['shipping']['zone_code'] = $zone_info['code'];
					$this->session->data['guest']['shipping']['zone_id'] = $zone_info['zone_id'];
					$this->session->data['guest']['payment']['zone'] = $zone_info['name'];
					$this->session->data['guest']['payment']['zone_code'] = $zone_info['code'];
					$this->session->data['guest']['payment']['zone_id'] = $zone_info['zone_id'];
					$this->session->data['shipping_zone_id'] = $zone_info['zone_id'];
				} else {
					$this->session->data['guest']['shipping']['zone'] = '';
					$this->session->data['guest']['shipping']['zone_code'] = '';
					$this->session->data['guest']['shipping']['zone_id'] = '';
					$this->session->data['guest']['payment']['zone'] = '';
					$this->session->data['guest']['payment']['zone_code'] = '';
					$this->session->data['guest']['payment']['zone_id'] = '';
					$this->session->data['shipping_zone_id'] = '';
				}

				$this->session->data['guest']['shipping_address'] = true;
			} else {
				$this->session->data['guest']['payment']['address_1'] = '';
				$this->session->data['guest']['payment']['address_2'] = '';
				$this->session->data['guest']['payment']['postcode'] = '';
				$this->session->data['guest']['payment']['city'] = '';
				$this->session->data['guest']['payment']['country_id'] = '';
				$this->session->data['guest']['payment']['country'] = '';
				$this->session->data['guest']['payment']['iso_code_2'] = '';
				$this->session->data['guest']['payment']['iso_code_3'] = '';
				$this->session->data['guest']['payment']['address_format'] = '';
				$this->session->data['guest']['payment']['zone'] = '';
				$this->session->data['guest']['payment']['zone_code'] = '';
				$this->session->data['guest']['payment']['zone_id'] = '';
				$this->session->data['guest']['shipping_address'] = false;
			}

			$this->session->data['account'] = 'guest';

			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
			unset($this->session->data['payment_method']);
			unset($this->session->data['payment_methods']);
		} else {
			unset($this->session->data['guest']);
			/**
			 * if the user is logged in, add the address to the account and set the ID.
			 */

			if ($this->cart->hasShipping()) {
				$this->load->model('account/address');

				$addresses = $this->model_account_address->getAddresses();

				/**
				 * Compare all of the user addresses and see if there is a match
				 */
				$match = false;
				foreach($addresses as $address) {
					if (trim(strtolower($address['address_1'])) == trim(strtolower($result['PAYMENTREQUEST_0_SHIPTOSTREET'])) && trim(strtolower($address['postcode'])) == trim(strtolower($result['PAYMENTREQUEST_0_SHIPTOZIP']))) {
						$match = true;

						$this->session->data['payment_address_id'] = $address['address_id'];
						$this->session->data['payment_country_id'] = $address['country_id'];
						$this->session->data['payment_zone_id'] = $address['zone_id'];

						$this->session->data['shipping_address_id'] = $address['address_id'];
						$this->session->data['shipping_country_id'] = $address['country_id'];
						$this->session->data['shipping_zone_id'] = $address['zone_id'];
						$this->session->data['shipping_postcode'] = $address['postcode'];

						break;
					}
				}

				/**
				 * If there is no address match add the address and set the info.
				 */
				if ($match == false) {

					$shipping_name = explode(' ', trim($result['PAYMENTREQUEST_0_SHIPTONAME']));
					$shipping_first_name = $shipping_name[0];
					unset($shipping_name[0]);
					$shipping_last_name = implode(' ', $shipping_name);

					$country_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE `iso_code_2` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']) . "' AND `status` = '1' LIMIT 1")->row;
					if (isset($result['PAYMENTREQUEST_0_SHIPTOSTATE'])) {
						$zone_info = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE (`name` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOSTATE']) . "' OR `code` = '" . $this->db->escape($result['PAYMENTREQUEST_0_SHIPTOSTATE']) . "') AND `status` = '1' AND `country_id` = '" . (int)$country_info['country_id'] . "'")->row;
					} else {
						$zone_info = array();
					}

					$address_data = array(
						'firstname'  => $shipping_first_name,
						'lastname'   => $shipping_last_name,
						'company'    => '',
						'company_id' => '',
						'tax_id'     => '',
						'address_1'  => $result['PAYMENTREQUEST_0_SHIPTOSTREET'],
						'address_2'  => (isset($result['PAYMENTREQUEST_0_SHIPTOSTREET2']) ? $result['PAYMENTREQUEST_0_SHIPTOSTREET2'] : ''),
						'postcode'   => $result['PAYMENTREQUEST_0_SHIPTOZIP'],
						'city'       => $result['PAYMENTREQUEST_0_SHIPTOCITY'],
						'zone_id'    => (isset($zone_info['zone_id']) ? $zone_info['zone_id'] : 0),
						'country_id' => (isset($country_info['country_id']) ? $country_info['country_id'] : 0)
					);

					$address_id = $this->model_account_address->addAddress($this->customer->getId(), $address_data);

					$this->session->data['payment_address_id'] = $address_id;
					$this->session->data['payment_country_id'] = $address_data['country_id'];
					$this->session->data['payment_zone_id'] = $address_data['zone_id'];

					$this->session->data['shipping_address_id'] = $address_id;
					$this->session->data['shipping_country_id'] = $address_data['country_id'];
					$this->session->data['shipping_zone_id'] = $address_data['zone_id'];
					$this->session->data['shipping_postcode'] = $address_data['postcode'];
				}
			} else {
				$this->session->data['payment_address_id'] = '';
				$this->session->data['payment_country_id'] = '';
				$this->session->data['payment_zone_id'] = '';
			}
		}

		$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
	}

	public function expressConfirm() {
		$this->load->language('extension/payment/pp_express');
		$this->load->language('checkout/cart');

		$this->load->model('tool/image');

		// Coupon
		if (isset($this->request->post['coupon']) && $this->validateCoupon()) {
			$this->session->data['coupon'] = $this->request->post['coupon'];

			$this->session->data['success'] = $this->language->get('text_coupon');

			$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		// Voucher
		if (isset($this->request->post['voucher']) && $this->validateVoucher()) {
			$this->session->data['voucher'] = $this->request->post['voucher'];

			$this->session->data['success'] = $this->language->get('text_voucher');

			$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		// Reward
		if (isset($this->request->post['reward']) && $this->validateReward()) {
			$this->session->data['reward'] = abs($this->request->post['reward']);

			$this->session->data['success'] = $this->language->get('text_reward');

			$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
		}

		$this->document->setTitle($this->language->get('express_text_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language')),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/payment/pp_express/express', 'language=' . $this->config->get('config_language')),
			'text' => $this->language->get('text_title')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')),
			'text' => $this->language->get('express_text_title')
		);

		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		$data['button_shipping'] = $this->language->get('button_express_shipping');
		$data['button_confirm'] = $this->language->get('button_express_confirm');

		if (isset($this->request->post['next'])) {
			$data['next'] = $this->request->post['next'];
		} else {
			$data['next'] = '';
		}

		$data['action'] = $this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language'));

		$this->load->model('tool/upload');

		$frequencies = array(
			'day'        => $this->language->get('text_day'),
			'week'       => $this->language->get('text_week'),
			'semi_month' => $this->language->get('text_semi_month'),
			'month'      => $this->language->get('text_month'),
			'year'       => $this->language->get('text_year'),
		);

		$products = $this->cart->getProducts();

		foreach ($products as $product) {
			$product_total = 0;

			foreach ($products as $product_2) {
				if ($product_2['product_id'] == $product['product_id']) {
					$product_total += $product_2['quantity'];
				}
			}

			if ($product['minimum'] > $product_total) {
				$data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
			}

			if ($product['image']) {
				$image = $this->model_tool_image->resize($product['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_cart_height'));
			} else {
				$image = '';
			}

			$option_data = array();

			foreach ($product['option'] as $option) {
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

				$option_data[] = array(
					'name'  => $option['name'],
					'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
				);
			}

			// Display prices
			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				$unit_price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));

				$price = $this->currency->format($unit_price, $this->session->data['currency']);
				$total = $this->currency->format($unit_price * $product['quantity'], $this->session->data['currency']);
			} else {
				$price = false;
				$total = false;
			}

			$recurring_description = '';

			if ($product['recurring']) {
				if ($product['recurring']['trial']) {
					$recurring_price = $this->currency->format($this->tax->calculate($product['recurring']['trial_price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$recurring_description = sprintf($this->language->get('text_trial_description'), $recurring_price, $product['recurring']['trial_cycle'], $frequencies[$product['recurring']['trial_frequency']], $product['recurring']['trial_duration']) . ' ';
				}

				$recurring_price = $this->currency->format($this->tax->calculate($product['recurring']['price'] * $product['quantity'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($product['recurring']['duration']) {
					$recurring_description .= sprintf($this->language->get('text_payment_description'), $recurring_price, $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				} else {
					$recurring_description .= sprintf($this->language->get('text_payment_cancel'), $recurring_price, $product['recurring']['cycle'], $frequencies[$product['recurring']['frequency']], $product['recurring']['duration']);
				}
			}

			$data['products'][] = array(
				'cart_id'               => $product['cart_id'],
				'thumb'                 => $image,
				'name'                  => $product['name'],
				'model'                 => $product['model'],
				'option'                => $option_data,
				'quantity'              => $product['quantity'],
				'stock'                 => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
				'reward'                => ($product['reward'] ? sprintf($this->language->get('text_points'), $product['reward']) : ''),
				'price'                 => $price,
				'total'                 => $total,
				'href'                  => $this->url->link('product/product', 'language=' . $this->config->get('config_language') . '&product_id=' . $product['product_id']),
				'remove'                => $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language') . '&remove=' . $product['cart_id']),
				'recurring'             => $product['recurring'],
				'recurring_name'        => (isset($product['recurring']['recurring_name']) ? $product['recurring']['recurring_name'] : ''),
				'recurring_description' => $recurring_description
			);
		}

		$data['vouchers'] = array();

		if ($this->cart->hasShipping()) {

			$data['has_shipping'] = true;
			/**
			 * Shipping services
			 */
			if ($this->customer->isLogged()) {
				$this->load->model('account/address');
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			if (!empty($shipping_address)) {
				// Shipping Methods
				$quote_data = array();

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensions('shipping');

				if (!empty($results)) {
					foreach ($results as $result) {
						if ($this->config->get('shipping_' . $result['code'] . '_status')) {
							$this->load->model('extension/shipping/' . $result['code']);

							$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);

							if ($quote) {
								$quote_data[$result['code']] = array(
									'title'      => $quote['title'],
									'quote'      => $quote['quote'],
									'sort_order' => $quote['sort_order'],
									'error'      => $quote['error']
								);
							}
						}
					}

					if (!empty($quote_data)) {
						$sort_order = array();

						foreach ($quote_data as $key => $value) {
							$sort_order[$key] = $value['sort_order'];
						}

						array_multisort($sort_order, SORT_ASC, $quote_data);

						$this->session->data['shipping_methods'] = $quote_data;
						$data['shipping_methods'] = $quote_data;

						if (!isset($this->session->data['shipping_method'])) {
							//default the shipping to the very first option.
							$key1 = key($quote_data);
							$key2 = key($quote_data[$key1]['quote']);
							$this->session->data['shipping_method'] = $quote_data[$key1]['quote'][$key2];
						}

						$data['code'] = $this->session->data['shipping_method']['code'];
						$data['action_shipping'] = $this->url->link('extension/payment/pp_express/shipping', 'language=' . $this->config->get('config_language'));
					} else {
						unset($this->session->data['shipping_methods']);
						unset($this->session->data['shipping_method']);
						$data['error_no_shipping'] = $this->language->get('error_no_shipping');
					}
				} else {
					unset($this->session->data['shipping_methods']);
					unset($this->session->data['shipping_method']);
					$data['error_no_shipping'] = $this->language->get('error_no_shipping');
				}
			}
		} else {
			$data['has_shipping'] = false;
		}

		// Totals
		$this->load->model('setting/extension');

		$totals = array();
		$taxes = $this->cart->getTaxes();
		$total = 0;

		// Because __call can not keep var references so we put them into an array.
		$total_data = array(
			'totals' => &$totals,
			'taxes'  => &$taxes,
			'total'  => &$total
		);

		// Display prices
		if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);
		}

		$data['totals'] = array();

		foreach ($totals as $total) {
			$data['totals'][] = array(
				'title' => $total['title'],
				'text'  => $this->currency->format($total['value'], $this->session->data['currency']),
			);
		}

		/**
		 * Payment methods
		 */
		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$this->load->model('account/address');
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		$method_data = array();

		$this->load->model('setting/extension');

		$results = $this->model_setting_extension->getExtensions('payment');

		foreach ($results as $result) {
			if ($this->config->get('payment_' . $result['code'] . '_status')) {
				$this->load->model('extension/payment/' . $result['code']);

				$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);

				if ($method) {
					$method_data[$result['code']] = $method;
				}
			}
		}

		$sort_order = array();

		foreach ($method_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}

		array_multisort($sort_order, SORT_ASC, $method_data);

		if (!isset($method_data['pp_express'])) {
			$this->session->data['error_warning'] = $this->language->get('error_unavailable');
			$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
		}

		$this->session->data['payment_methods'] = $method_data;
		$this->session->data['payment_method'] = $method_data['pp_express'];

		$data['action_confirm'] = $this->url->link('extension/payment/pp_express/expressComplete', 'language=' . $this->config->get('config_language'));

		if (isset($this->session->data['error_warning'])) {
			$data['error_warning'] = $this->session->data['error_warning'];
			unset($this->session->data['error_warning']);
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->session->data['attention'])) {
			$data['attention'] = $this->session->data['attention'];
			unset($this->session->data['attention']);
		} else {
			$data['attention'] = '';
		}

		$data['coupon'] = $this->load->controller('extension/total/coupon');
		$data['voucher'] = $this->load->controller('extension/total/voucher');
		$data['reward'] = $this->load->controller('extension/total/reward');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('extension/payment/pp_express_confirm', $data));
	}

	public function expressComplete() {
		$this->load->language('extension/payment/pp_express');
		$redirect = '';

		if ($this->cart->hasShipping()) {
			// Validate if shipping address has been set.
			$this->load->model('account/address');

			if ($this->customer->isLogged() && isset($this->session->data['shipping_address_id'])) {
				$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$shipping_address = $this->session->data['guest']['shipping'];
			}

			if (empty($shipping_address)) {
				$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
			}

			// Validate if shipping method has been set.
			if (!isset($this->session->data['shipping_method'])) {
				$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
			}
		} else {
			unset($this->session->data['shipping_method']);
			unset($this->session->data['shipping_methods']);
		}

		// Validate if payment address has been set.
		$this->load->model('account/address');

		if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
			$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
		} elseif (isset($this->session->data['guest'])) {
			$payment_address = $this->session->data['guest']['payment'];
		}

		// Validate if payment method has been set.
		if (!isset($this->session->data['payment_method'])) {
			$redirect = $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language'));
		}

		// Validate cart has products and has stock.
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$redirect = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));
		}

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
				$redirect = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));

				break;
			}
		}

		if ($redirect == '') {
			$totals = array();
			$taxes = $this->cart->getTaxes();
			$total = 0;

			// Because __call can not keep var references so we put them into an array.
			$total_data = array(
				'totals' => &$totals,
				'taxes'  => &$taxes,
				'total'  => &$total
			);

			$this->load->model('setting/extension');

			$sort_order = array();

			$results = $this->model_setting_extension->getExtensions('total');

			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);

					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}

			$sort_order = array();

			foreach ($totals as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $totals);

			$this->load->language('checkout/checkout');

			$data = array();

			$data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
			$data['store_id'] = $this->config->get('config_store_id');
			$data['store_name'] = $this->config->get('config_name');

			if ($data['store_id']) {
				$data['store_url'] = $this->config->get('config_url');
			} else {
				$data['store_url'] = HTTP_SERVER;
			}

			if ($this->customer->isLogged() && isset($this->session->data['payment_address_id'])) {
				$data['customer_id'] = $this->customer->getId();
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
				$data['firstname'] = $this->customer->getFirstName();
				$data['lastname'] = $this->customer->getLastName();
				$data['email'] = $this->customer->getEmail();
				$data['telephone'] = $this->customer->getTelephone();

				$this->load->model('account/address');

				$payment_address = $this->model_account_address->getAddress($this->session->data['payment_address_id']);
			} elseif (isset($this->session->data['guest'])) {
				$data['customer_id'] = 0;
				$data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
				$data['firstname'] = $this->session->data['guest']['firstname'];
				$data['lastname'] = $this->session->data['guest']['lastname'];
				$data['email'] = $this->session->data['guest']['email'];
				$data['telephone'] = $this->session->data['guest']['telephone'];

				$payment_address = $this->session->data['guest']['payment'];
			}

			$data['payment_firstname'] = isset($payment_address['firstname']) ? $payment_address['firstname'] : '';
			$data['payment_lastname'] = isset($payment_address['lastname']) ? $payment_address['lastname'] : '';
			$data['payment_company'] = isset($payment_address['company']) ? $payment_address['company'] : '';
			$data['payment_company_id'] = isset($payment_address['company_id']) ? $payment_address['company_id'] : '';
			$data['payment_tax_id'] = isset($payment_address['tax_id']) ? $payment_address['tax_id'] : '';
			$data['payment_address_1'] = isset($payment_address['address_1']) ? $payment_address['address_1'] : '';
			$data['payment_address_2'] = isset($payment_address['address_2']) ? $payment_address['address_2'] : '';
			$data['payment_city'] = isset($payment_address['city']) ? $payment_address['city'] : '';
			$data['payment_postcode'] = isset($payment_address['postcode']) ? $payment_address['postcode'] : '';
			$data['payment_zone'] = isset($payment_address['zone']) ? $payment_address['zone'] : '';
			$data['payment_zone_id'] = isset($payment_address['zone_id']) ? $payment_address['zone_id'] : '';
			$data['payment_country'] = isset($payment_address['country']) ? $payment_address['country'] : '';
			$data['payment_country_id'] = isset($payment_address['country_id']) ? $payment_address['country_id'] : '';
			$data['payment_address_format'] = isset($payment_address['address_format']) ? $payment_address['address_format'] : '';

			$data['payment_method'] = '';
			if (isset($this->session->data['payment_method']['title'])) {
				$data['payment_method'] = $this->session->data['payment_method']['title'];
			}

			$data['payment_code'] = '';
			if (isset($this->session->data['payment_method']['code'])) {
				$data['payment_code'] = $this->session->data['payment_method']['code'];
			}

			if ($this->cart->hasShipping()) {
				if ($this->customer->isLogged()) {
					$this->load->model('account/address');

					$shipping_address = $this->model_account_address->getAddress($this->session->data['shipping_address_id']);
				} elseif (isset($this->session->data['guest'])) {
					$shipping_address = $this->session->data['guest']['shipping'];
				}

				$data['shipping_firstname'] = $shipping_address['firstname'];
				$data['shipping_lastname'] = $shipping_address['lastname'];
				$data['shipping_company'] = $shipping_address['company'];
				$data['shipping_address_1'] = $shipping_address['address_1'];
				$data['shipping_address_2'] = $shipping_address['address_2'];
				$data['shipping_city'] = $shipping_address['city'];
				$data['shipping_postcode'] = $shipping_address['postcode'];
				$data['shipping_zone'] = $shipping_address['zone'];
				$data['shipping_zone_id'] = $shipping_address['zone_id'];
				$data['shipping_country'] = $shipping_address['country'];
				$data['shipping_country_id'] = $shipping_address['country_id'];
				$data['shipping_address_format'] = $shipping_address['address_format'];

				$data['shipping_method'] = '';
				if (isset($this->session->data['shipping_method']['title'])) {
					$data['shipping_method'] = $this->session->data['shipping_method']['title'];
				}

				$data['shipping_code'] = '';
				if (isset($this->session->data['shipping_method']['code'])) {
					$data['shipping_code'] = $this->session->data['shipping_method']['code'];
				}
			} else {
				$data['shipping_firstname'] = '';
				$data['shipping_lastname'] = '';
				$data['shipping_company'] = '';
				$data['shipping_address_1'] = '';
				$data['shipping_address_2'] = '';
				$data['shipping_city'] = '';
				$data['shipping_postcode'] = '';
				$data['shipping_zone'] = '';
				$data['shipping_zone_id'] = '';
				$data['shipping_country'] = '';
				$data['shipping_country_id'] = '';
				$data['shipping_address_format'] = '';
				$data['shipping_method'] = '';
				$data['shipping_code'] = '';
			}

			$product_data = array();

			foreach ($this->cart->getProducts() as $product) {
				$option_data = array();

				foreach ($product['option'] as $option) {
					$option_data[] = array(
						'product_option_id'       => $option['product_option_id'],
						'product_option_value_id' => $option['product_option_value_id'],
						'option_id'               => $option['option_id'],
						'option_value_id'         => $option['option_value_id'],
						'name'                    => $option['name'],
						'value'                   => $option['value'],
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

			// Gift Voucher
			$voucher_data = array();

			if (!empty($this->session->data['vouchers'])) {
				foreach ($this->session->data['vouchers'] as $voucher) {
					$voucher_data[] = array(
						'description'      => $voucher['description'],
						'code'             => token(10),
						'to_name'          => $voucher['to_name'],
						'to_email'         => $voucher['to_email'],
						'from_name'        => $voucher['from_name'],
						'from_email'       => $voucher['from_email'],
						'voucher_theme_id' => $voucher['voucher_theme_id'],
						'message'          => $voucher['message'],
						'amount'           => $voucher['amount']
					);
				}
			}

			$data['products'] = $product_data;
			$data['vouchers'] = $voucher_data;
			$data['totals'] = $totals;
			$data['comment'] = $this->session->data['comment'];
			$data['total'] = $total;

			if (isset($this->request->cookie['tracking'])) {
				$data['tracking'] = $this->request->cookie['tracking'];

				$subtotal = $this->cart->getSubTotal();

				// Affiliate
				$this->load->model('account/affiliate');

				$affiliate_info = $this->model_account_affiliate->getAffiliateByTracking($this->request->cookie['tracking']);

				if ($affiliate_info) {
					$data['affiliate_id'] = $affiliate_info['affiliate_id'];
					$data['commission'] = ($subtotal / 100) * $affiliate_info['commission'];
				} else {
					$data['affiliate_id'] = 0;
					$data['commission'] = 0;
				}

				// Marketing
				$this->load->model('marketing/marketing');

				$marketing_info = $this->model_marketing_marketing->getMarketingByCode($this->request->cookie['tracking']);

				if ($marketing_info) {
					$data['marketing_id'] = $marketing_info['marketing_id'];
				} else {
					$data['marketing_id'] = 0;
				}
			} else {
				$data['affiliate_id'] = 0;
				$data['commission'] = 0;
				$data['marketing_id'] = 0;
				$data['tracking'] = '';
			}

			$data['language_id'] = $this->config->get('config_language_id');
			$data['currency_id'] = $this->currency->getId($this->session->data['currency']);
			$data['currency_code'] = $this->session->data['currency'];
			$data['currency_value'] = $this->currency->getValue($this->session->data['currency']);
			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$data['accept_language'] = '';
			}

			$this->load->model('account/custom_field');
			$this->load->model('checkout/order');

			$order_id = $this->model_checkout_order->addOrder($data);
			$this->session->data['order_id'] = $order_id;

			$this->load->model('extension/payment/pp_express');

			$paypal_data = array(
				'TOKEN'                      => $this->session->data['paypal']['token'],
				'PAYERID'                    => $this->session->data['paypal']['payerid'],
				'METHOD'                     => 'DoExpressCheckoutPayment',
				'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('extension/payment/pp_express/ipn', 'language=' . $this->config->get('config_language')),
				'RETURNFMFDETAILS'           => 1
			);

			$paypal_data = array_merge($paypal_data, $this->model_extension_payment_pp_express->paymentRequestInfo());

			$result = $this->model_extension_payment_pp_express->call($paypal_data);

			if ($result['ACK'] == 'Success') {
				//handle order status
				switch($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
					case 'Canceled_Reversal':
						$order_status_id = $this->config->get('payment_pp_express_canceled_reversal_status_id');
						break;
					case 'Completed':
						$order_status_id = $this->config->get('payment_pp_express_completed_status_id');
						break;
					case 'Denied':
						$order_status_id = $this->config->get('payment_pp_express_denied_status_id');
						break;
					case 'Expired':
						$order_status_id = $this->config->get('payment_pp_express_expired_status_id');
						break;
					case 'Failed':
						$order_status_id = $this->config->get('payment_pp_express_failed_status_id');
						break;
					case 'Pending':
						$order_status_id = $this->config->get('payment_pp_express_pending_status_id');
						break;
					case 'Processed':
						$order_status_id = $this->config->get('payment_pp_express_processed_status_id');
						break;
					case 'Refunded':
						$order_status_id = $this->config->get('payment_pp_express_refunded_status_id');
						break;
					case 'Reversed':
						$order_status_id = $this->config->get('payment_pp_express_reversed_status_id');
						break;
					case 'Voided':
						$order_status_id = $this->config->get('payment_pp_express_voided_status_id');
						break;
				}

				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

				//add order to paypal table
				$paypal_order_data = array(
					'order_id'         => $order_id,
					'capture_status'   => ($this->config->get('payment_pp_express_transaction') == 'Sale' ? 'Complete' : 'NotComplete'),
					'currency_code'    => $result['PAYMENTINFO_0_CURRENCYCODE'],
					'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
					'total'            => $result['PAYMENTINFO_0_AMT']
				);

				$paypal_order_id = $this->model_extension_payment_pp_express->addOrder($paypal_order_data);

				//add transaction to paypal transaction table
				$paypal_transaction_data = array(
					'paypal_order_id'       => $paypal_order_id,
					'transaction_id'        => $result['PAYMENTINFO_0_TRANSACTIONID'],
					'parent_id' => '',
					'note'                  => '',
					'msgsubid'              => '',
					'receipt_id'            => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
					'payment_type'          => $result['PAYMENTINFO_0_PAYMENTTYPE'],
					'payment_status'        => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
					'pending_reason'        => $result['PAYMENTINFO_0_PENDINGREASON'],
					'transaction_entity'    => ($this->config->get('payment_pp_express_transaction') == 'Sale' ? 'payment' : 'auth'),
					'amount'                => $result['PAYMENTINFO_0_AMT'],
					'debug_data'            => json_encode($result)
				);

				$this->model_extension_payment_pp_express->addTransaction($paypal_transaction_data);

				$recurring_products = $this->cart->getRecurringProducts();

				//loop through any products that are recurring items
				if ($recurring_products) {
					$this->load->language('extension/payment/pp_express');

					$this->load->model('checkout/recurring');

					$billing_period = array(
						'day'        => 'Day',
						'week'       => 'Week',
						'semi_month' => 'SemiMonth',
						'month'      => 'Month',
						'year'       => 'Year'
					);

					foreach($recurring_products as $item) {
						$data = array(
							'METHOD'             => 'CreateRecurringPaymentsProfile',
							'TOKEN'              => $this->session->data['paypal']['token'],
							'PROFILESTARTDATE'   => gmdate("Y-m-d\TH:i:s\Z", gmmktime(gmdate("H"), gmdate("i")+5, gmdate("s"), gmdate("m"), gmdate("d"), gmdate("y"))),
							'BILLINGPERIOD'      => $billing_period[$item['recurring']['frequency']],
							'BILLINGFREQUENCY'   => $item['recurring']['cycle'],
							'TOTALBILLINGCYCLES' => $item['recurring']['duration'],
							'AMT'                => $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'],
							'CURRENCYCODE'       => $this->session->data['currency']
						);

						//trial information
						if ($item['recurring']['trial']) {
							$data_trial = array(
								'TRIALBILLINGPERIOD'      => $billing_period[$item['recurring']['trial_frequency']],
								'TRIALBILLINGFREQUENCY'   => $item['recurring']['trial_cycle'],
								'TRIALTOTALBILLINGCYCLES' => $item['recurring']['trial_duration'],
								'TRIALAMT'                => $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity']
							);

							$trial_amt = $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
							$trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);

							$data = array_merge($data, $data_trial);
						} else {
							$trial_text = '';
						}

						$recurring_amt = $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false)  * $item['quantity'] . ' ' . $this->session->data['currency'];
						$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

						if ($item['recurring']['duration'] > 0) {
							$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
						}

						//create new recurring and set to pending status as no payment has been made yet.
						$recurring_id = $this->model_checkout_recurring->addRecurring($order_id, $recurring_description, $item);

						$data['PROFILEREFERENCE'] = $recurring_id;
						$data['DESC'] = $recurring_description;

						$result = $this->model_extension_payment_pp_express->call($data);

						if (isset($result['PROFILEID'])) {
							$this->model_checkout_recurring->addReference($recurring_id, $result['PROFILEID']);
						} else {
							// there was an error creating the recurring, need to log and also alert admin / user

						}
					}
				}

				$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));

				if (isset($result['REDIRECTREQUIRED']) && $result['REDIRECTREQUIRED'] == true) {
					//- handle german redirect here
					$this->response->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token=' . $this->session->data['paypal']['token']);
				}
			} else {
				if ($result['L_ERRORCODE0'] == '10486') {
					if (isset($this->session->data['paypal_redirect_count'])) {

						if ($this->session->data['paypal_redirect_count'] == 2) {
							$this->session->data['paypal_redirect_count'] = 0;
							$this->session->data['error'] = $this->language->get('error_too_many_failures');
							$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
						} else {
							$this->session->data['paypal_redirect_count']++;
						}
					} else {
						$this->session->data['paypal_redirect_count'] = 1;
					}

					if ($this->config->get('payment_pp_express_test') == 1) {
						$this->response->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
					} else {
						$this->response->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
					}
				}

				$this->session->data['error_warning'] = $result['L_LONGMESSAGE0'];
				$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
			}
		} else {
			$this->response->redirect($redirect);
		}
	}

	public function checkout() {
		if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
			$this->response->redirect($this->url->link('checkout/cart', 'language=' . $this->config->get('config_language')));
		}

		$this->load->model('extension/payment/pp_express');
		$this->load->model('tool/image');
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$max_amount = $this->cart->getTotal() * 1.5;
		$max_amount = $this->currency->format($max_amount, $this->session->data['currency'], '', false);

		if ($this->cart->hasShipping()) {
			$shipping = 0;

			// PayPal requires some countries to use zone code (not name) to be sent in SHIPTOSTATE
			$ship_to_state_codes = array(
				'30', // Brazil
				'38', // Canada
				'105', // Italy
				'138', // Mexico
				'223', // USA
			);

			if (in_array($order_info['shipping_country_id'], $ship_to_state_codes)) {
				$ship_to_state = $order_info['shipping_zone_code'];
			} else {
				$ship_to_state = $order_info['shipping_zone'];
			}

			$data_shipping = array(
				'PAYMENTREQUEST_0_SHIPTONAME'        => html_entity_decode($order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOSTREET'      => html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOSTREET2'     => html_entity_decode($order_info['shipping_address_2'], ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOCITY'        => html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOSTATE'       => html_entity_decode($ship_to_state, ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOZIP'         => html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8'),
				'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => $order_info['shipping_iso_code_2'],
				'ADDROVERRIDE' 						 => 1,
			);
		} else {
			$shipping = 1;
			$data_shipping = array();
		}

		$data = array(
			'METHOD'             => 'SetExpressCheckout',
			'MAXAMT'             => $max_amount,
			'RETURNURL'          => $this->url->link('extension/payment/pp_express/checkoutReturn', 'language=' . $this->config->get('config_language')),
			'CANCELURL'          => $this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')),
			'REQCONFIRMSHIPPING' => 0,
			'NOSHIPPING'         => $shipping,
			'LOCALECODE'         => 'EN',
			'LANDINGPAGE'        => 'Login',
			'HDRIMG'             => $this->model_tool_image->resize($this->config->get('payment_pp_express_logo'), 750, 90),
			'PAYFLOWCOLOR'       => $this->config->get('payment_pp_express_colour'),
			'CHANNELTYPE'        => 'Merchant',
			'ALLOWNOTE'          => $this->config->get('payment_pp_express_allow_note')
		);

		$data = array_merge($data, $data_shipping);

		if (isset($this->session->data['pp_login']['seamless']['access_token']) && (isset($this->session->data['pp_login']['seamless']['customer_id']) && $this->session->data['pp_login']['seamless']['customer_id'] == $this->customer->getId()) && $this->config->get('module_pp_login_seamless')) {
			$data['IDENTITYACCESSTOKEN'] = $this->session->data['pp_login']['seamless']['access_token'];
		}

		$data = array_merge($data, $this->model_extension_payment_pp_express->paymentRequestInfo());

		$result = $this->model_extension_payment_pp_express->call($data);

		/**
		 * If a failed PayPal setup happens, handle it.
		 */
		if (!isset($result['TOKEN'])) {
			$this->session->data['error'] = "PayPal request failed, please contact the store owner";

			if (isset($result['L_ERRORCODE0'])) {
				$this->session->data['error'] = "[Error code: " . (string)$result['L_ERRORCODE0'] . "]";
			}

			if (isset($result['L_SHORTMESSAGE0'])) {
				$this->session->data['error'] .= " " . (string)$result['L_SHORTMESSAGE0'] . "\r\n";
			}

			if (isset($result['L_LONGMESSAGE0'])) {
				$this->session->data['error'] .= (string)$result['L_LONGMESSAGE0'];
			}

			/**
			 * Unable to add error message to user as the session errors/success are not
			 * used on the cart or checkout pages - need to be added?
			 * If PayPal debug log is off then still log error to normal error log.
			 */
			$this->log->write('Unable to create Paypal session' . json_encode($result));

			$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
		}

		$this->session->data['paypal']['token'] = $result['TOKEN'];

		$json = array("token" => $result['TOKEN']);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function checkoutReturn() {
		$this->load->language('extension/payment/pp_express');

		$this->load->model('extension/payment/pp_express');
		$this->load->model('checkout/order');

		$data = array(
			'METHOD' => 'GetExpressCheckoutDetails',
			'TOKEN'  => $this->session->data['paypal']['token']
		);

		$result = $this->model_extension_payment_pp_express->call($data);

		$this->session->data['paypal']['payerid'] = $result['PAYERID'];
		$this->session->data['paypal']['result'] = $result;

		$order_id = $this->session->data['order_id'];

		$paypal_data = array(
			'TOKEN'                      => $this->session->data['paypal']['token'],
			'PAYERID'                    => $this->session->data['paypal']['payerid'],
			'METHOD'                     => 'DoExpressCheckoutPayment',
			'PAYMENTREQUEST_0_NOTIFYURL' => $this->url->link('extension/payment/pp_express/ipn', 'language=' . $this->config->get('config_language')),
			'RETURNFMFDETAILS'           => 1
		);

		$paypal_data = array_merge($paypal_data, $this->model_extension_payment_pp_express->paymentRequestInfo());

		$result = $this->model_extension_payment_pp_express->call($paypal_data);

		if ($result['ACK'] == 'Success') {
			//handle order status
			switch($result['PAYMENTINFO_0_PAYMENTSTATUS']) {
				case 'Canceled_Reversal':
					$order_status_id = $this->config->get('payment_pp_express_canceled_reversal_status_id');
					break;
				case 'Completed':
					$order_status_id = $this->config->get('payment_pp_express_completed_status_id');
					break;
				case 'Denied':
					$order_status_id = $this->config->get('payment_pp_express_denied_status_id');
					break;
				case 'Expired':
					$order_status_id = $this->config->get('payment_pp_express_expired_status_id');
					break;
				case 'Failed':
					$order_status_id = $this->config->get('payment_pp_express_failed_status_id');
					break;
				case 'Pending':
					$order_status_id = $this->config->get('payment_pp_express_pending_status_id');
					break;
				case 'Processed':
					$order_status_id = $this->config->get('payment_pp_express_processed_status_id');
					break;
				case 'Refunded':
					$order_status_id = $this->config->get('payment_pp_express_refunded_status_id');
					break;
				case 'Reversed':
					$order_status_id = $this->config->get('payment_pp_express_reversed_status_id');
					break;
				case 'Voided':
					$order_status_id = $this->config->get('payment_pp_express_voided_status_id');
					break;
			}

			$this->model_checkout_order->addOrderHistory($order_id, $order_status_id);

			//add order to paypal table
			$paypal_order_data = array(
				'order_id'         => $order_id,
				'capture_status'   => ($this->config->get('payment_pp_express_transaction') == 'Sale' ? 'Complete' : 'NotComplete'),
				'currency_code'    => $result['PAYMENTINFO_0_CURRENCYCODE'],
				'authorization_id' => $result['PAYMENTINFO_0_TRANSACTIONID'],
				'total'            => $result['PAYMENTINFO_0_AMT']
			);

			$paypal_order_id = $this->model_extension_payment_pp_express->addOrder($paypal_order_data);

			//add transaction to paypal transaction table
			$paypal_transaction_data = array(
				'paypal_order_id'       => $paypal_order_id,
				'transaction_id'        => $result['PAYMENTINFO_0_TRANSACTIONID'],
				'parent_id' => '',
				'note'                  => '',
				'msgsubid'              => '',
				'receipt_id'            => (isset($result['PAYMENTINFO_0_RECEIPTID']) ? $result['PAYMENTINFO_0_RECEIPTID'] : ''),
				'payment_type'          => $result['PAYMENTINFO_0_PAYMENTTYPE'],
				'payment_status'        => $result['PAYMENTINFO_0_PAYMENTSTATUS'],
				'pending_reason'        => $result['PAYMENTINFO_0_PENDINGREASON'],
				'transaction_entity'    => ($this->config->get('payment_pp_express_transaction') == 'Sale' ? 'payment' : 'auth'),
				'amount'                => $result['PAYMENTINFO_0_AMT'],
				'debug_data'            => json_encode($result)
			);
			$this->model_extension_payment_pp_express->addTransaction($paypal_transaction_data);

			$recurring_products = $this->cart->getRecurringProducts();

			//loop through any products that are recurring items
			if ($recurring_products) {
				$this->load->model('checkout/recurring');

				$billing_period = array(
					'day'        => 'Day',
					'week'       => 'Week',
					'semi_month' => 'SemiMonth',
					'month'      => 'Month',
					'year'       => 'Year'
				);

				foreach ($recurring_products as $item) {
					$data = array(
						'METHOD'             => 'CreateRecurringPaymentsProfile',
						'TOKEN'              => $this->session->data['paypal']['token'],
						'PROFILESTARTDATE'   => gmdate("Y-m-d\TH:i:s\Z", gmmktime(gmdate('H'), gmdate('i') + 5, gmdate('s'), gmdate('m'), gmdate('d'), gmdate('y'))),
						'BILLINGPERIOD'      => $billing_period[$item['recurring']['frequency']],
						'BILLINGFREQUENCY'   => $item['recurring']['cycle'],
						'TOTALBILLINGCYCLES' => $item['recurring']['duration'],
						'AMT'                => $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'],
						'CURRENCYCODE'       => $this->session->data['currency']
					);

					//trial information
					if ($item['recurring']['trial'] == 1) {
						$data_trial = array(
							'TRIALBILLINGPERIOD'      => $billing_period[$item['recurring']['trial_frequency']],
							'TRIALBILLINGFREQUENCY'   => $item['recurring']['trial_cycle'],
							'TRIALTOTALBILLINGCYCLES' => $item['recurring']['trial_duration'],
							'TRIALAMT'                => $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity']
						);

						$trial_amt = $this->currency->format($this->tax->calculate($item['recurring']['trial_price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false) * $item['quantity'] . ' ' . $this->session->data['currency'];
						$trial_text =  sprintf($this->language->get('text_trial'), $trial_amt, $item['recurring']['trial_cycle'], $item['recurring']['trial_frequency'], $item['recurring']['trial_duration']);

						$data = array_merge($data, $data_trial);
					} else {
						$trial_text = '';
					}

					$recurring_amt = $this->currency->format($this->tax->calculate($item['recurring']['price'], $item['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'], false, false)  * $item['quantity'] . ' ' . $this->session->data['currency'];
					$recurring_description = $trial_text . sprintf($this->language->get('text_recurring'), $recurring_amt, $item['recurring']['cycle'], $item['recurring']['frequency']);

					if ($item['recurring']['duration'] > 0) {
						$recurring_description .= sprintf($this->language->get('text_length'), $item['recurring']['duration']);
					}

					//create new recurring and set to pending status as no payment has been made yet.
					$recurring_id = $this->model_checkout_recurring->addRecurring($order_id, $recurring_description, $item);

					$data['PROFILEREFERENCE'] = $recurring_id;
					$data['DESC'] = $recurring_description;

					$result = $this->model_extension_payment_pp_express->call($data);

					if (isset($result['PROFILEID'])) {
						$this->model_checkout_recurring->editReference($recurring_id, $result['PROFILEID']);
					} else {
						// there was an error creating the recurring, need to log and also alert admin / user

					}
				}
			}

			if (isset($result['REDIRECTREQUIRED']) && $result['REDIRECTREQUIRED'] == true) {
				//- handle german redirect here
				$this->response->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_complete-express-checkout&token=' . $this->session->data['paypal']['token']);
			} else {
				$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
			}
		} else {
			if ($result['L_ERRORCODE0'] == '10486') {
				if (isset($this->session->data['paypal_redirect_count'])) {

					if ($this->session->data['paypal_redirect_count'] == 2) {
						$this->session->data['paypal_redirect_count'] = 0;
						$this->session->data['error'] = $this->language->get('error_too_many_failures');

						$this->response->redirect($this->url->link('checkout/checkout', 'language=' . $this->config->get('config_language')));
					} else {
						$this->session->data['paypal_redirect_count']++;
					}
				} else {
					$this->session->data['paypal_redirect_count'] = 1;
				}

				if ($this->config->get('payment_pp_express_test') == 1) {
					$this->response->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
				} else {
					$this->response->redirect('https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&token=' . $this->session->data['paypal']['token']);
				}
			}

			$this->load->language('extension/payment/pp_express');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home', 'language=' . $this->config->get('config_language'))
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_cart'),
				'href' => $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'))
			);

			$data['heading_title'] = $this->language->get('error_heading_title');

			$data['text_error'] = '<div class="warning">' . $result['L_ERRORCODE0'] . ' : ' . $result['L_LONGMESSAGE0'] . '</div>';

			$data['button_continue'] = $this->language->get('button_continue');

			$data['continue'] = $this->url->link('checkout/cart', 'language=' . $this->config->get('config_language'));

			unset($this->session->data['success']);

			$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}


	public function ipn() {
		$this->load->model('extension/payment/pp_express');
		$this->load->model('account/recurring');

		$request = 'cmd=_notify-validate';

		foreach ($_POST as $key => $value) {
			$request .= '&' . $key . '=' . urlencode(html_entity_decode($value, ENT_QUOTES, 'UTF-8'));
		}

		if ($this->config->get('payment_pp_express_test') == 1) {
			$curl = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
		} else {
			$curl = curl_init('https://www.paypal.com/cgi-bin/webscr');
		}

		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$curl_response = curl_exec($curl);
		$curl_response = trim($curl_response);

		if (!$curl_response) {
			$this->model_extension_payment_pp_express->log(array('error' => curl_error($curl), 'error_no' => curl_errno($curl)), 'Curl failed');
		}

		$this->model_extension_payment_pp_express->log(array('request' => $request, 'response' => $curl_response), 'IPN data');

		if ((string)$curl_response == "VERIFIED")  {
			if (isset($this->request->post['transaction_entity'])) {
				$this->log->write($this->request->post['transaction_entity']);
			}

			if (isset($this->request->post['txn_id'])) {
				$transaction = $this->model_extension_payment_pp_express->getTransactionRow($this->request->post['txn_id']);
			} else {
				$transaction = false;
			}

			if (isset($this->request->post['parent_txn_id'])) {
				$parent_transaction = $this->model_extension_payment_pp_express->getTransactionRow($this->request->post['parent_txn_id']);
			} else {
				$parent_transaction = false;
			}

			if ($transaction) {
				//transaction exists, check for cleared payment or updates etc
				$this->model_extension_payment_pp_express->log('Transaction exists', 'IPN data');

				//if the transaction is pending but the new status is completed
				if ($transaction['payment_status'] != $this->request->post['payment_status']) {
					$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = '" . $this->db->escape($this->request->post['payment_status']) . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");
				} elseif ($transaction['payment_status'] == 'Pending' && ($transaction['pending_reason'] != $this->request->post['pending_reason'])) {
					//payment is still pending but the pending reason has changed, update it.
					$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `pending_reason` = '" . $this->db->escape($this->request->post['pending_reason']) . "' WHERE `transaction_id` = '" . $this->db->escape($transaction['transaction_id']) . "' LIMIT 1");
				}
			} else {
				$this->model_extension_payment_pp_express->log('Transaction does not exist', 'IPN data');

				if ($parent_transaction) {
					//parent transaction exists
					$this->model_extension_payment_pp_express->log('Parent transaction exists', 'IPN data');

					//add new related transaction
					$transaction = array(
						'paypal_order_id'       => $parent_transaction['paypal_order_id'],
						'transaction_id'        => $this->request->post['txn_id'],
						'parent_id'             => $this->request->post['parent_txn_id'],
						'note'                  => '',
						'msgsubid'              => '',
						'receipt_id'            => (isset($this->request->post['receipt_id']) ? $this->request->post['receipt_id'] : ''),
						'payment_type'          => (isset($this->request->post['payment_type']) ? $this->request->post['payment_type'] : ''),
						'payment_status'        => (isset($this->request->post['payment_status']) ? $this->request->post['payment_status'] : ''),
						'pending_reason'        => (isset($this->request->post['pending_reason']) ? $this->request->post['pending_reason'] : ''),
						'amount'                => $this->request->post['mc_gross'],
						'debug_data'            => json_encode($this->request->post),
						'transaction_entity'    => (isset($this->request->post['transaction_entity']) ? $this->request->post['transaction_entity'] : '')
					);

					$this->model_extension_payment_pp_express->addTransaction($transaction);

					/**
					 * If there has been a refund, log this against the parent transaction.
					 */
					if (isset($this->request->post['payment_status']) && $this->request->post['payment_status'] == 'Refunded') {
						if (($this->request->post['mc_gross'] * -1) == $parent_transaction['amount']) {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
						} else {
							$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order_transaction` SET `payment_status` = 'Partially-Refunded' WHERE `transaction_id` = '" . $this->db->escape($parent_transaction['transaction_id']) . "' LIMIT 1");
						}
					}

					/**
					 * If the capture payment is now complete
					 */
					if (isset($this->request->post['auth_status']) && $this->request->post['auth_status'] == 'Completed' && $parent_transaction['payment_status'] == 'Pending') {
						$captured = $this->currency->format($this->model_extension_payment_pp_express->getTotalCaptured($parent_transaction['paypal_order_id']), $this->session->data['currency'], false, false);
						$refunded = $this->currency->format($this->model_extension_payment_pp_express->getRefundedTotal($parent_transaction['paypal_order_id']), $this->session->data['currency'], false, false);
						$remaining = $this->currency->format($parent_transaction['amount'] - $captured + $refunded, $this->session->data['currency'], false, false);

						$this->model_extension_payment_pp_express->log('Captured: ' . $captured, 'IPN data');
						$this->model_extension_payment_pp_express->log('Refunded: ' . $refunded, 'IPN data');
						$this->model_extension_payment_pp_express->log('Remaining: ' . $remaining, 'IPN data');

						if ($remaining > 0.00) {
							$transaction = array(
								'paypal_order_id'       => $parent_transaction['paypal_order_id'],
								'transaction_id'        => '',
								'parent_id' 			=> $this->request->post['parent_txn_id'],
								'note'                  => '',
								'msgsubid'              => '',
								'receipt_id'            => '',
								'payment_type'          => '',
								'payment_status'        => 'Void',
								'pending_reason'        => '',
								'amount'                => '',
								'debug_data'            => 'Voided after capture',
								'transaction_entity'    => 'auth'
							);

							$this->model_extension_payment_pp_express->addTransaction($transaction);
						}

						$this->model_extension_payment_pp_express->updateOrder('Complete', $parent_transaction['order_id']);
					}

				} else {
					//parent transaction doesn't exists, need to investigate?
					$this->model_extension_payment_pp_express->log('Parent transaction not found', 'IPN data');
				}
			}

			/*
			 * Subscription payments
			 *
			 * recurring ID should always exist if its a recurring payment transaction.
			 *
			 * also the reference will match a recurring payment ID
			 */
			if (isset($this->request->post['txn_type'])) {
				$this->model_extension_payment_pp_express->log($this->request->post['txn_type'], 'IPN data');

				//payment
				if ($this->request->post['txn_type'] == 'recurring_payment') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					$this->model_extension_payment_pp_express->log($recurring, 'IPN data');

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `amount` = '" . (float)$this->request->post['amount'] . "', `type` = '1'");

						//as there was a payment the recurring is active, ensure it is set to active (may be been suspended before)
						if ($recurring['status'] != 1) {
							$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 1 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "'");
						}
					}
				}

				//suspend
				if ($this->request->post['txn_type'] == 'recurring_payment_suspended') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '6'");
						$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");
					}
				}

				//suspend due to max failed
				if ($this->request->post['txn_type'] == 'recurring_payment_suspended_due_to_max_failed_payment') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '6'");
						$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 4 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");
					}
				}

				//payment failed
				if ($this->request->post['txn_type'] == 'recurring_payment_failed') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '4'");
					}
				}

				//outstanding payment failed
				if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment_failed') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '8'");
					}
				}

				//outstanding payment
				if ($this->request->post['txn_type'] == 'recurring_payment_outstanding_payment') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `amount` = '" . (float)$this->request->post['amount'] . "', `type` = '2'");

						//as there was a payment the recurring is active, ensure it is set to active (may be been suspended before)
						if ($recurring['status'] != 1) {
							$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 1 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "'");
						}
					}
				}

				//date_added
				if ($this->request->post['txn_type'] == 'recurring_payment_profile_date_added') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '0'");

						if ($recurring['status'] != 1) {
							$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 6 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "'");
						}
					}
				}

				//cancelled
				if ($this->request->post['txn_type'] == 'recurring_payment_profile_cancel') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false && $recurring['status'] != 3) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '5'");
						$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 3 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");
					}
				}

				//skipped
				if ($this->request->post['txn_type'] == 'recurring_payment_skipped') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '3'");
					}
				}

				//expired
				if ($this->request->post['txn_type'] == 'recurring_payment_expired') {
					$recurring = $this->model_account_recurring->getOrderRecurringByReference($this->request->post['recurring_payment_id']);

					if ($recurring != false) {
						$this->db->query("INSERT INTO `" . DB_PREFIX . "order_recurring_transaction` SET `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "', `date_added` = NOW(), `type` = '9'");
						$this->db->query("UPDATE `" . DB_PREFIX . "order_recurring` SET `status` = 5 WHERE `order_recurring_id` = '" . (int)$recurring['order_recurring_id'] . "' LIMIT 1");
					}
				}
			}
		} elseif ((string)$curl_response == "INVALID") {
			$this->model_extension_payment_pp_express->log(array('IPN was invalid'), 'IPN fail');
		} else {
			$this->model_extension_payment_pp_express->log('Response string unknown: ' . (string)$curl_response, 'IPN data');
		}

		header("HTTP/1.1 200 Ok");
	}

	public function shipping() {
		$this->shippingValidate($this->request->post['shipping_method']);

		$this->response->redirect($this->url->link('extension/payment/pp_express/expressConfirm', 'language=' . $this->config->get('config_language')));
	}

	protected function shippingValidate($code) {
		$this->load->language('checkout/cart');
		$this->load->language('extension/payment/pp_express');

		if (empty($code)) {
			$this->session->data['error_warning'] = $this->language->get('error_shipping');
			return false;
		} else {
			$shipping = explode('.', $code);

			if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
				$this->session->data['error_warning'] = $this->language->get('error_shipping');
				return false;
			} else {
				$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];
				$this->session->data['success'] = $this->language->get('text_shipping_updated');
				return true;
			}
		}
	}

	protected function validateCoupon() {
		$this->load->model('extension/total/coupon');

		$coupon_info = $this->model_extension_total_coupon->getCoupon($this->request->post['coupon']);

		if ($coupon_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_coupon');
			return false;
		}
	}

	protected function validateVoucher() {
		$this->load->model('extension/total/coupon');

		$voucher_info = $this->model_extension_total_voucher->getVoucher($this->request->post['voucher']);

		if ($voucher_info) {
			return true;
		} else {
			$this->session->data['error_warning'] = $this->language->get('error_voucher');
			return false;
		}
	}

	protected function validateReward() {
		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		$error = '';

		if (empty($this->request->post['reward'])) {
			$error = $this->language->get('error_reward');
		}

		if ($this->request->post['reward'] > $points) {
			$error = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->request->post['reward'] > $points_total) {
			$error = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$error) {
			return true;
		} else {
			$this->session->data['error_warning'] = $error;
			return false;
		}
	}
}
