<?php
class ControllerExtensionPaymentCardConnect extends Controller {
	public function index() {
		$this->load->language('extension/payment/cardconnect');

		$this->load->model('extension/payment/cardconnect');

		$data['card_types'] = $this->model_extension_payment_cardconnect->getCardTypes();

		$data['months'] = $this->model_extension_payment_cardconnect->getMonths();

		$data['years'] = $this->model_extension_payment_cardconnect->getYears();

		if ($this->customer->isLogged() && $this->config->get('cardconnect_store_cards')) {
			$data['store_cards'] = true;

			$data['cards'] = $this->model_extension_payment_cardconnect->getCards($this->customer->getId());
		} else {
			$data['store_cards'] = false;

			$data['cards'] = array();
		}

		$data['echeck'] = $this->config->get('cardconnect_echeck');

		$data['action'] = $this->url->link('extension/payment/cardconnect/send', '', true);

		return $this->load->view('extension/payment/cardconnect', $data);
	}

	public function send()	{
		$this->load->language('extension/payment/cardconnect');

		$this->load->model('extension/payment/cardconnect');

		$this->model_extension_payment_cardconnect->log('Posting order to CardConnect');

		$json = array();

		$json['error'] = '';

		if ($this->config->get('cardconnect_status')) {
			if ($this->request->server['REQUEST_METHOD'] == 'POST') {
				$error = $this->validate();

				if (!$error) {
					$this->load->model('checkout/order');

					$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

					if ($order_info) {
						$this->model_extension_payment_cardconnect->log('Order ID: ' . $order_info['order_id']);

						$accttype = $account = $expiry = $cvv2 = $profile = $capture = $bankaba = '';

						$existing_card = false;

						if (!isset($this->request->post['method']) || $this->request->post['method'] == 'card') {
							$this->model_extension_payment_cardconnect->log('Method is card');

							if ($this->request->post['card_new'] && isset($this->request->post['card_save']) && $this->config->get('cardconnect_store_cards') && $this->customer->isLogged()) {
								$profile = 'Y';
							} else if (!$this->request->post['card_new'] && $this->customer->isLogged()) {
								$existing_card = $this->model_extension_payment_cardconnect->getCard($this->request->post['card_choice'], $this->customer->getId());

								$profile = $existing_card['profileid'];
							}

							if ($existing_card) {
								$accttype = $existing_card['type'];

								$account = $existing_card['token'];

								$expiry = $existing_card['expiry'];

								$cvv2 = '';
							} else {
								$accttype = $this->request->post['card_type'];

								$account = $this->request->post['card_number'];

								$expiry = $this->request->post['card_expiry_month'] . $this->request->post['card_expiry_year'];

								$cvv2 = $this->request->post['card_cvv2'];
							}
						} else {
							$this->model_extension_payment_cardconnect->log('Method is Echeck');

							$account = $this->request->post['account_number'];

							$bankaba = $this->request->post['routing_number'];
						}

						if ($this->config->get('cardconnect_transaction') == 'payment') {
							$capture = 'Y';

							$type = 'payment';

							$status = 'New';

							$order_status_id = $this->config->get('cardconnect_order_status_id_processing');
						} else {
							$capture = 'N';

							$type = 'auth';

							$status = 'New';

							$order_status_id = $this->config->get('cardconnect_order_status_id_pending');
						}

						$data = array(
							'merchid'    => $this->config->get('payment_cardconnect_merchant_id'),
							'accttype'   => $accttype,
							'account'    => $account,
							'expiry'     => $expiry,
							'cvv2'       => $cvv2,
							'amount'     => round(floatval($order_info['total']), 2, PHP_ROUND_HALF_DOWN),
							'currency'   => $order_info['currency_code'],
							'orderid'    => $order_info['order_id'],
							'name'       => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
							'address'    => $order_info['payment_address_1'],
							'city'       => $order_info['payment_city'],
							'region'     => $order_info['payment_zone'],
							'country'    => $order_info['payment_iso_code_2'],
							'postal'     => $order_info['payment_postcode'],
							'email'      => $order_info['email'],
							'phone'      => $order_info['telephone'],
							'ecomind'    => 'E',
							'tokenize'   => 'Y',
							'profile'    => $profile,
							'capture'    => $capture,
							'bankaba'    => $bankaba,
							'userfields' => array('secret_token' => $this->config->get('cardconnect_token')),
							'frontendid' => '26'
						);

						$data_json = json_encode($data);

						$url = 'https://' . $this->config->get('cardconnect_site') . '.cardconnect.com:' . (($this->config->get('cardconnect_environment') == 'live') ? 8443 : 6443) . '/cardconnect/rest/auth';

						$header = array();

						$header[] = 'Content-type: application/json';
						$header[] = 'Content-length: ' . strlen($data_json);
						$header[] = 'Authorization: Basic ' . base64_encode($this->config->get('cardconnect_api_username') . ':' . $this->config->get('cardconnect_api_password'));

						$this->model_extension_payment_cardconnect->log('Header: ' . print_r($header, true));

						$this->model_extension_payment_cardconnect->log('Post Data: ' . print_r($data, true));

						$this->model_extension_payment_cardconnect->log('URL: ' . $url);

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
						curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						$response_data = curl_exec($ch);
						if (curl_errno($ch)) {
							$this->model_extension_payment_cardconnect->log('cURL error: ' . curl_errno($ch));
						}
						curl_close($ch);

						$response_data = json_decode($response_data, true);

						$this->model_extension_payment_cardconnect->log('Response: ' . print_r($response_data, true));

					 	if (isset($response_data['respstat']) && $response_data['respstat'] == 'A') {
							$this->load->model('checkout/order');

							// if a cheque
							if ($bankaba) {
								$payment_method = 'echeck';

								$type = 'payment';
							} else {
								$payment_method = 'card';
							}

							$this->model_checkout_order->addOrderHistory($order_info['order_id'], $order_status_id);

							$order_info = array_merge($order_info, $response_data);

							$cardconnect_order_id = $this->model_extension_payment_cardconnect->addOrder($order_info, $payment_method);

							$this->model_extension_payment_cardconnect->addTransaction($cardconnect_order_id, $type, $status, $order_info);

							if (isset($response_data['profileid']) && $this->config->get('cardconnect_store_cards') && $this->customer->isLogged()) {
								$this->model_extension_payment_cardconnect->log('Saving card');

								$this->model_extension_payment_cardconnect->addCard($cardconnect_order_id, $this->customer->getId(), $response_data['profileid'], $response_data['token'], $this->request->post['card_type'], $response_data['account'], $this->request->post['card_expiry_month'] . $this->request->post['card_expiry_year']);
							}

							$this->model_extension_payment_cardconnect->log('Success');

							$json['success'] = $this->url->link('checkout/success', '', true);
						} else {
							$this->model_extension_payment_cardconnect->log($response_data['resptext']);

							$json['error']['warning'] = $response_data['resptext'];
						}
					} else {
						$this->model_extension_payment_cardconnect->log('No matching order');

						$json['error']['warning'] = $this->language->get('error_no_order');
					}
				} else {
					$this->model_extension_payment_cardconnect->log('Failed validation');

					$json['error'] = $error;
				}
			} else {
				$this->model_extension_payment_cardconnect->log('No $_POST data');

				$json['error']['warning'] = $this->language->get('error_no_post_data');
			}
		} else {
			$this->model_extension_payment_cardconnect->log('Module not enabled');

			$json['error']['warning'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('extension/payment/cardconnect');

		$this->load->model('extension/payment/cardconnect');

		$this->model_extension_payment_cardconnect->log('Deleting card');

		$json = array();

		if ($this->config->get('cardconnect_status')) {
			if ($this->customer->isLogged()) {
				if (isset($this->request->post['card_choice'])) {
					if ($this->request->post['card_choice']) {
						$card = $this->model_extension_payment_cardconnect->getCard($this->request->post['card_choice'], $this->customer->getId());

						if ($card) {
							$this->model_extension_payment_cardconnect->deleteCard($this->request->post['card_choice'], $this->customer->getId());
						} else {
							$this->model_extension_payment_cardconnect->log('No such card');

							$json['error'] = $this->language->get('error_no_card');
						}
					} else {
						$this->model_extension_payment_cardconnect->log('No card selected');

						$json['error'] = $this->language->get('error_select_card');
					}
				} else {
					$this->model_extension_payment_cardconnect->log('Data missing');

					$json['error'] = $this->language->get('error_data_missing');
				}
			} else {
				$this->model_extension_payment_cardconnect->log('Not logged in');

				$json['error'] = $this->language->get('error_not_logged_in');
			}
		} else {
			$this->model_extension_payment_cardconnect->log('Module not enabled');

			$json['error']['warning'] = $this->language->get('error_not_enabled');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function cron() {
		$this->load->model('extension/payment/cardconnect');

		$this->model_extension_payment_cardconnect->log('Running cron');

		if ($this->config->get('cardconnect_status')) {
			if (isset($this->request->get['token']) && hash_equals($this->config->get('cardconnect_token'), $this->request->get['token'])) {
				$date = date('md', strtotime('yesterday'));

				$responses = $this->model_extension_payment_cardconnect->getSettlementStatuses($this->config->get('payment_cardconnect_merchant_id'), $date);

				foreach($responses as $response) {
					foreach($response['txns'] as $transaction) {
						$this->model_extension_payment_cardconnect->updateTransactionStatusByRetref($transaction['retref'], $transaction['setlstat']);
					}
				}

				$this->model_extension_payment_cardconnect->updateCronRunTime();
			} else {
				$this->model_extension_payment_cardconnect->log('Token does not match.');
			}
		} else {
			$this->model_extension_payment_cardconnect->log('Module not enabled');
		}
	}

	private function validate() {
		$this->load->language('extension/payment/cardconnect');

		$this->load->model('extension/payment/cardconnect');

		$error = array();

		if (!isset($this->request->post['method']) || $this->request->post['method'] == 'card') {
			if ($this->request->post['card_new']) {
				if (!isset($this->request->post['card_number']) || utf8_strlen($this->request->post['card_number']) < 1 || utf8_strlen($this->request->post['card_number']) > 19) {
					$error['card_number'] = $this->language->get('error_card_number');
				}

				if (!isset($this->request->post['card_cvv2']) || utf8_strlen($this->request->post['card_cvv2']) < 1 || utf8_strlen($this->request->post['card_cvv2']) > 4) {
					$error['card_cvv2'] = $this->language->get('error_card_cvv2');
				}
			} else {
				if (isset($this->request->post['card_choice']) && $this->request->post['card_choice']) {
					$card = $this->model_extension_payment_cardconnect->getCard($this->request->post['card_choice'], $this->customer->getId());

					if (!$card) {
						$error['card_choice'] = $this->language->get('error_no_card');
					}
				} else {
					$error['card_choice'] = $this->language->get('error_select_card');
				}
			}
		} else {
			if ($this->config->get('cardconnect_echeck')) {
				if (!isset($this->request->post['account_number']) || utf8_strlen($this->request->post['account_number']) < 1 || utf8_strlen($this->request->post['account_number']) > 19) {
					$error['account_number'] = $this->language->get('error_account_number');
				}

				if (!isset($this->request->post['routing_number']) || utf8_strlen($this->request->post['routing_number']) < 1 || utf8_strlen($this->request->post['routing_number']) > 9) {
					$error['routing_number'] = $this->language->get('error_routing_number');
				}
			} else {
				$error['method'] = $this->language->get('error_no_echeck');
			}
		}

		return $error;
	}
}