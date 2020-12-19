<?php
class ControllerExtensionPaymentRealexRemote extends Controller {
	public function index() {
		$this->load->language('extension/payment/realex_remote');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_wait'] = $this->language->get('text_wait');
		$data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_name'] = $this->language->get('entry_cc_name');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		$data['entry_cc_issue'] = $this->language->get('entry_cc_issue');
		$data['help_start_date'] = $this->language->get('help_start_date');
		$data['help_issue'] = $this->language->get('help_issue');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$accounts = $this->config->get('payment_realex_remote_account');

		$card_types = array(
			'visa' => $this->language->get('text_card_visa'),
			'mc' => $this->language->get('text_card_mc'),
			'amex' => $this->language->get('text_card_amex'),
			'switch' => $this->language->get('text_card_switch'),
			'laser' => $this->language->get('text_card_laser'),
			'diners' => $this->language->get('text_card_diners'),
		);

		$data['cards'] = array();

		foreach ($accounts as $card => $account) {
			if (isset($account['enabled']) && $account['enabled'] == 1) {
				$data['cards'][] = array(
					'code' => $card,
					'text' => $card_types[$card],
				);
			}
		}

		$data['months'] = array();

		for ($i = 1; $i <= 12; $i++) {
			$data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)),
				'value' => sprintf('%02d', $i)
			);
		}

		$today = getdate();

		$data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%y', mktime(0, 0, 0, 1, 1, $i))
			);
		}

		return $this->load->view('extension/payment/realex_remote', $data);
	}

	public function send() {
		$this->load->model('checkout/order');
		$this->load->model('extension/payment/realex_remote');

		$this->load->language('extension/payment/realex_remote');

		if ($this->request->post['cc_number'] == '') {
			$json['error'] = $this->language->get('error_card_number');
		}

		if ($this->request->post['cc_name'] == '') {
			$json['error'] = $this->language->get('error_card_name');
		}

		if (strlen($this->request->post['cc_cvv2']) != 3 && strlen($this->request->post['cc_cvv2']) != 4) {
			$json['error'] = $this->language->get('error_card_cvv');
		}

		if (isset($json['error'])) {
			$this->response->addHeader('Content-Type: application/json');
			$this->response->setOutput(json_encode($json));
			$this->response->output();
			die();
		}

		if(!isset($this->session->data['order_id'])) {
			return false;
		}

		$order_id = $this->session->data['order_id'];

		$order_ref = $order_id . 'T' . strftime("%Y%m%d%H%M%S") . mt_rand(1, 999);

		$order_info = $this->model_checkout_order->getOrder($order_id);

		$amount = round($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) * 100);
		$currency = $order_info['currency_code'];

		$accounts = $this->config->get('payment_realex_remote_account');

		if (isset($accounts[$this->request->post['cc_type']]['default']) && $accounts[$this->request->post['cc_type']]['default'] == 1) {
			$account = $this->config->get('payment_realex_remote_merchant_id');
		} else {
			$account = $accounts[$this->request->post['cc_type']]['merchant_id'];
		}

		$eci_ref = '';
		$eci = '';
		$cavv = '';
		$xid = '';

		if ($this->config->get('payment_realex_remote_3d') == 1) {
			if ($this->request->post['cc_type'] == 'visa' || $this->request->post['cc_type'] == 'mc' || $this->request->post['cc_type'] == 'amex') {
				$verify_3ds = $this->model_extension_payment_realex_remote->checkEnrollment($account, $amount, $currency, $order_ref);

				$this->model_extension_payment_realex_remote->logger('Verify 3DS result:\r\n' . print_r($verify_3ds, 1));

				// Proceed to 3D secure
				if (isset($verify_3ds->result) && $verify_3ds->result == '00') {
					$enc_data = array(
						'account' => $account,
						'amount' => $amount,
						'currency' => $currency,
						'order_id' => $order_id,
						'order_ref' => $order_ref,
						'cc_number' => $this->request->post['cc_number'],
						'cc_expire' => $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'],
						'cc_name' => $this->request->post['cc_name'],
						'cc_type' => $this->request->post['cc_type'],
						'cc_cvv2' => $this->request->post['cc_cvv2'],
						'cc_issue' => $this->request->post['cc_issue']
					);

					$md = $this->encryption->encrypt($this->config->get('config_encryption'), json_encode($enc_data));

					$json = array();
					$json['ACSURL'] = (string)$verify_3ds->url;
					$json['MD'] = $md;
					$json['PaReq'] = (string)$verify_3ds->pareq;
					$json['TermUrl'] = $this->url->link('extension/payment/realex_remote/acsReturn', '', true);

					$this->response->addHeader('Content-Type: application/json');
					$this->response->setOutput(json_encode($json));
					$this->response->output();
					die();
				}

				// Cardholder Not Enrolled. Shift in liability. ECI = 6
				if (isset($verify_3ds->result) && $verify_3ds->result == '110' && isset($verify_3ds->enrolled) && $verify_3ds->enrolled == 'N') {
					$eci_ref = 1;
					$xid = '';
					$cavv = '';
					if ($this->request->post['cc_type'] == 'mc') {
						$eci = 1;
					} else {
						$eci = 6;
					}
				}

				// Unable to Verify Enrollment. No shift in liability. ECI = 7
				if (isset($verify_3ds->result) && $verify_3ds->result == '110' && isset($verify_3ds->enrolled) && $verify_3ds->enrolled == 'U') {
					if ($this->config->get('payment_realex_remote_liability') != 1) {
						$this->load->language('extension/payment/realex_remote');

						$json['error'] = $this->language->get('error_3d_unable');

						$this->response->addHeader('Content-Type: application/json');
						$this->response->setOutput(json_encode($json));
						$this->response->output();
						die();
					} else {
						$eci_ref = 2;
						$xid = '';
						$cavv = '';
						if ($this->request->post['cc_type'] == 'mc') {
							$eci = 0;
						} else {
							$eci = 7;
						}
					}
				}

				// Invalid response from Enrollment Server. No shift in liability. ECI = 7
				if (isset($verify_3ds->result)  && $verify_3ds->result >= 500 && $verify_3ds->result < 600) {
					if ($this->config->get('payment_realex_remote_liability') != 1) {
						$this->load->language('extension/payment/realex_remote');

						$json['error'] = (string)$verify_3ds->message;

						$this->response->addHeader('Content-Type: application/json');
						$this->response->setOutput(json_encode($json));
						$this->response->output();
						die();
					} else {
						$eci_ref = 3;
						if ($this->request->post['cc_type'] == 'mc') {
							$eci = 0;
						} else {
							$eci = 7;
						}
					}
				}
			}
		}

		$capture_result = $this->model_extension_payment_realex_remote->capturePayment(
			$account,
			$amount,
			$currency,
			$order_id,
			$order_ref,
			$this->request->post['cc_number'],
			$this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'],
			$this->request->post['cc_name'],
			$this->request->post['cc_type'],
			$this->request->post['cc_cvv2'],
			$this->request->post['cc_issue'],
			$eci_ref,
			$eci,
			$cavv,
			$xid
		);

		$this->model_extension_payment_realex_remote->logger('Capture result:\r\n' . print_r($capture_result, 1));

		if ($capture_result->result != '00') {
			$json['error'] = (string)$capture_result->message . ' (' . (int)$capture_result->result . ')';
		} else {
			$json['success'] = $this->url->link('checkout/success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function acsReturn() {
		if (isset($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$this->load->model('extension/payment/realex_remote');

			$post = $this->request->post;

			$md = json_decode($this->encryption->decrypt($this->config->get('config_encryption'), $post['MD']), true);

			$signature_result = $this->model_extension_payment_realex_remote->enrollmentSignature($md['account'], $md['amount'], $md['currency'], $md['order_ref'], $md['cc_number'], $md['cc_expire'], $md['cc_type'], $md['cc_name'], $post['PaRes']);

			$this->model_extension_payment_realex_remote->logger('Signature result:\r\n' . print_r($signature_result, 1));

			if ($signature_result->result == '00' && (strtoupper($signature_result->threedsecure->status) == 'Y' || strtoupper($signature_result->threedsecure->status) == 'A')) {
				if (strtoupper($signature_result->threedsecure->status) == 'Y') {
					$eci_ref = 5;
				} else {
					$eci_ref = 6;
				}

				$eci = (string)$signature_result->threedsecure->eci;
				$cavv = (string)$signature_result->threedsecure->cavv;
				$xid = (string)$signature_result->threedsecure->xid;
			} else {
				if ($md['cc_type'] == 'mc') {
					$eci = 0;
				} else {
					$eci = 7;
				}

				// Enrolled but invalid response from ACS.  No shift in liability. ECI = 7
				if ($signature_result->result == '110' && strtoupper($signature_result->threedsecure->status) == 'Y') {
					$eci_ref = 4;
					$cavv = (string)$signature_result->threedsecure->cavv;
					$xid = (string)$signature_result->threedsecure->xid;
				}

				// Incorrect password entered.  No shift in liability. ECI = 7
				if ($signature_result->result == '00' && strtoupper($signature_result->threedsecure->status) == 'N') {
					$eci_ref = 7;
					$xid = (string)$signature_result->threedsecure->xid;
					$cavv = '';
				}

				// Authentication Unavailable.  No shift in liability. ECI = 7
				if ($signature_result->result == '00' && strtoupper($signature_result->threedsecure->status) == 'U') {
					$eci_ref = 8;
					$xid = (string)$signature_result->threedsecure->xid;
					$cavv = '';
				}

				// Invalid response from ACS.  No shift in liability. ECI = 7
				if (isset($signature_result->result)  && $signature_result->result >= 500 && $signature_result->result < 600) {
					$eci_ref = 9;
					$xid = '';
					$cavv = '';
				}

				if ($this->config->get('payment_realex_remote_liability') != 1) {
					// this is the check for liability shift - if the merchant does not want to accept, redirect to checkout with message
					$this->load->language('extension/payment/realex_remote');

					$message = $this->language->get('error_3d_unsuccessful');
					$message .= '<br /><strong>' . $this->language->get('text_eci') . ':</strong> (' . $eci . ') ' . $this->language->get('text_3d_s' . (int)$eci_ref);
					$message .= '<br /><strong>' . $this->language->get('text_timestamp') . ':</strong> ' . (string)strftime("%Y%m%d%H%M%S");
					$message .= '<br /><strong>' . $this->language->get('text_order_ref') . ':</strong> ' . (string)$md['order_ref'];

					if ($this->config->get('payment_realex_remote_card_data_status') == 1) {
						$message .= '<br /><strong>' . $this->language->get('entry_cc_type') . ':</strong> ' . (string)$md['cc_type'];
						$message .= '<br /><strong>' . $this->language->get('text_last_digits') . ':</strong> ' . (string)substr($md['cc_number'], -4);
						$message .= '<br /><strong>' . $this->language->get('entry_cc_expire_date') . ':</strong> ' . (string)$md['cc_expire'];
						$message .= '<br /><strong>' . $this->language->get('entry_cc_name') . ':</strong> ' . (string)$md['cc_name'];
					}

					$this->model_extension_payment_realex_remote->addHistory($md['order_id'], $this->config->get('payment_realex_remote_order_status_decline_id'), $message);

					$this->session->data['error'] = $this->language->get('error_3d_unsuccessful');

					$this->response->redirect($this->url->link('checkout/checkout', '', true));
					die();
				}
			}

			$capture_result = $this->model_extension_payment_realex_remote->capturePayment(
				$md['account'],
				$md['amount'],
				$md['currency'],
				$md['order_id'],
				$md['order_ref'],
				$md['cc_number'],
				$md['cc_expire'],
				$md['cc_name'],
				$md['cc_type'],
				$md['cc_cvv2'],
				$md['cc_issue'],
				$eci_ref,
				$eci,
				$cavv,
				$xid
			);

			$this->model_extension_payment_realex_remote->logger('Capture result:\r\n' . print_r($capture_result, 1));

			if ($capture_result->result != '00') {
				$this->session->data['error'] = (string)$capture_result->message . ' (' . (int)$capture_result->result . ')';

				$this->response->redirect($this->url->link('checkout/checkout', '', true));
			} else {
				$this->response->redirect($this->url->link('checkout/success'));
			}
		} else {
			$this->response->redirect($this->url->link('account/login', '', true));
		}
	}
}
