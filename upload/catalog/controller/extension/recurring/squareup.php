<?php
class ControllerExtensionRecurringSquareup extends Controller {
	public function index() {
		$this->load->language('extension/recurring/squareup');
		
		$this->load->model('account/recurring');
		$this->load->model('extension/payment/squareup');

		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}
		
		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);
		
		if ($recurring_info) {
			$data['cancel_url'] = html_entity_decode($this->url->link('extension/recurring/squareup/cancel', 'order_recurring_id=' . $order_recurring_id, 'SSL'));

			$data['continue'] = $this->url->link('account/recurring', '', true);    
			
			if ($recurring_info['status'] == ModelExtensionPaymentSquareup::RECURRING_ACTIVE) {
				$data['order_recurring_id'] = $order_recurring_id;
			} else {
				$data['order_recurring_id'] = '';
			}

			return $this->load->view('extension/recurring/squareup', $data);
		}
	}
    
	public function cancel() {
		$this->load->language('extension/recurring/squareup');
		
		$this->load->model('account/recurring');
		$this->load->model('extension/payment/squareup');
		
		if (isset($this->request->get['order_recurring_id'])) {
			$order_recurring_id = $this->request->get['order_recurring_id'];
		} else {
			$order_recurring_id = 0;
		}

		$json = array();
		
		$recurring_info = $this->model_account_recurring->getOrderRecurring($order_recurring_id);

		if ($recurring_info) {
			$this->model_account_recurring->editOrderRecurringStatus($order_recurring_id, ModelExtensionPaymentSquareup::RECURRING_CANCELLED);

			$this->load->model('checkout/order');

			$order_info = $this->model_checkout_order->getOrder($recurring_info['order_id']);

			$this->model_checkout_order->addOrderHistory($recurring_info['order_id'], $order_info['order_status_id'], $this->language->get('text_order_history_cancel'), true);

			$json['success'] = $this->language->get('text_canceled');
		} else {
			$json['error'] = $this->language->get('error_not_found');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// cron-task for recurring payments
	public function recurring() {
		// 1. Set environment to allow long execution
		ignore_user_abort(true); // Keep running even if the client disconnects
		set_time_limit(0);       // Remove the 30-second PHP timeout

		// 2. Prepare the immediate response
		ob_start();
		echo json_encode(['status' => 'success', 'message' => 'Task started in background']);
		
		// 3. Force the server to send headers and close the connection
		$size = ob_get_length();
		header("Content-Length: $size");
		header("Connection: close");
		header("Content-Encoding: none");
		ob_end_flush();
		ob_flush();
		flush();

		// 4. (Optional) If using PHP-FPM, this is the most reliable way
		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		}

		// 5. Now do our cron-task logic here
		$this->load->language('extension/payment/squareup');
		
		$this->load->model('extension/payment/squareup');

		if (!$this->model_extension_payment_squareup->validateCRON()) {
			return;
		}

		$this->load->library('squareup');

		$result = array(
			'payment_success' => array(),
			'payment_error' => array(),
			'payment_fail' => array(),
			'token_update_error' => ''
		);

		// updateToken only affects the live environment, for sandbox it will use the settings from squareup developer console
		$result['token_update_error'] = $this->model_extension_payment_squareup->updateToken();

		$this->load->model('checkout/order');

		$recurrings = $this->model_extension_payment_squareup->nextRecurringPayments();
		foreach ($recurrings as $recurring) {
			$order_id = $recurring['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);

			if (empty($order_info)) {
				// this should never happen, why would an order be deleted when there are still
				// outstanding recurring payments!
				continue;
			}

			if (!$recurring['is_free']) {
				if ($this->config->get('payment_squareup_enable_sandbox')) {
					$access_token = $this->config->get('payment_squareup_sandbox_token');
				} else {
					$access_token = $this->config->get('payment_squareup_access_token');
				}

				$recurring_price = $recurring['recurring_price'];
				list($payment_gateway_amount, $payment_gateway_currency) = $this->model_extension_payment_squareup->getAmountAndCurrency($recurring_price);
				$card_id = $recurring['recurring_reference'];
				$billing_address = $recurring['billing_address'];
				$email = $recurring['email'];
				$phone = $this->squareup->phoneFormat($order_info['telephone'], $order_info['payment_iso_code_2']);
				$statement_description_identifier = $this->language->get('text_order_id').'='.$order_info['order_id'];
				$reference_id = (string)$order_info['order_id'];
				$source_id = $card_id;

				try {
					$customers = $this->squareup->searchCustomers($access_token, $email, $phone);
					$customer_id = isset($customers['customers'][0]['id']) ? $customers['customers'][0]['id'] : '';
					$payment = $this->squareup->createPayment($access_token, $payment_gateway_amount, $payment_gateway_currency, $billing_address, $email, $phone, $source_id, $reference_id, $statement_description_identifier, $customer_id, '');
				} catch (\Squareup\Exception $e) {
					if ($e->isCurlError()) {
						$error = $this->language->get('text_token_issue_customer_error');
					} else if ($e->isAccessTokenRevoked()) {
						// Send reminder e-mail to store admin to refresh the token
						$this->model_extension_payment_squareup->tokenRevokedEmail();
						$error = $this->language->get('text_token_issue_customer_error');
					} else if ($e->isAccessTokenExpired()) {
						// Send reminder e-mail to store admin to refresh the token
						$this->model_extension_payment_squareup->tokenExpiredEmail();
						$error = $this->language->get('text_token_issue_customer_error');
					} else {
						$error = $e->getMessage();
					}
					$result['transaction_error'][] = '[ID: ' . $recurring['order_recurring_id'] . '] - ' . $error;
				}

				if (!empty($payment['payment']['card_details']['status'])) {
					$payment_status = strtolower($payment['payment']['card_details']['status']);
				} else {
					$payment_status = '';
				}

				if ($payment_status) {
					$user_agent = 'CRON JOB';
					if (isset($this->request->server['REMOTE_ADDR'])) {
						$ip = $this->request->server['REMOTE_ADDR'];
					} else {
						$ip = '';
					}
					$merchant_id = $this->config->get('payment_squareup_merchant_id');
					$this->model_extension_payment_squareup->addPayment($payment, $merchant_id, $order_id, $user_agent, $ip);
				}
				$reference = $card_id;
			} else {
				$payment_gateway_amount = 0;
				$recurring_price = 0;
				$card_id = $recurring['recurring_reference'];
				$reference = $card_id;;
				$payment_status = 'captured';
			}

			$success = ($payment_status == 'captured');

			$this->model_extension_payment_squareup->addRecurringTransaction($recurring['order_recurring_id'], $reference, $recurring_price, $success);

			$trial_expired = false;
			$recurring_expired = false;
			$profile_suspended = false;

			$target_currency = $order_info['currency_code'];

			if ($success) {
				$trial_expired = $this->model_extension_payment_squareup->updateRecurringTrial($recurring['order_recurring_id']);

				$recurring_expired = $this->model_extension_payment_squareup->updateRecurringExpired($recurring['order_recurring_id']);

				$result['transaction_success'][$recurring['order_recurring_id']] = $this->currency->format($recurring_price, $target_currency);
			} else {
				// Transaction was not successful. Suspend the recurring profile.
				$profile_suspended = $this->model_extension_payment_squareup->suspendRecurringProfile($recurring['order_recurring_id']);

				$result['transaction_fail'][$recurring['order_recurring_id']] = $this->currency->format($recurring_price, $target_currency);
			}

			$order_status_id = $this->config->get('payment_squareup_status_' . $payment_status);

			if ($order_status_id) {
				if (!$recurring['is_free']) {
					$order_status_comment = $this->language->get('squareup_status_comment_' . $payment_status);
				} else {
					$order_status_comment = '';
				}

				if ($profile_suspended) {
					$order_status_comment .= $this->language->get('text_squareup_profile_suspended');
				}

				if ($trial_expired) {
					$order_status_comment .= $this->language->get('text_squareup_trial_expired');
				}

				if ($recurring_expired) {
					$order_status_comment .= $this->language->get('text_squareup_recurring_expired');
				}

				if ($success) {
					$notify = (bool)$this->config->get('payment_squareup_notify_recurring_success');
				} else {
					$notify = (bool)$this->config->get('payment_squareup_notify_recurring_fail');
				}

				$this->model_checkout_order->addOrderHistory($order_id, $order_status_id, trim($order_status_comment), $notify);
			}
		}

		if ($this->config->get('payment_squareup_cron_email_status')) {
			$this->model_extension_payment_squareup->cronEmail($result);
		}
	}
}