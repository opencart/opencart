<?php
class ControllerPaymentGlobalpay extends Controller {
	public function index() {
		$this->load->language('payment/globalpay');

		$data['entry_cc_type'] = $this->language->get('entry_cc_type');

		$data['help_select_card'] = $this->language->get('help_select_card');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($this->config->get('globalpay_live_demo') == 1) {
			$data['action'] = $this->config->get('globalpay_live_url');
		} else {
			$data['action'] = $this->config->get('globalpay_demo_url');
		}

		if ($this->config->get('globalpay_card_select') == 1) {
			$card_types = array(
				'visa' => $this->language->get('text_card_visa'),
				'mc' => $this->language->get('text_card_mc'),
				'amex' => $this->language->get('text_card_amex'),
				'switch' => $this->language->get('text_card_switch'),
				'laser' => $this->language->get('text_card_laser'),
				'diners' => $this->language->get('text_card_diners'),
			);

			$data['cards'] = array();

			$accounts = $this->config->get('globalpay_account');

			foreach ($accounts as $card => $account) {
				if (isset($account['enabled']) && $account['enabled'] == 1) {
					$data['cards'][] = array(
						'type' => $card_types[$card],
						'account' => (isset($account['default']) && $account['default'] == 1 ? $this->config->get('globalpay_merchant_id') : $account['merchant_id']),
					);
				}
			}

			$data['card_select'] = true;
		} else {
			$data['card_select'] = false;
		}

		if ($this->config->get('globalpay_auto_settle') == 0) {
			$data['settle'] = 0;
		} elseif ($this->config->get('globalpay_auto_settle') == 1) {
			$data['settle'] = 1;
		} elseif ($this->config->get('globalpay_auto_settle') == 2) {
			$data['settle'] = 'MULTI';
		}

		$data['tss'] = (int)$this->config->get('globalpay_tss_check');
		$data['merchant_id'] = $this->config->get('globalpay_merchant_id');

		$data['timestamp'] = strftime("%Y%m%d%H%M%S");
		$data['order_id'] = $this->session->data['order_id'] . 'T' . $data['timestamp'] . mt_rand(1, 999);

		$data['amount'] = round($this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false)*100);
		$data['currency'] = $order_info['currency_code'];

		$tmp = $data['timestamp'] . '.' . $data['merchant_id'] . '.' . $data['order_id'] . '.' . $data['amount'] . '.' . $data['currency'];
		$hash = sha1($tmp);
		$tmp = $hash . '.' . $this->config->get('globalpay_secret');
		$data['hash'] = sha1($tmp);

		$data['billing_code'] = filter_var(str_replace('-', '', $order_info['payment_postcode']), FILTER_SANITIZE_NUMBER_INT) . '|' . filter_var(str_replace('-', '', $order_info['payment_address_1']), FILTER_SANITIZE_NUMBER_INT);
		$data['payment_country'] = $order_info['payment_iso_code_2'];

		if ($this->cart->hasShipping()) {
			$data['shipping_code'] = filter_var(str_replace('-', '', $order_info['shipping_postcode']), FILTER_SANITIZE_NUMBER_INT) . '|' . filter_var(str_replace('-', '', $order_info['shipping_address_1']), FILTER_SANITIZE_NUMBER_INT);
			$data['shipping_country'] = $order_info['shipping_iso_code_2'];
		} else {
			$data['shipping_code'] = filter_var(str_replace('-', '', $order_info['payment_postcode']), FILTER_SANITIZE_NUMBER_INT) . '|' . filter_var(str_replace('-', '', $order_info['payment_address_1']), FILTER_SANITIZE_NUMBER_INT);
			$data['shipping_country'] = $order_info['payment_iso_code_2'];
		}

