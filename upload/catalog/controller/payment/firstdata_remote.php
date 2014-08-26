<?php
class ControllerPaymentFirstdataRemote extends Controller {
	public function index() {
		$this->load->language('payment/firstdata_remote');
		$this->load->model('payment/firstdata_remote');

		$data['text_credit_card'] = $this->language->get('text_credit_card');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_wait'] = $this->language->get('text_wait');

		$data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$data['entry_cc_name'] = $this->language->get('entry_cc_name');
		$data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');

		$data['help_start_date'] = $this->language->get('help_start_date');
		$data['help_issue'] = $this->language->get('help_issue');

		$data['button_confirm'] = $this->language->get('button_confirm');

		if ($this->config->get('firstdata_remote_card_storage') == 1 && $this->customer->isLogged()) {
			$data['card_storage'] = 1;
			$data['stored_cards'] = $this->model_payment_firstdata_remote->getStoredCards();
		} else {
			$data['card_storage'] = 0;
			$data['stored_cards'] = array();
		}

		$data['accepted_cards'] = $this->config->get('firstdata_remote_cards_accepted');
		$data['text_card_accepted'] = $this->language->get('text_card_accepted');
		$data['text_card_type_m'] = $this->language->get('text_card_type_m');
		$data['text_card_type_v'] = $this->language->get('text_card_type_v');
		$data['text_card_type_c'] = $this->language->get('text_card_type_c');
		$data['text_card_type_a'] = $this->language->get('text_card_type_a');
		$data['text_card_type_ma'] = $this->language->get('text_card_type_ma');
		$data['text_card_new'] = $this->language->get('text_card_new');

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

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/firstdata_remote.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/firstdata_remote.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/firstdata_remote.tpl', $data);
		}
	}

	public function send() {
		$this->load->model('checkout/order');
		$this->load->model('payment/firstdata_remote');
		$this->load->language('payment/firstdata_remote');

		$address_codes = array(
			'PPX' => $this->language->get('text_address_ppx'),
			'YYY' => $this->language->get('text_address_yyy'),
			'YNA' => $this->language->get('text_address_yna'),
			'NYZ' => $this->language->get('text_address_nyz'),
			'NNN' => $this->language->get('text_address_nnn'),
			'YPX' => $this->language->get('text_address_ypx'),
			'PYX' => $this->language->get('text_address_pyx'),
			'XXU' => $this->language->get('text_address_xxu')
		);

		$cvv_codes = array(
			'M' => $this->language->get('text_card_code_m'),
			'N' => $this->language->get('text_card_code_n'),
			'P' => $this->language->get('text_card_code_p'),
			'S' => $this->language->get('text_card_code_s'),
			'U' => $this->language->get('text_card_code_u'),
			'X' => $this->language->get('text_card_code_x'),
			'NONE' => $this->language->get('text_card_code_blank')
		);

		if (!isset($this->request->post['cc_choice']) || (isset($this->request->post['cc_choice']) && $this->request->post['cc_choice'] == 'new')) {
			if ($this->request->post['cc_number'] == '') {
				$json['error'] = $this->language->get('error_card_number');
			}

			if ($this->request->post['cc_name'] == '') {
				$json['error'] = $this->language->get('error_card_name');
			}
		}

		if (strlen($this->request->post['cc_cvv2']) != 3 && strlen($this->request->post['cc_cvv2']) != 4) {
			$json['error'] = $this->language->get('error_card_cvv');
		}

		if (empty($json['error'])) {
			$order_id = $this->session->data['order_id'];
			$order_info = $this->model_checkout_order->getOrder($order_id);

			$capture_result = $this->model_payment_firstdata_remote->capturePayment($this->request->post, $order_id);

			$message = '';

			if (isset($capture_result['transaction_result']) && strtoupper($capture_result['transaction_result']) == 'APPROVED') {
				$json['success'] = $this->url->link('checkout/success');

				$message .= $this->language->get('text_result') . $capture_result['transaction_result'] . '<br />';
				$message .= $this->language->get('text_avs') . $address_codes[$capture_result['avs']] . ' (' . $capture_result['avs'] . ')<br />';

				if (!empty($capture_result['ccv'])) {
					$message .= $this->language->get('text_card_code_verify') . $cvv_codes[$capture_result['ccv']] . ' (' . $capture_result['ccv'] . ')<br />';
				}

				$message .= $this->language->get('text_approval_code') . $capture_result['approval_code'] . '<br />';
				$message .= $this->language->get('text_reference_number') . $capture_result['reference_number'] . '<br />';
				$message .= $this->language->get('text_card_brand') . $capture_result['brand'] . '<br />';
				$message .= $this->language->get('text_card_number_ref') . $capture_result['card_number_ref'] . '<br />';
				$message .= $this->language->get('text_response_code') . $capture_result['response_code'] . '<br />';

				$fd_order_id = $this->model_payment_firstdata_remote->addOrder($order_info, $capture_result);

				if ($this->config->get('firstdata_remote_auto_settle') == 1) {
					$this->model_payment_firstdata_remote->addTransaction($fd_order_id, 'payment', $order_info);

					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_remote_order_status_success_settled_id'), $message, false);
				} else {
					$this->model_payment_firstdata_remote->addTransaction($fd_order_id, 'auth');

					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_remote_order_status_success_unsettled_id'), $message, false);
				}
			} else {
				if (isset($capture_result['error']) && !empty($capture_result['error'])) {
					$json['error'] = $capture_result['error'];
				} else {
					$json['error'] = $this->language->get('error_failed');
				}

				if (isset($capture_result['fault']) && !empty($capture_result['fault'])) {
					$message .= $this->language->get('text_fault') . $capture_result['fault'] . '<br />';
				}

				$message .= $this->language->get('text_result') . $capture_result['transaction_result'] . '<br />';
				$message .= $this->language->get('text_error') . $capture_result['error'] . '<br />';
				$message .= $this->language->get('text_card_brand') . $capture_result['brand'] . '<br />';
				$message .= $this->language->get('text_card_number_ref') . $capture_result['card_number_ref'] . '<br />';

				$this->model_payment_firstdata_remote->addHistory($order_id, $this->config->get('firstdata_remote_order_status_decline_id'), $message);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}