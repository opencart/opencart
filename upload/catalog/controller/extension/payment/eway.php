<?php
class ControllerExtensionPaymentEway extends Controller {
	public function index() {
		$this->load->language('extension/payment/eway');

		$data['payment_type'] = $this->config->get('payment_eway_payment_type');

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text' => sprintf('%02d', $i),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		if ($this->config->get('payment_eway_test')) {
			$data['text_testing'] = $this->language->get('text_testing');
			$data['Endpoint'] = 'Sandbox';
		} else {
			$data['Endpoint'] = 'Production';
		}

		$request = new stdClass();

		$request->Customer = new stdClass();
		$request->Customer->Title = 'Mr.';
		$request->Customer->FirstName = (string)substr($order_info['payment_firstname'], 0, 50);
		$request->Customer->LastName = (string)substr($order_info['payment_lastname'], 0, 50);
		$request->Customer->CompanyName = (string)substr($order_info['payment_company'], 0, 50);
		$request->Customer->Street1 = (string)substr($order_info['payment_address_1'], 0, 50);
		$request->Customer->Street2 = (string)substr($order_info['payment_address_2'], 0, 50);
		$request->Customer->City = (string)substr($order_info['payment_city'], 0, 50);
		$request->Customer->State = (string)substr($order_info['payment_zone'], 0, 50);
		$request->Customer->PostalCode = (string)substr($order_info['payment_postcode'], 0, 30);
		$request->Customer->Country = strtolower($order_info['payment_iso_code_2']);
		$request->Customer->Email = $order_info['email'];
		$request->Customer->Phone = (string)substr($order_info['telephone'], 0, 32);

		$request->ShippingAddress = new stdClass();
		$request->ShippingAddress->FirstName = (string)substr($order_info['shipping_firstname'], 0, 50);
		$request->ShippingAddress->LastName = (string)substr($order_info['shipping_lastname'], 0, 50);
		$request->ShippingAddress->Street1 = (string)substr($order_info['shipping_address_1'], 0, 50);
		$request->ShippingAddress->Street2 = (string)substr($order_info['shipping_address_2'], 0, 50);
		$request->ShippingAddress->City = (string)substr($order_info['shipping_city'], 0, 50);
		$request->ShippingAddress->State = (string)substr($order_info['shipping_zone'], 0, 50);
		$request->ShippingAddress->PostalCode = (string)substr($order_info['shipping_postcode'], 0, 30);
		$request->ShippingAddress->Country = strtolower($order_info['shipping_iso_code_2']);
		$request->ShippingAddress->Email = $order_info['email'];
		$request->ShippingAddress->Phone = (string)substr($order_info['telephone'], 0, 32);
		$request->ShippingAddress->ShippingMethod = "Unknown";

		$invoice_desc = '';
		foreach ($this->cart->getProducts() as $product) {
			$item_price = $this->currency->format($product['price'], $order_info['currency_code'], false, false);
			$item_total = $this->currency->format($product['total'], $order_info['currency_code'], false, false);
			$item = new stdClass();
			$item->SKU = (string)substr($product['product_id'], 0, 12);
			$item->Description = (string)substr($product['name'], 0, 26);
			$item->Quantity = strval($product['quantity']);
			$item->UnitCost = strval($item_price * 100);
			$item->Total = strval($item_total * 100);
			$request->Items[] = $item;
			$invoice_desc .= $product['name'] . ', ';
		}
		$invoice_desc = (string)substr($invoice_desc, 0, -2);
		if (strlen($invoice_desc) > 64) {
			$invoice_desc = (string)substr($invoice_desc, 0, 61) . '...';
		}

		$shipping = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

		if ($shipping > 0) {
			$item = new stdClass();
			$item->SKU = '';
			$item->Description = (string)substr($this->language->get('text_shipping'), 0, 26);
			$item->Quantity = 1;
			$item->UnitCost = $shipping * 100;
			$item->Total = $shipping * 100;
			$request->Items[] = $item;
		}

		$opt1 = new stdClass();
		$opt1->Value = $order_info['order_id'];
		$request->Options = array($opt1);

		$request->Payment = new stdClass();
		$request->Payment->TotalAmount = number_format($amount, 2, '.', '') * 100;
		$request->Payment->InvoiceNumber = $this->session->data['order_id'];
		$request->Payment->InvoiceDescription = $invoice_desc;
		$request->Payment->InvoiceReference = (string)substr($this->config->get('config_name'), 0, 40) . ' - #' . $order_info['order_id'];
		$request->Payment->CurrencyCode = $order_info['currency_code'];

		$request->RedirectUrl = $this->url->link('extension/payment/eway/callback', '', true);
		if ($this->config->get('payment_eway_transaction_method') == 'auth') {
			$request->Method = 'Authorise';
		} else {
			$request->Method = 'ProcessPayment';
		}
		$request->TransactionType = 'Purchase';
		$request->DeviceID = 'opencart-' . VERSION . ' eway-trans-2.1.2';
		$request->CustomerIP = $this->request->server['REMOTE_ADDR'];

		$this->load->model('extension/payment/eway');
		$template = 'eway';
		if ($this->config->get('payment_eway_paymode') == 'iframe') {
			$request->CancelUrl = 'http://www.example.org';
			$request->CustomerReadOnly = true;
			$result = $this->model_extension_payment_eway->getSharedAccessCode($request);

			$template = 'eway_iframe';
		} else {
			$result = $this->model_extension_payment_eway->getAccessCode($request);
		}

