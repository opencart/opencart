<?php
class ControllerExtensionPaymentSagepayDirect extends Controller {
	public function index() {
		$this->load->language('extension/payment/sagepay_direct');

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['cards'] = array();

		$data['cards'][] = array(
			'text' => 'Visa',
			'value' => 'VISA'
		);

		$data['cards'][] = array(
			'text' => 'MasterCard',
			'value' => 'MC'
		);

		$data['cards'][] = array(
			'text' => 'Visa Delta/Debit',
			'value' => 'DELTA'
		);

		$data['cards'][] = array(
			'text' => 'Solo',
			'value' => 'SOLO'
		);

		$data['cards'][] = array(
			'text' => 'Maestro',
			'value' => 'MAESTRO'
		);

		$data['cards'][] = array(
			'text' => 'Visa Electron UK Debit',
			'value' => 'UKE'
		);

		$data['cards'][] = array(
			'text' => 'American Express',
			'value' => 'AMEX'
		);

		$data['cards'][] = array(
			'text' => 'Diners Club',
			'value' => 'DC'
		);

		$data['cards'][] = array(
			'text' => 'Japan Credit Bureau',
			'value' => 'JCB'
		);

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text' => sprintf('%02d', $i),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_valid'] = array();

		for ($i = $today['year'] - 10; $i < $today['year'] + 1; $i++) {
			$data['year_valid'][] = array(
				'text' => sprintf('%02d', $i % 100),
				'value' => sprintf('%04d', $i)
			);
		}

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text' => sprintf('%02d', $i % 100),
				'value' => sprintf('%04d', $i)
			);
		}

		if ($this->config->get('payment_sagepay_direct_card') == '1') {
			$data['sagepay_direct_card'] = true;
		} else {
			$data['sagepay_direct_card'] = false;
		}

		$data['existing_cards'] = array();
		if ($this->customer->isLogged() && $data['sagepay_direct_card']) {
			$this->load->model('extension/payment/sagepay_direct');
			$data['existing_cards'] = $this->model_extension_payment_sagepay_direct->getCards($this->customer->getId());
		}

		return $this->load->view('extension/payment/sagepay_direct', $data);
	}

	public function send() {
		$this->load->language('extension/payment/sagepay_direct');
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/sagepay_direct');
		$this->load->model('account/order');

		$payment_data = array();

		if ($this->config->get('payment_sagepay_direct_test') == 'live') {
			$url = 'https://live.sagepay.com/gateway/service/vspdirect-register.vsp';
			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('payment_sagepay_direct_test') == 'test') {
			$url = 'https://test.sagepay.com/gateway/service/vspdirect-register.vsp';
			$payment_data['VPSProtocol'] = '3.00';
		} elseif ($this->config->get('payment_sagepay_direct_test') == 'sim') {
			$url = 'https://test.sagepay.com/Simulator/VSPDirectGateway.asp';
			$payment_data['VPSProtocol'] = '2.23';
		}

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$payment_data['ReferrerID'] = 'E511AF91-E4A0-42DE-80B0-09C981A3FB61';
		$payment_data['Vendor'] = $this->config->get('payment_sagepay_direct_vendor');
		$payment_data['VendorTxCode'] = $this->session->data['order_id'] . 'SD' . date("YmdHis") . mt_rand(1, 999);
		$payment_data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], false, false);
		$payment_data['Currency'] = $this->session->data['currency'];
		$payment_data['Description'] = substr($this->config->get('config_name'), 0, 100);
		$payment_data['TxType'] = $this->config->get('payment_sagepay_direct_transaction');

		$payment_data['CV2'] = $this->request->post['cc_cvv2'];

		if (isset($this->request->post['Token'])) {
			$payment_data['Token'] = $this->request->post['Token'];
			$payment_data['StoreToken'] = 1;
		} else {
			$payment_data['CardHolder'] = $this->request->post['cc_owner'];
			$payment_data['CardNumber'] = $this->request->post['cc_number'];
			$payment_data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . substr($this->request->post['cc_expire_date_year'], 2);
			$payment_data['CardType'] = $this->request->post['cc_type'];
		}

		if (isset($this->request->post['CreateToken'])) {
			$payment_data['CreateToken'] = $this->request->post['CreateToken'];
			$payment_data['StoreToken'] = 1;
		}

		$payment_data['BillingSurname'] = substr($order_info['payment_lastname'], 0, 20);
		$payment_data['BillingFirstnames'] = substr($order_info['payment_firstname'], 0, 20);
		$payment_data['BillingAddress1'] = substr($order_info['payment_address_1'], 0, 100);

		if ($order_info['payment_address_2']) {
			$payment_data['BillingAddress2'] = $order_info['payment_address_2'];
		}

		$payment_data['BillingCity'] = substr($order_info['payment_city'], 0, 40);
		$payment_data['BillingPostCode'] = substr($order_info['payment_postcode'], 0, 10);
		$payment_data['BillingCountry'] = $order_info['payment_iso_code_2'];

		if ($order_info['payment_iso_code_2'] == 'US') {
			$payment_data['BillingState'] = $order_info['payment_zone_code'];
		}

		$payment_data['BillingPhone'] = substr($order_info['telephone'], 0, 20);

		if ($this->cart->hasShipping()) {
			$payment_data['DeliverySurname'] = substr($order_info['shipping_lastname'], 0, 20);
			$payment_data['DeliveryFirstnames'] = substr($order_info['shipping_firstname'], 0, 20);
			$payment_data['DeliveryAddress1'] = substr($order_info['shipping_address_1'], 0, 100);

			if ($order_info['shipping_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['shipping_address_2'];
			}

			$payment_data['DeliveryCity'] = substr($order_info['shipping_city'], 0, 40);
			$payment_data['DeliveryPostCode'] = substr($order_info['shipping_postcode'], 0, 10);
			$payment_data['DeliveryCountry'] = $order_info['shipping_iso_code_2'];

			if ($order_info['shipping_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['shipping_zone_code'];
			}

			$payment_data['CustomerName'] = substr($order_info['firstname'] . ' ' . $order_info['lastname'], 0, 100);
			$payment_data['DeliveryPhone'] = substr($order_info['telephone'], 0, 20);
		} else {
			$payment_data['DeliveryFirstnames'] = $order_info['payment_firstname'];
			$payment_data['DeliverySurname'] = $order_info['payment_lastname'];
			$payment_data['DeliveryAddress1'] = $order_info['payment_address_1'];

			if ($order_info['payment_address_2']) {
				$payment_data['DeliveryAddress2'] = $order_info['payment_address_2'];
			}

			$payment_data['DeliveryCity'] = $order_info['payment_city'];
			$payment_data['DeliveryPostCode'] = $order_info['payment_postcode'];
			$payment_data['DeliveryCountry'] = $order_info['payment_iso_code_2'];

			if ($order_info['payment_iso_code_2'] == 'US') {
				$payment_data['DeliveryState'] = $order_info['payment_zone_code'];
			}

			$payment_data['DeliveryPhone'] = $order_info['telephone'];
		}

		$order_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
		$cart_rows = 0;
		$str_basket = "";
		foreach ($order_products as $product) {
			$str_basket .=
					":" . str_replace(":", " ", $product['name'] . " " . $product['model']) .
					":" . $product['quantity'] .
					":" . $this->currency->format($product['price'], $order_info['currency_code'], false, false) .
					":" . $this->currency->format($product['tax'], $order_info['currency_code'], false, false) .
					":" . $this->currency->format(($product['price'] + $product['tax']), $order_info['currency_code'], false, false) .
					":" . $this->currency->format(($product['price'] + $product['tax']) * $product['quantity'], $order_info['currency_code'], false, false);
			$cart_rows++;
		}

		$order_totals = $this->model_account_order->getOrderTotals($this->session->data['order_id']);
		foreach ($order_totals as $total) {
			$str_basket .= ":" . str_replace(":", " ", $total['title']) . ":::::" . $this->currency->format($total['value'], $order_info['currency_code'], false, false);
			$cart_rows++;
		}
		$str_basket = $cart_rows . $str_basket;

		$payment_data['Basket'] = $str_basket;

		$payment_data['CustomerEMail'] = substr($order_info['email'], 0, 255);
		$payment_data['Apply3DSecure'] = '0';

		$response_data = $this->model_extension_payment_sagepay_direct->sendCurl($url, $payment_data);

		$json = array();

		if ($response_data['Status'] == '3DAUTH') {
			$json['ACSURL'] = $response_data['ACSURL'];
			$json['MD'] = $response_data['MD'];
			$json['PaReq'] = $response_data['PAReq'];

			$response_data['VPSTxId'] = '';
			$response_data['SecurityKey'] = '';
			$response_data['TxAuthNo'] = '';

			$card_id = '';
			if (!empty($payment_data['CreateToken']) && $this->customer->isLogged()) {
				$card_data = array();
				$card_data['customer_id'] = $this->customer->getId();
				$card_data['Token'] = '';
				$card_data['Last4Digits'] = substr(str_replace(' ', '', $payment_data['CardNumber']), -4, 4);
				$card_data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . '/' . substr($this->request->post['cc_expire_date_year'], 2);
				$card_data['CardType'] = $payment_data['CardType'];
				$card_id = $this->model_extension_payment_sagepay_direct->addCard($card_data);
			} elseif (isset($payment_data['Token'])) {
				$card = $this->model_extension_payment_sagepay_direct->getCard(false, $payment_data['Token']);
				$card_id = $card['card_id'];
			}

			$this->model_extension_payment_sagepay_direct->addOrder($this->session->data['order_id'], $response_data, $payment_data, $card_id);
			$this->model_extension_payment_sagepay_direct->logger('Response data', $response_data);
			$this->model_extension_payment_sagepay_direct->logger('$payment_data', $payment_data);
			$this->model_extension_payment_sagepay_direct->logger('order_id', $this->session->data['order_id']);

			$json['TermUrl'] = $this->url->link('extension/payment/sagepay_direct/callback', '', true);
		} elseif ($response_data['Status'] == 'OK' || $response_data['Status'] == 'AUTHENTICATED' || $response_data['Status'] == 'REGISTERED') {
			$message = '';

			if (isset($response_data['TxAuthNo'])) {
				$message .= 'TxAuthNo: ' . $response_data['TxAuthNo'] . "\n";
			} else {
				$response_data['TxAuthNo'] = '';
			}

			if (isset($response_data['AVSCV2'])) {
				$message .= 'AVSCV2: ' . $response_data['AVSCV2'] . "\n";
			}

			if (isset($response_data['AddressResult'])) {
				$message .= 'AddressResult: ' . $response_data['AddressResult'] . "\n";
			}

			if (isset($response_data['PostCodeResult'])) {
				$message .= 'PostCodeResult: ' . $response_data['PostCodeResult'] . "\n";
			}

			if (isset($response_data['CV2Result'])) {
				$message .= 'CV2Result: ' . $response_data['CV2Result'] . "\n";
			}

			if (isset($response_data['3DSecureStatus'])) {
				$message .= '3DSecureStatus: ' . $response_data['3DSecureStatus'] . "\n";
			}

			if (isset($response_data['CAVV'])) {
				$message .= 'CAVV: ' . $response_data['CAVV'] . "\n";
			}

			$card_id = '';
			if (!empty($payment_data['CreateToken']) && !empty($response_data['Token']) && $this->customer->isLogged()) {
				$card_data = array();
				$card_data['customer_id'] = $this->customer->getId();
				$card_data['Token'] = $response_data['Token'];
				$card_data['Last4Digits'] = substr(str_replace(' ', '', $payment_data['CardNumber']), -4, 4);
				$card_data['ExpiryDate'] = $this->request->post['cc_expire_date_month'] . '/' . substr($this->request->post['cc_expire_date_year'], 2);
				$card_data['CardType'] = $payment_data['CardType'];
				$card_id = $this->model_extension_payment_sagepay_direct->addCard($card_data);
			} elseif (isset($payment_data['Token'])) {
				$card = $this->model_extension_payment_sagepay_direct->getCard(false, $payment_data['Token']);
				$card_id = $card['card_id'];
			}

			$sagepay_direct_order_id = $this->model_extension_payment_sagepay_direct->addOrder($order_info['order_id'], $response_data, $payment_data, $card_id);
			$this->model_extension_payment_sagepay_direct->logger('Response data', $response_data);
			$this->model_extension_payment_sagepay_direct->logger('$payment_data', $payment_data);
			$this->model_extension_payment_sagepay_direct->logger('order_id', $this->session->data['order_id']);

			$this->model_extension_payment_sagepay_direct->addTransaction($sagepay_direct_order_id, $this->config->get('payment_sagepay_direct_transaction'), $order_info);

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_sagepay_direct_order_status_id'), $message, false);

			if ($this->config->get('payment_sagepay_direct_transaction') == 'PAYMENT') {
				$recurring_products = $this->cart->getRecurringProducts();
				//loop through any products that are recurring items
				foreach ($recurring_products as $item) {
					$this->model_extension_payment_sagepay_direct->recurringPayment($item, $payment_data['VendorTxCode']);
				}
			}

			$json['redirect'] = $this->url->link('checkout/success', '', true);
		} else {
			$json['error'] = $response_data['Status'] . ': ' . $response_data['StatusDetail'];
			$this->model_extension_payment_sagepay_direct->logger('Response data', $json['error']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function callback() {
		$this->load->model('extension/payment/sagepay_direct');
		$this->load->language('extension/payment/sagepay_direct');
		$this->load->model('checkout/order');

		if (isset($this->session->data['order_id'])) {
			if ($this->config->get('payment_sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/direct3dcallback.vsp';
			} elseif ($this->config->get('payment_sagepay_direct_test') == 'test') {
				$url = 'https://test.sagepay.com/gateway/service/direct3dcallback.vsp';
			} elseif ($this->config->get('payment_sagepay_direct_test') == 'sim') {
				$url = 'https://test.sagepay.com/Simulator/VSPDirectCallback.asp';
			}

			$response_data = $this->model_extension_payment_sagepay_direct->sendCurl($url, $this->request->post);
			$this->model_extension_payment_sagepay_direct->logger('$response_data', $response_data);

			if ($response_data['Status'] == 'OK' || $response_data['Status'] == 'AUTHENTICATED' || $response_data['Status'] == 'REGISTERED') {
				$message = '';

				if (isset($response_data['TxAuthNo'])) {
					$message .= 'TxAuthNo: ' . $response_data['TxAuthNo'] . "\n";
				} else {
					$response_data['TxAuthNo'] = '';
				}

				if (isset($response_data['AVSCV2'])) {
					$message .= 'AVSCV2: ' . $response_data['AVSCV2'] . "\n";
				}

				if (isset($response_data['AddressResult'])) {
					$message .= 'AddressResult: ' . $response_data['AddressResult'] . "\n";
				}

				if (isset($response_data['PostCodeResult'])) {
					$message .= 'PostCodeResult: ' . $response_data['PostCodeResult'] . "\n";
				}

				if (isset($response_data['CV2Result'])) {
					$message .= 'CV2Result: ' . $response_data['CV2Result'] . "\n";
				}

				if (isset($response_data['3DSecureStatus'])) {
					$message .= '3DSecureStatus: ' . $response_data['3DSecureStatus'] . "\n";
				}

				if (isset($response_data['CAVV'])) {
					$message .= 'CAVV: ' . $response_data['CAVV'] . "\n";
				}

				$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
				$sagepay_order_info = $this->model_extension_payment_sagepay_direct->getOrder($this->session->data['order_id']);

				$this->model_extension_payment_sagepay_direct->logger('$order_info', $order_info);
				$this->model_extension_payment_sagepay_direct->logger('$sagepay_order_info', $sagepay_order_info);

				$this->model_extension_payment_sagepay_direct->updateOrder($order_info, $response_data);
				$this->model_extension_payment_sagepay_direct->addTransaction($sagepay_order_info['sagepay_direct_order_id'], $this->config->get('payment_sagepay_direct_transaction'), $order_info);
				$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_sagepay_direct_order_status_id'), $message, false);

				if (!empty($response_data['Token']) && $this->customer->isLogged()) {
					$this->model_extension_payment_sagepay_direct->updateCard($sagepay_order_info['card_id'], $response_data['Token']);
				} else {
					$this->model_extension_payment_sagepay_direct->deleteCard($sagepay_order_info['card_id']);
				}

				if ($this->config->get('payment_sagepay_direct_transaction') == 'PAYMENT') {
					$recurring_products = $this->cart->getRecurringProducts();
					//loop through any products that are recurring items
					foreach ($recurring_products as $item) {
						$this->model_extension_payment_sagepay_direct->recurringPayment($item, $sagepay_order_info['VendorTxCode']);
					}
				}

				$this->response->redirect($this->url->link('checkout/success', '', true));
			} else {
				$this->session->data['error'] = $response_data['StatusDetail'];

				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			}
		} else {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	}

	public function delete() {

		$this->load->language('account/sagepay_direct_cards');

		$this->load->model('extension/payment/sagepay_direct');

		$card = $this->model_extension_payment_sagepay_direct->getCard(false, $this->request->post['Token']);

		if (!empty($card['token'])) {
			if ($this->config->get('payment_sagepay_direct_test') == 'live') {
				$url = 'https://live.sagepay.com/gateway/service/removetoken.vsp';
			} else {
				$url = 'https://test.sagepay.com/gateway/service/removetoken.vsp';
			}
			$payment_data['VPSProtocol'] = '3.00';
			$payment_data['Vendor'] = $this->config->get('payment_sagepay_direct_vendor');
			$payment_data['TxType'] = 'REMOVETOKEN';
			$payment_data['Token'] = $card['token'];

			$response_data = $this->model_extension_payment_sagepay_direct->sendCurl($url, $payment_data);
			if ($response_data['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_direct->deleteCard($card['card_id']);
				$this->session->data['success'] = $this->language->get('text_success_card');
				$json['success'] = true;
			} else {
				$json['error'] = $this->language->get('text_fail_card');
			}
		} else {
			$json['error'] = $this->language->get('text_fail_card');
		}
		$this->response->setOutput(json_encode($json));
	}

	public function cron() {
		if (isset($this->request->get['token']) && hash_equals($this->config->get('payment_sagepay_direct_cron_job_token'), $this->request->get['token'])) {
			$this->load->model('extension/payment/sagepay_direct');

			$orders = $this->model_extension_payment_sagepay_direct->cronPayment();

			$this->model_extension_payment_sagepay_direct->updateCronJobRunTime();

			$this->model_extension_payment_sagepay_direct->logger('Repeat Orders', $orders);
		}
	}

}