		$data['response_url'] = HTTPS_SERVER . 'index.php?route=payment/globalpay/notify';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/globalpay.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/globalpay.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/globalpay.tpl', $data);
		}
	}

	public function notify() {
		$this->load->model('payment/globalpay');

		$this->model_payment_globalpay->logger(print_r($this->request->post, 1));

		$this->load->language('payment/globalpay');

		$hash = sha1($this->request->post['TIMESTAMP'] . '.' . $this->config->get('globalpay_merchant_id') . '.' . $this->request->post['ORDER_ID'] . '.' . $this->request->post['RESULT'] . '.' . $this->request->post['MESSAGE'] . '.' . $this->request->post['PASREF'] . '.' . $this->request->post['AUTHCODE']);
		$tmp = $hash . '.' . $this->config->get('globalpay_secret');
		$hash = sha1($tmp);

		//Check to see if hashes match or not
		if ($hash != $this->request->post['SHA1HASH']) {
			$data['text_response'] = $this->language->get('text_hash_failed');
			$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
		} else {
			$this->load->model('checkout/order');

			$order_id_parts = explode('T', $this->request->post['ORDER_ID']);
			$order_id = (int)$order_id_parts[0];

			$order_info = $this->model_checkout_order->getOrder($order_id);

			$auto_settle = (int)$this->config->get('globalpay_auto_settle');
			$tss = (int)$this->config->get('globalpay_tss_check');

			$message = '<strong>' . $this->language->get('text_result') . ':</strong> ' . $this->request->post['RESULT'];
			$message .= '<br /><strong>' . $this->language->get('text_message') . ':</strong> ' . $this->request->post['MESSAGE'];

			if (isset($this->request->post['ORDER_ID'])) {
				$message .= '<br /><strong>' . $this->language->get('text_order_ref') . ':</strong> ' . $this->request->post['ORDER_ID'];
			}

			if (isset($this->request->post['CVNRESULT'])) {
				$message .= '<br /><strong>' . $this->language->get('text_cvn_result') . ':</strong> ' . $this->request->post['CVNRESULT'];
			}

			if (isset($this->request->post['AVSPOSTCODERESULT'])) {
				$message .= '<br /><strong>' . $this->language->get('text_avs_postcode') . ':</strong> ' . $this->request->post['AVSPOSTCODERESULT'];
			}

			if (isset($this->request->post['AVSADDRESSRESULT'])) {
				$message .= '<br /><strong>' . $this->language->get('text_avs_address') . ':</strong> ' . $this->request->post['AVSADDRESSRESULT'];
			}

			//3D Secure message
			if (isset($this->request->post['ECI']) && isset($this->request->post['CAVV']) && isset($this->request->post['XID'])) {
				$eci = $this->request->post['ECI'];

				if (($this->request->post['ECI'] == 6 || $this->request->post['ECI'] == 1) && empty($this->request->post['CAVV']) && empty($this->request->post['XID'])) {
					$scenario_id = 1;
				}

				if (($this->request->post['ECI'] == 5 || $this->request->post['ECI'] == 0) && !empty($this->request->post['CAVV']) && !empty($this->request->post['XID'])) {
					$scenario_id = 5;
				}

				if (($this->request->post['ECI'] == 6 || $this->request->post['ECI'] == 1) && !empty($this->request->post['CAVV']) && !empty($this->request->post['XID'])) {
					$scenario_id = 6;
				}

				if (isset($scenario_id)) {
					$scenario_message = $this->language->get('text_3d_s' . $scenario_id);
				} else {
					if (isset($this->request->post['CARDTYPE'])) {
						if ($this->request->post['CARDTYPE'] == 'VISA') {
							$eci = 7;
						} else {
							$eci = 2;
						}
					}

					$scenario_message = $this->language->get('text_3d_liability');
				}

				$message .= '<br /><strong>' . $this->language->get('text_eci') . ':</strong> (' . $eci . ') ' . $scenario_message;
			}

			if ($tss == 1 && isset($this->request->post['TSS'])) {
				$message .= '<br /><strong>' . $this->language->get('text_tss') . ':</strong> ' . $this->request->post['TSS'];
			}

			if (isset($this->request->post['TIMESTAMP'])) {
				$message .= '<br /><strong>' . $this->language->get('text_timestamp') . ':</strong> ' . $this->request->post['TIMESTAMP'];
			}

			if (isset($this->request->post['CARDDIGITS'])) {
				$message .= '<br /><strong>' . $this->language->get('text_card_digits') . ':</strong> ' . $this->request->post['CARDDIGITS'];
			}

			if (isset($this->request->post['CARDTYPE'])) {
				$message .= '<br /><strong>' . $this->language->get('text_card_type') . ':</strong> ' . $this->request->post['CARDTYPE'];
			}

			if (isset($this->request->post['EXPDATE'])) {
				$message .= '<br /><strong>' . $this->language->get('text_card_exp') . ':</strong> ' . $this->request->post['EXPDATE'];
			}

			if (isset($this->request->post['CARDNAME'])) {
				$message .= '<br /><strong>' . $this->language->get('text_card_name') . ':</strong> ' . $this->request->post['CARDNAME'];
			}

			if (isset($this->request->post['DCCAUTHCARDHOLDERAMOUNT']) && isset($this->request->post['DCCAUTHRATE'])) {
				$message .= '<br /><strong>DCCAUTHCARDHOLDERAMOUNT:</strong> ' . $this->request->post['DCCAUTHCARDHOLDERAMOUNT'];
				$message .= '<br /><strong>DCCAUTHRATE:</strong> ' . $this->request->post['DCCAUTHRATE'];
				$message .= '<br /><strong>DCCAUTHCARDHOLDERCURRENCY:</strong> ' . $this->request->post['DCCAUTHCARDHOLDERCURRENCY'];
				$message .= '<br /><strong>DCCAUTHMERCHANTCURRENCY:</strong> ' . $this->request->post['DCCAUTHMERCHANTCURRENCY'];
				$message .= '<br /><strong>DCCAUTHMERCHANTAMOUNT:</strong> ' . $this->request->post['DCCAUTHMERCHANTAMOUNT'];
				$message .= '<br /><strong>DCCCCP:</strong> ' . $this->request->post['DCCCCP'];
				$message .= '<br /><strong>DCCRATE:</strong> ' . $this->request->post['DCCRATE'];
				$message .= '<br /><strong>DCCMARGINRATEPERCENTAGE:</strong> ' . $this->request->post['DCCMARGINRATEPERCENTAGE'];
				$message .= '<br /><strong>DCCEXCHANGERATESOURCENAME:</strong> ' . $this->request->post['DCCEXCHANGERATESOURCENAME'];
				$message .= '<br /><strong>DCCCOMMISSIONPERCENTAGE:</strong> ' . $this->request->post['DCCCOMMISSIONPERCENTAGE'];
				$message .= '<br /><strong>DCCEXCHANGERATESOURCETIMESTAMP:</strong> ' . $this->request->post['DCCEXCHANGERATESOURCETIMESTAMP'];
				$message .= '<br /><strong>DCCCHOICE:</strong> ' . $this->request->post['DCCCHOICE'];
			}

			if ($this->request->post['RESULT'] == "00") {
				$globalpay_order_id = $this->model_payment_globalpay->addOrder($order_info, $this->request->post['PASREF'], $this->request->post['AUTHCODE'], $this->request->post['ACCOUNT'], $this->request->post['ORDER_ID']);

				if ($auto_settle == 1) {
					$this->model_payment_globalpay->addTransaction($globalpay_order_id, 'payment', $order_info);
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('globalpay_order_status_success_settled_id'), $message, false);
				} else {
					$this->model_payment_globalpay->addTransaction($globalpay_order_id, 'auth', 0.00);
					$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('globalpay_order_status_success_unsettled_id'), $message, false);
				}

				$data['text_response'] = $this->language->get('text_success');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/success', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "101") {
				// Decline
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_id'), $message);
				$data['text_response'] = $this->language->get('text_decline');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "102") {
				// Referal B
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_pending_id'), $message);
				$data['text_response'] = $this->language->get('text_decline');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "103") {
				// Referal A
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_stolen_id'), $message);
				$data['text_response'] = $this->language->get('text_decline');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "200") {
				// Error Connecting to Bank
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_bank_id'), $message);
				$data['text_response'] = $this->language->get('text_bank_error');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "204") {
				// Error Connecting to Bank
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_bank_id'), $message);
				$data['text_response'] = $this->language->get('text_bank_error');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} elseif ($this->request->post['RESULT'] == "205") {
				// Comms Error
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_bank_id'), $message);
				$data['text_response'] = $this->language->get('text_bank_error');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			} else {
				// Other error
				$this->model_payment_globalpay->addHistory($order_id, $this->config->get('globalpay_order_status_decline_id'), $message);
				$data['text_response'] = $this->language->get('text_generic_error');
				$data['text_link'] = sprintf($this->language->get('text_link'), $this->url->link('checkout/checkout', '', 'SSL'));
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/globalpay_response.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/payment/globalpay_response.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/payment/globalpay_response.tpl', $data));
		}
	}
}