		// Check if any error returns
		if (isset($result->Errors)) {
			$error_array = explode(",", $result->Errors);
			$lbl_error = "";
			foreach ($error_array as $error) {
				$error = $this->language->get('text_card_message_' . $error);
				$lbl_error .= $error . "<br />\n";
			}
			$this->log->write('eWAY Payment error: ' . $lbl_error);
		}

		if (isset($lbl_error)) {
			$data['error'] = $lbl_error;
		} else {
			if ($this->config->get('payment_eway_paymode') == 'iframe') {
				$data['callback'] = $this->url->link('extension/payment/eway/callback', 'AccessCode=' . $result->AccessCode, true);
				$data['SharedPaymentUrl'] = $result->SharedPaymentUrl;
			}
			$data['action'] = $result->FormActionURL;
			$data['AccessCode'] = $result->AccessCode;
		}

		return $this->load->view('extension/payment/' . $template, $data);
	}

	public function callback() {
		$this->load->language('extension/payment/eway');

		if (isset($this->request->get['AccessCode']) || isset($this->request->get['amp;AccessCode'])) {

			$this->load->model('extension/payment/eway');

			if (isset($this->request->get['amp;AccessCode'])) {
				$access_code = $this->request->get['amp;AccessCode'];
			} else {
				$access_code = $this->request->get['AccessCode'];
			}

			$result = $this->model_extension_payment_eway->getAccessCodeResult($access_code);

			$is_error = false;

			// Check if any error returns
			if (isset($result->Errors)) {
				$error_array = explode(",", $result->Errors);
				$is_error = true;
				$lbl_error = '';
				foreach ($error_array as $error) {
					$error = $this->language->get('text_card_message_' . $error);
					$lbl_error .= $error . ", ";
				}
				$this->log->write('eWAY error: ' . $lbl_error);
			}
			if (!$is_error) {
				$fraud = false;
				if (!$result->TransactionStatus) {
					$error_array = explode(", ", $result->ResponseMessage);
					$is_error = true;
					$lbl_error = '';
					$log_error = '';
					foreach ($error_array as $error) {
						// Don't show fraud issues to customers
						if (stripos($error, 'F') === false) {
							$lbl_error .= $this->language->get('text_card_message_' . $error);
						} else {
							$fraud = true;
						}
						$log_error .= $this->language->get('text_card_message_' . $error) . ", ";
					}
					$log_error = substr($log_error, 0, -2);
					$this->log->write('eWAY payment failed: ' . $log_error);
				}
			}

			$this->load->model('checkout/order');

			if ($is_error) {
				if ($fraud) {
					$this->response->redirect($this->url->link('checkout/failure', '', true));
				} else {
					$this->session->data['error'] = $this->language->get('text_transaction_failed');
					$this->response->redirect($this->url->link('checkout/checkout', '', true));
				}
			} else {
				$order_id = $result->Options[0]->Value;

				$order_info = $this->model_checkout_order->getOrder($order_id);

				$this->load->model('extension/payment/eway');
				$eway_order_data = array(
					'order_id' => $order_id,
					'transaction_id' => $result->TransactionID,
					'amount' => $result->TotalAmount / 100,
					'currency_code' => $order_info['currency_code'],
					'debug_data' => json_encode($result)
				);

				$error_array = explode(", ", $result->ResponseMessage);
				$log_error = '';
				foreach ($error_array as $error) {
					if (stripos($error, 'F') !== false) {
						$fraud = true;
						$log_error .= $this->language->get('text_card_message_' . $error) . ", ";
					}
				}
				$log_error = substr($log_error, 0, -2);

				$eway_order_id = $this->model_extension_payment_eway->addOrder($eway_order_data);
				$this->model_extension_payment_eway->addTransaction($eway_order_id, $this->config->get('payment_eway_transaction_method'), $result->TransactionID, $order_info);

				if ($fraud) {
					$message = 'Suspected fraud order: ' . $log_error . "\n";
				} else {
					$message = "eWAY Payment accepted\n";
				}
				$message .= 'Transaction ID: ' . $result->TransactionID . "\n";
				$message .= 'Authorisation Code: ' . $result->AuthorisationCode . "\n";
				$message .= 'Card Response Code: ' . $result->ResponseCode . "\n";

				if ($fraud) {
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_eway_order_status_fraud_id'), $message);
				} elseif ($this->config->get('payment_eway_transaction_method') == 'payment') {
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_eway_order_status_id'), $message);
				} else {
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('payment_eway_order_status_auth_id'), $message);
				}

				if (!empty($result->Customer->TokenCustomerID) && $this->customer->isLogged() && !$this->model_checkout_order->checkToken($result->Customer->TokenCustomerID)) {
					$card_data = array();
					$card_data['customer_id'] = $this->customer->getId();
					$card_data['Token'] = $result->Customer->TokenCustomerID;
					$card_data['Last4Digits'] = substr(str_replace(' ', '', $result->Customer->CardDetails->Number), -4, 4);
					$card_data['ExpiryDate'] = $result->Customer->CardDetails->ExpiryMonth . '/' . $result->Customer->CardDetails->ExpiryYear;
					$card_data['CardType'] = '';
					$this->model_extension_payment_eway->addFullCard($this->session->data['order_id'], $card_data);
				}

				$this->response->redirect($this->url->link('checkout/success', '', true));
			}
		}
	}

}