<?php
class ControllerExtensionPaymentAuthorizeNetAcceptHosted extends Controller {
	public function index() {
		$this->load->language('extension/payment/authorizenet_accept_hosted');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		//Warning!  Authorize.net's parser does not comply with the JSON spec, and is sensitive to element order!
		$request = json_encode(array(
			'getHostedPaymentPageRequest' => array(
				'merchantAuthentication' => array(
					'name' => $this->config->get('payment_authorizenet_accept_hosted_name'),
					'transactionKey' => $this->config->get('payment_authorizenet_accept_hosted_transaction_key')
				),
				'transactionRequest' => array(
					'transactionType' => 'authCaptureTransaction',
					'amount' => $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false),
					'order' => array(
						'invoiceNumber' => $this->session->data['order_id'],
						'description' => html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')
					),
					'customer' => array(
						'email' =>  $order_info['email']
					),
					'billTo' => array(
						'firstName' => html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8'),
						'lastName' => html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8'),
						'company' => html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8'),
						'address' => html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'),
						'city' => html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8'),
						'state' => html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8'),
						'zip' => html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8'),
						'country' => html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8'),
						'phoneNumber' => $order_info['telephone']
					),
					'shipTo' => array(
						'firstName' => html_entity_decode($order_info['shipping_firstname'], ENT_QUOTES, 'UTF-8'),
						'lastName' => html_entity_decode($order_info['shipping_lastname'], ENT_QUOTES, 'UTF-8'),
						'company' => html_entity_decode($order_info['shipping_company'], ENT_QUOTES, 'UTF-8'),
						'address' => html_entity_decode($order_info['shipping_address_1'], ENT_QUOTES, 'UTF-8') . ' ' . html_entity_decode($order_info['payment_address_2'], ENT_QUOTES, 'UTF-8'),
						'city' => html_entity_decode($order_info['shipping_city'], ENT_QUOTES, 'UTF-8'),
						'state' => html_entity_decode($order_info['shipping_zone'], ENT_QUOTES, 'UTF-8'),
						'zip' => html_entity_decode($order_info['shipping_postcode'], ENT_QUOTES, 'UTF-8'),
						'country' => html_entity_decode($order_info['shipping_country'], ENT_QUOTES, 'UTF-8')
					),
					'customerIP' => $this->request->server['REMOTE_ADDR']
				),
				'hostedPaymentSettings' => array(
					'setting' => array(
						array(
							'settingName' => 'hostedPaymentReturnOptions',
							'settingValue' => json_encode(array(
								'showReceipt' => false
							))
						),
						array(
							'settingName' => 'hostedPaymentButtonOptions',
							'settingValue' => json_encode(array(
								'text' => 'Pay'
							))
						),
						array(
							'settingName' => 'hostedPaymentPaymentOptions',
							'settingValue' => json_encode(array(
								'cardCodeRequired' => true,
								'showCreditCard' => $this->config->get('payment_authorizenet_accept_hosted_credit_enabled')? true: false,
								'showBankAccount' => $this->config->get('payment_authorizenet_accept_hosted_ach_enabled')? true: false
							))
						),
						array(
							'settingName' => 'hostedPaymentShippingAddressOptions',
							'settingValue' => json_encode(array(
								'show' => false,
								'required' => false
							))
						),
						array(
							'settingName' => 'hostedPaymentBillingAddressOptions',
							'settingValue' => json_encode(array(
								'show' => false,
								'required' => false
							))
						),
						array(
							'settingName' => 'hostedPaymentIFrameCommunicatorUrl',
							'settingValue' => json_encode(array(
								'url' => $this->url->link('extension/payment/authorizenet_accept_hosted/callback')
							))
						)
					)
				)
			)
		));

		$api_url = 'https://api.authorize.net/xml/v1/request.api';
		if ($this->config->get('payment_authorizenet_accept_hosted_testmode')) $api_url = 'https://apitest.authorize.net/xml/v1/request.api';

		$curl = curl_init($api_url);
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		if ($this->config->get('payment_authorizenet_accept_hosted_debug')) $this->log->write('AUTHORIZE.NET ACCEPT HOSTED SEND: ' . $request);

		$response_raw = curl_exec($curl);
		if ($this->config->get('payment_authorizenet_accept_hosted_debug')) $this->log->write('AUTHORIZE.NET ACCEPT HOSTED RECV: ' . $response_raw);

		if (curl_error($curl)) {
			$data['error'] = $this->language->get('error_no_form');
			$this->log->write('AUTHORIZE.NET ACCEPT HOSTED CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));
		} elseif (!$response_raw) {
			$this->log->write('AUTHORIZE.NET ACCEPT HOSTED CURL ERROR: No response');
		} else {
			if (substr($response_raw, 0, 3) === "\xef\xbb\xbf") {
				$response_raw = substr($response_raw, 3); //result of RFC-violating inclusion of BOM by authorize.net; remove if they fix their bug
			}

			$response = json_decode($response_raw);
			if ($response === null) $data['error'] = $this->language->get('error_no_form');
			else {
				if ($response->messages->resultCode != 'Ok') {
					$data['error'] = $this->language->get('error_no_form');
					$this->log->write('Authorize.net accept hosted returned error while getting payment form: ' .
						$response->messages->resultCode . ': ' . $response->messages->message[0]->code . ' ' . $response->messages->message[0]->text);
				}
				else {
					$data['token'] = $response->token;
				}
			}
		}
		$data['token_responder'] = 'https://accept.authorize.net/payment/payment';
		if ($this->config->get('payment_authorizenet_accept_hosted_testmode')) $data['token_responder'] = 'https://test.authorize.net/payment/payment';

		return $this->load->view('extension/payment/authorizenet_accept_hosted', $data);
	}

