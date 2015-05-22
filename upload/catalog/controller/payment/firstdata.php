<?php
class ControllerPaymentFirstdata extends Controller {
	public function index() {
		$this->load->language('payment/firstdata');

		$data['button_confirm'] = $this->language->get('button_confirm');
		$data['text_new_card'] = $this->language->get('text_new_card');
		$data['text_store_card'] = $this->language->get('text_store_card');

		$this->load->model('checkout/order');
		$this->load->model('payment/firstdata');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		if ($this->config->get('firstdata_live_demo') == 1) {
			$data['action'] = $this->config->get('firstdata_live_url');
		} else {
			$data['action'] = $this->config->get('firstdata_demo_url');
		}

		$data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
		$data['currency'] = $this->model_payment_firstdata->mapCurrency($order_info['currency_code']);
		$data['merchant_id'] = $this->config->get('firstdata_merchant_id');
		$data['timestamp'] = date('Y:m:d-H:i:s');
		$data['order_id'] = 'CON-' . $this->session->data['order_id'] . 'T' . $data['timestamp'] . mt_rand(1, 999);
		$data['url_success'] = $this->url->link('checkout/success', '', 'SSL');
		$data['url_fail'] = $this->url->link('payment/firstdata/fail', '', 'SSL');
		$data['url_notify'] = $this->url->link('payment/firstdata/notify', '', 'SSL');

		if (preg_match("/Mobile|Android|BlackBerry|iPhone|Windows Phone/", $this->request->server['HTTP_USER_AGENT'])) {
			$data['mobile'] = true;
		} else {
			$data['mobile'] = false;
		}

		if ($this->config->get('firstdata_auto_settle') == 1) {
			$data['txntype'] = 'sale';
		} else {
			$data['txntype'] = 'preauth';
		}

		$tmp = $data['merchant_id'] . $data['timestamp'] . $data['amount'] . $data['currency'] . $this->config->get('firstdata_secret');
		$ascii = bin2hex($tmp);
		$data['hash'] = sha1($ascii);

		$data['version'] = 'OPENCART-C-' . VERSION;

		$data['bcompany'] = $order_info['payment_company'];
		$data['bname'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
		$data['baddr1'] = substr($order_info['payment_address_1'], 0, 30);
		$data['baddr2'] = substr($order_info['payment_address_2'], 0, 30);
		$data['bcity'] = substr($order_info['payment_city'], 0, 30);
		$data['bstate'] = substr($order_info['payment_zone'], 0, 30);
		$data['bcountry'] = $order_info['payment_iso_code_2'];
		$data['bzip'] = $order_info['payment_postcode'];
		$data['email'] = $order_info['email'];

		if ($this->cart->hasShipping()) {
			$data['sname'] = $order_info['shipping_firstname'] . ' ' . $order_info['shipping_lastname'];
			$data['saddr1'] = substr($order_info['shipping_address_1'], 0, 30);
			$data['saddr2'] = substr($order_info['shipping_address_2'], 0, 30);
			$data['scity'] = substr($order_info['shipping_city'], 0, 30);
			$data['sstate'] = substr($order_info['shipping_zone'], 0, 30);
			$data['scountry'] = $order_info['shipping_iso_code_2'];
			$data['szip'] = $order_info['shipping_postcode'];
		} else {
			$data['sname'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];
			$data['saddr1'] = substr($order_info['payment_address_1'], 0, 30);
			$data['saddr2'] = substr($order_info['payment_address_2'], 0, 30);
			$data['scity'] = substr($order_info['payment_city'], 0, 30);
			$data['sstate'] = substr($order_info['payment_zone'], 0, 30);
			$data['scountry'] = $order_info['payment_iso_code_2'];
			$data['szip'] = $order_info['payment_postcode'];
		}

		if ($this->config->get('firstdata_card_storage') == 1 && $this->customer->isLogged()) {
			$data['card_storage'] = 1;
			$data['stored_cards'] = $this->model_payment_firstdata->getStoredCards();
			$data['new_hosted_id'] = sha1($this->customer->getId()  . '-' . date("Y-m-d-H-i-s") . rand(10, 500));
		} else {
			$data['card_storage'] = 0;
			$data['stored_cards'] = array();
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/firstdata.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/firstdata.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/firstdata.tpl', $data);
		}
	}

	public function notify() {
		$this->load->model('payment/firstdata');

		$this->load->model('checkout/order');

		$this->load->language('payment/firstdata');

		$message = '';

		if ($this->config->get('firstdata_debug') == 1) {
			$this->model_payment_firstdata->logger(print_r($this->request->post, 1));
		}

		if (isset($this->request->post['txntype']) && isset($this->request->post['notification_hash']) && isset($this->request->post['oid'])) {
			$local_hash = $this->model_payment_firstdata->responseHash($this->request->post['chargetotal'], $this->request->post['currency'], $this->request->post['txndatetime'], $this->request->post['approval_code']);

			if ($local_hash == $this->request->post['notification_hash']) {
				$order_id_parts = explode('T', $this->request->post['oid']);

				$order_id = str_replace("CON-","",$order_id_parts[0]);

				$order_info = $this->model_checkout_order->getOrder($order_id);

				if ($this->request->post['txntype'] == 'preauth' || $this->request->post['txntype'] == 'sale') {
					if (isset($this->request->post['approval_code'])) {
						$response_parts = explode(':', $this->request->post['approval_code']);

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
							'M'    => $this->language->get('text_card_code_m'),
							'N'    => $this->language->get('text_card_code_n'),
							'P'    => $this->language->get('text_card_code_p'),
							'S'    => $this->language->get('text_card_code_s'),
							'U'    => $this->language->get('text_card_code_u'),
							'X'    => $this->language->get('text_card_code_x'),
							'NONE' => $this->language->get('text_card_code_blank')
						);

						$card_types = array(
							'M'         => $this->language->get('text_card_type_m'),
							'V'         => $this->language->get('text_card_type_v'),
							'C'         => $this->language->get('text_card_type_c'),
							'A'         => $this->language->get('text_card_type_a'),
							'MA'        => $this->language->get('text_card_type_ma'),
							'MAESTROUK' => $this->language->get('text_card_type_mauk')
						);

						if ($response_parts[0] == 'Y') {
							if (isset($response_parts[3])) {
								if (strlen($response_parts[3]) == 4) {
									$address_pass = strtoupper(substr($response_parts[3], 0, 3));
									$cvv_pass = strtoupper(substr($response_parts[3], -1));

									if (!array_key_exists($cvv_pass, $cvv_codes)) {
										$cvv_pass = 'NONE';
									}
								} else {
									$address_pass = $response_parts[3];
									$cvv_pass = 'NONE';
								}

								$message .= $this->language->get('text_address_response') . $address_codes[$address_pass] . '<br />';
								$message .= $this->language->get('text_card_code_verify') . $cvv_codes[$cvv_pass] . '<br />';
								$message .= $this->language->get('text_response_code_full') . $this->request->post['approval_code'] . '<br />';
								$message .= $this->language->get('text_response_code') . $response_parts[1] . '<br />';

								if (isset($this->request->post['cardnumber'])) {
									$message .= $this->language->get('text_response_card') . $this->request->post['cardnumber'] . '<br />';
								}

								if (isset($this->request->post['processor_response_code'])) {
									$message .= $this->language->get('text_response_proc_code') . $this->request->post['processor_response_code'] . '<br />';
								}

								if (isset($this->request->post['refnumber'])) {
									$message .= $this->language->get('text_response_ref') . $this->request->post['refnumber'] . '<br />';
								}

								if (isset($this->request->post['paymentMethod'])) {
									$message .= $this->language->get('text_response_card_type') . $card_types[strtoupper($this->request->post['paymentMethod'])] . '<br />';
								}
							}

							if (isset($this->request->post['hosteddataid']) && $order_info['customer_id'] != 0) {
								$this->model_payment_firstdata->storeCard($this->request->post['hosteddataid'], $order_info['customer_id'], $this->request->post['expmonth'], $this->request->post['expyear'], $this->request->post['cardnumber']);
							}

							$fd_order_id = $this->model_payment_firstdata->addOrder($order_info, $this->request->post['oid'], $this->request->post['tdate']);

							if ($this->config->get('firstdata_auto_settle') == 1) {
								$this->model_payment_firstdata->addTransaction($fd_order_id, 'payment', $order_info);

								$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_order_status_success_settled_id'), $message, false);
							} else {
								$this->model_payment_firstdata->addTransaction($fd_order_id, 'auth');

								$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_order_status_success_unsettled_id'), $message, false);
							}
						} else {
							$message = $this->request->post['fail_reason'] . '<br />';
							$message .= $this->language->get('text_response_code_full') . $this->request->post['approval_code'];

							$this->model_payment_firstdata->addOrderHistory($order_id, $this->config->get('firstdata_order_status_decline_id'), $message);
						}
					}
				}

				if ($this->request->post['txntype'] == 'void') {
					if ($this->request->post['status'] == 'DECLINED') {
						$fd_order = $this->model_payment_firstdata->getOrder($order_id);

						$this->model_payment_firstdata->updateVoidStatus($order_id, 1);

						$this->model_payment_firstdata->addTransaction($fd_order['firstdata_order_id'], 'void');

						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_order_status_void_id'), $message, false);
					}
				}

				if ($this->request->post['txntype'] == 'postauth') {
					if ($this->request->post['status'] == 'APPROVED') {
						$fd_order = $this->model_payment_firstdata->getOrder($order_id);

						$this->model_payment_firstdata->updateCaptureStatus($order_id, 1);

						$this->model_payment_firstdata->addTransaction($fd_order['firstdata_order_id'], 'payment', $order_info);

						$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('firstdata_order_status_success_settled_id'), $message, false);
					}
				}
			} else {
				$this->model_payment_firstdata->logger('Hash does not match! Received: ' . $this->request->post['notification_hash'] . ', calculated: ' . $local_hash);
			}
		} else {
			$this->model_payment_firstdata->logger('Data is missing from request . ');
		}
	}

	public function fail() {
		$this->load->language('payment/firstdata');

		if (isset($this->request->post['fail_reason']) && !empty($this->request->post['fail_reason'])) {
			$this->session->data['error'] = $this->request->post['fail_reason'];
		} else {
			$this->session->data['error'] = $this->language->get('error_failed');
		}

		$this->response->redirect($this->url->link('checkout/checkout', '', 'SSL'));
	}
}