	public function callback() {
		$data = array();
		$this->response->setOutput($this->load->view('extension/payment/authorizenet_accept_hosted_callback', $data));
	}

	public function complete() {
		$this->load->model('checkout/order');
		$resp_raw = html_entity_decode($this->request->post['resp']);
		if ($this->config->get('payment_authorizenet_accept_hosted_debug')) $this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION RESP: ' . $resp_raw);
		$resp = json_decode($resp_raw);
		if ($resp === null) {
			$this->log->write('Authorize.NET Accept Hosted completion: invalid response; JSON parsing error ' . json_last_error_msg());
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
			return;
		}

		if (!property_exists($resp, "transId")) {
			$this->log->write('Authorize.NET Accept Hosted completion: response did not include transaction ID');
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
			return;
		}

		if (!in_array($resp->responseCode, ["1", "4"])) { //i.e. if transaction was not approved or held
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
			return;
		}

		//Because authorize.net provides no way to authenticate the transaction we have to verify it by looking it up using the transaction ID the user provided.
		//We can match it with the order ID to prevent replay attacks.
		$request = json_encode(array(
			'getTransactionDetailsRequest' => array(
				'merchantAuthentication' => array(
					'name' => $this->config->get('payment_authorizenet_accept_hosted_name'),
					'transactionKey' => $this->config->get('payment_authorizenet_accept_hosted_transaction_key')
				),
				'transId' => $resp->transId
			)
		));

		$api_url = 'https://api.authorize.net/xml/v1/request.api';
		if ($this->config->get('payment_authorizenet_accept_hosted_testmode')) $api_url = 'https://apitest.authorize.net/xml/v1/request.api';

		$curl = curl_init($api_url);
		curl_setopt($curl, CURLOPT_PORT, 443);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
		curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

		if ($this->config->get('payment_authorizenet_accept_hosted_debug')) $this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION SEND: ' . $request);

		$tx_resp_raw = curl_exec($curl);
		$tx_resp = array();
		if ($this->config->get('payment_authorizenet_accept_hosted_debug')) $this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION RECV: ' . $tx_resp_raw);

		if (curl_error($curl)) {
			$this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION CURL ERROR: ' . curl_errno($curl) . '::' . curl_error($curl));
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
			return;
		} elseif (!$tx_resp_raw) {
			$this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION CURL ERROR: No response');
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
			return;
		} else {
			if (substr($tx_resp_raw, 0, 3) === "\xef\xbb\xbf") {
				$tx_resp_raw = substr($tx_resp_raw, 3); //result of RFC-violating inclusion of BOM by authorize.net; remove if they fix their bug
			}

			$tx_resp = json_decode($tx_resp_raw);
			if ($tx_resp === null || !property_exists($tx_resp, 'transaction') || $tx_resp->transaction === null) {
				$this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION: Got invalid response when looking transaction up in authorize.net');
				$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
				return;
			} else if ($tx_resp->transaction->order->invoiceNumber != $this->session->data['order_id']) {
				$this->log->write('AUTHORIZE.NET ACCEPT HOSTED COMPLETION: Attempted fraud on order ' . $this->session->data['order_id'] . ': incorrect transaction number from user: ' . $resp->transId);
				$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
				return;
			}
		}

		$txn = $tx_resp->transaction;
		if (in_array($txn->responseCode, ["1", "4"])) {
			$message = 'Transaction Status: ' . $txn->transactionStatus . "\n";
			$message = 'Response Code: ' . $txn->responseCode . "\n";
			$message .= 'Response Reason: ' . $txn->responseReasonCode . ': ' . $txn->responseReasonDescription . "\n";
			if (property_exists($txn, 'authCode')) $message .= 'Auth Code: ' . $txn->authCode . "\n";
			$message .= 'Transaction ID: ' . $txn->transId . "\n";
			$message .= 'AVS Response Code: ' . $txn->AVSResponse . "\n";
			if (property_exists($txn, 'cardCodeResponse')) $message .= 'CVV Response Code: ' . $txn->cardCodeResponse . "\n";
			if (property_exists($txn, 'CAVVResponse')) $message .= 'CAVV Response Code: ' . $txn->CAVVResponse . "\n";
			if (property_exists($txn->payment, 'creditCard')) {
				$message .= 'Masked Card Number: ' . $txn->payment->creditCard->cardNumber . "\n";
				$message .= 'Card Account Type: ' . $txn->payment->creditCard->cardType;
			}
			if (property_exists($txn->payment, 'bankAccount')) {
				$message .= 'Masked Routing Number: ' . $txn->payment->bankAccount->routingNumber . "\n";
				$message .= 'Masked Account Number: ' . $txn->payment->bankAccount->accountNumber;
			}

			$this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->config->get('payment_authorizenet_accept_hosted_order_status_id'), $message, true);

			$this->response->redirect($this->url->link('checkout/success', 'language=' . $this->config->get('config_language')));
		} else {
			$this->response->redirect($this->url->link('checkout/failure', 'language=' . $this->config->get('config_language')));
		}
	}
}
