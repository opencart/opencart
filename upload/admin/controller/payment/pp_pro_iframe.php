<?php
class ControllerPaymentPPProIframe extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('pp_pro_iframe', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		} else {
			$data['error'] = @$this->error;
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_authorization'] = $this->language->get('text_authorization');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_iframe'] = $this->language->get('text_iframe');
		$data['text_redirect'] = $this->language->get('text_redirect');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_signature'] = $this->language->get('entry_signature');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_transaction_method'] = $this->language->get('entry_transaction_method');
		$data['entry_ipn_url'] = $this->language->get('entry_ipn_url');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_denied_status'] = $this->language->get('entry_denied_status');
		$data['entry_expired_status'] = $this->language->get('entry_expired_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
		$data['entry_voided_status'] = $this->language->get('entry_voided_status');
		$data['entry_checkout_method'] = $this->language->get('entry_checkout_method');

		$data['help_test'] = $this->language->get('help_test');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_checkout_method'] = $this->language->get('help_checkout_method');
		$data['help_debug'] = $this->language->get('help_debug');

		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_order_status'] = $this->language->get('tab_order_status');
		$data['tab_checkout_customisation'] = $this->language->get('tab_checkout_customisation');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['pp_pro_iframe_sig'])) {
			$data['pp_pro_iframe_sig'] = $this->request->post['pp_pro_iframe_sig'];
		} else {
			$data['pp_pro_iframe_sig'] = $this->config->get('pp_pro_iframe_sig');
		}

		if (isset($this->request->post['pp_pro_iframe_user'])) {
			$data['pp_pro_iframe_user'] = $this->request->post['pp_pro_iframe_user'];
		} else {
			$data['pp_pro_iframe_user'] = $this->config->get('pp_pro_iframe_user');
		}

		if (isset($this->request->post['pp_pro_iframe_password'])) {
			$data['pp_pro_iframe_password'] = $this->request->post['pp_pro_iframe_password'];
		} else {
			$data['pp_pro_iframe_password'] = $this->config->get('pp_pro_iframe_password');
		}

		if (isset($this->request->post['pp_pro_iframe_transaction_method'])) {
			$data['pp_pro_iframe_transaction_method'] = $this->request->post['pp_pro_iframe_transaction_method'];
		} else {
			$data['pp_pro_iframe_transaction_method'] = $this->config->get('pp_pro_iframe_transaction_method');
		}

		if (isset($this->request->post['pp_pro_iframe_test'])) {
			$data['pp_pro_iframe_test'] = $this->request->post['pp_pro_iframe_test'];
		} else {
			$data['pp_pro_iframe_test'] = $this->config->get('pp_pro_iframe_test');
		}

		if (isset($this->request->post['pp_pro_iframe_total'])) {
			$data['pp_pro_iframe_total'] = $this->request->post['pp_pro_iframe_total'];
		} else {
			$data['pp_pro_iframe_total'] = $this->config->get('pp_pro_iframe_total');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['pp_pro_iframe_canceled_reversal_status_id'])) {
			$data['pp_pro_iframe_canceled_reversal_status_id'] = $this->request->post['pp_pro_iframe_canceled_reversal_status_id'];
		} else {
			$data['pp_pro_iframe_canceled_reversal_status_id'] = $this->config->get('pp_pro_iframe_canceled_reversal_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_completed_status_id'])) {
			$data['pp_pro_iframe_completed_status_id'] = $this->request->post['pp_pro_iframe_completed_status_id'];
		} else {
			$data['pp_pro_iframe_completed_status_id'] = $this->config->get('pp_pro_iframe_completed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_denied_status_id'])) {
			$data['pp_pro_iframe_denied_status_id'] = $this->request->post['pp_pro_iframe_denied_status_id'];
		} else {
			$data['pp_pro_iframe_denied_status_id'] = $this->config->get('pp_pro_iframe_denied_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_expired_status_id'])) {
			$data['pp_pro_iframe_expired_status_id'] = $this->request->post['pp_pro_iframe_expired_status_id'];
		} else {
			$data['pp_pro_iframe_expired_status_id'] = $this->config->get('pp_pro_iframe_expired_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_failed_status_id'])) {
			$data['pp_pro_iframe_failed_status_id'] = $this->request->post['pp_pro_iframe_failed_status_id'];
		} else {
			$data['pp_pro_iframe_failed_status_id'] = $this->config->get('pp_pro_iframe_failed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_pending_status_id'])) {
			$data['pp_pro_iframe_pending_status_id'] = $this->request->post['pp_pro_iframe_pending_status_id'];
		} else {
			$data['pp_pro_iframe_pending_status_id'] = $this->config->get('pp_pro_iframe_pending_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_processed_status_id'])) {
			$data['pp_pro_iframe_processed_status_id'] = $this->request->post['pp_pro_iframe_processed_status_id'];
		} else {
			$data['pp_pro_iframe_processed_status_id'] = $this->config->get('pp_pro_iframe_processed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_refunded_status_id'])) {
			$data['pp_pro_iframe_refunded_status_id'] = $this->request->post['pp_pro_iframe_refunded_status_id'];
		} else {
			$data['pp_pro_iframe_refunded_status_id'] = $this->config->get('pp_pro_iframe_refunded_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_reversed_status_id'])) {
			$data['pp_pro_iframe_reversed_status_id'] = $this->request->post['pp_pro_iframe_reversed_status_id'];
		} else {
			$data['pp_pro_iframe_reversed_status_id'] = $this->config->get('pp_pro_iframe_reversed_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_voided_status_id'])) {
			$data['pp_pro_iframe_voided_status_id'] = $this->request->post['pp_pro_iframe_voided_status_id'];
		} else {
			$data['pp_pro_iframe_voided_status_id'] = $this->config->get('pp_pro_iframe_voided_status_id');
		}

		if (isset($this->request->post['pp_pro_iframe_geo_zone_id'])) {
			$data['pp_pro_iframe_geo_zone_id'] = $this->request->post['pp_pro_iframe_geo_zone_id'];
		} else {
			$data['pp_pro_iframe_geo_zone_id'] = $this->config->get('pp_pro_iframe_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['pp_pro_iframe_status'])) {
			$data['pp_pro_iframe_status'] = $this->request->post['pp_pro_iframe_status'];
		} else {
			$data['pp_pro_iframe_status'] = $this->config->get('pp_pro_iframe_status');
		}

		if (isset($this->request->post['pp_pro_iframe_sort_order'])) {
			$data['pp_pro_iframe_sort_order'] = $this->request->post['pp_pro_iframe_sort_order'];
		} else {
			$data['pp_pro_iframe_sort_order'] = $this->config->get('pp_pro_iframe_sort_order');
		}

		if (isset($this->request->post['pp_pro_iframe_checkout_method'])) {
			$data['pp_pro_iframe_checkout_method'] = $this->request->post['pp_pro_iframe_checkout_method'];
		} else {
			$data['pp_pro_iframe_checkout_method'] = $this->config->get('pp_pro_iframe_checkout_method');
		}

		if (isset($this->request->post['pp_pro_iframe_debug'])) {
			$data['pp_pro_iframe_debug'] = $this->request->post['pp_pro_iframe_debug'];
		} else {
			$data['pp_pro_iframe_debug'] = $this->config->get('pp_pro_iframe_debug');
		}

		$data['ipn_url'] = HTTPS_CATALOG . 'index.php?route=payment/pp_pro_iframe/notify';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_pro_iframe.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/pp_pro_iframe');

		$this->model_payment_pp_pro_iframe->install();
	}

	public function uninstall() {
		$this->load->model('payment/pp_pro_iframe');

		$this->model_payment_pp_pro_iframe->uninstall();
	}

	public function refund() {
		$this->load->language('payment/pp_pro_iframe');
		$this->load->model('payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('text_refund'));

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_refund'] = $this->language->get('text_refund');
		
		$data['entry_transaction_id'] = $this->language->get('entry_transaction_id');
		$data['entry_full_refund'] = $this->language->get('entry_full_refund');
		$data['entry_amount'] = $this->language->get('entry_amount');
		$data['entry_message'] = $this->language->get('entry_message');
		
		$data['button_refund'] = $this->language->get('button_refund');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_refund'),
			'href' => $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'], 'SSL')
		);

		//button actions
		$data['action'] = $this->url->link('payment/pp_pro_iframe/doRefund', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->get['order_id'])) {
			$data['cancel'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
		} else {
			$data['cancel'] = '';
		}

		$data['transaction_id'] = $this->request->get['transaction_id'];

		$pp_transaction = $this->model_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);

		$data['amount_original'] = $pp_transaction['AMT'];
		$data['currency_code'] = $pp_transaction['CURRENCYCODE'];

		$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedTransaction($this->request->get['transaction_id']), 2);

		if ($refunded != 0.00) {
			$data['refund_available'] = number_format($data['amount_original'] + $refunded, 2);
			$data['attention'] = $this->language->get('text_current_refunds') . ': ' . $data['refund_available'];
		} else {
			$data['refund_available'] = '';
			$data['attention'] = '';
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_pro_iframe_refund.tpl', $data));
	}

	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		if (isset($this->request->post['transaction_id']) && isset($this->request->post['refund_full'])) {

			$this->load->model('payment/pp_pro_iframe');
			$this->load->language('payment/pp_pro_iframe');

			if ($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_capture_amt');
				$this->response->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
			} else {
				$order_id = $this->model_payment_pp_pro_iframe->getOrderId($this->request->post['transaction_id']);
				$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($order_id);

				if ($paypal_order) {
					$call_data = array();
					$call_data['METHOD'] = 'RefundTransaction';
					$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
					$call_data['NOTE'] = urlencode($this->request->post['refund_message']);
					$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

					$current_transaction = $this->model_payment_pp_pro_iframe->getLocalTransaction($this->request->post['transaction_id']);

					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE'] = 'Full';
					} else {
						$call_data['REFUNDTYPE'] = 'Partial';
						$call_data['AMT'] = number_format($this->request->post['amount'], 2);
						$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
					}

					$result = $this->model_payment_pp_pro_iframe->call($call_data);

					$transaction = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $this->request->post['transaction_id'],
						'note' => $this->request->post['refund_message'],
						'msgsubid' => $call_data['MSGSUBID'],
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Refunded',
						'transaction_entity' => 'payment',
						'pending_reason' => '',
						'amount' => '-' . (isset($call_data['AMT']) ? $call_data['AMT'] : $current_transaction['amount']),
						'debug_data' => json_encode($result)
					);

					if ($result == false) {
						$transaction['payment_status'] = 'Failed';
						$this->model_payment_pp_pro_iframe->addTransaction($transaction, $call_data);
						$this->response->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {

						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
						$transaction['pending_reason'] = $result['PENDINGREASON'];
						$transaction['amount'] = '-' . $result['GROSSREFUNDAMT'];

						$this->model_payment_pp_pro_iframe->addTransaction($transaction);

						if ($result['TOTALREFUNDEDAMOUNT'] == $this->request->post['amount_original']) {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Refunded');
						} else {
							$this->model_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Partially-Refunded');
						}

						//redirect back to the order
						$this->response->redirect($this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $paypal_order['order_id'], 'SSL'));
					} else {
						$this->model_payment_pp_pro_iframe->log(json_encode($result));
						$this->session->data['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error') . (isset($result['L_LONGMESSAGE0']) ? '<br />' . $result['L_LONGMESSAGE0'] : '');
						$this->response->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
					}
				} else {
					$this->session->data['error'] = $this->language->get('error_data_missing');
					$this->response->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->response->redirect($this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->post['transaction_id'], 'SSL'));
		}
	}

	public function reauthorise() {
		$this->load->language('payment/pp_pro_iframe');
		$this->load->model('payment/pp_pro_iframe');

		$json = array();

		if (isset($this->request->post['order_id'])) {
			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoReauthorization';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];
			$call_data['AMT'] = number_format($paypal_order['total'], 2);
			$call_data['CURRENCYCODE'] = $paypal_order['currency_code'];

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$this->model_payment_pp_pro_iframe->updateAuthorizationId($paypal_order['paypal_iframe_order_id'], $result['AUTHORIZATIONID']);

				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_transaction_id' => $paypal_order['authorization_id'],
					'note' => '',
					'msgsubid' => '',
					'receipt_id' => '',
					'payment_type' => 'instant',
					'payment_status' => $result['PAYMENTSTATUS'],
					'transaction_entity' => 'auth',
					'pending_reason' => $result['PENDINGREASON'],
					'amount' => '-' . '',
					'debug_data' => json_encode($result)
				);

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);

				$transaction['date_added'] = date("Y-m-d H:i:s");

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Ok';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : $this->language->get('error_general'));
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function viewTransaction() {
		$this->load->model('payment/pp_pro_iframe');
		$this->load->language('payment/pp_pro_iframe');

		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_product_lines'] = $this->language->get('text_product_lines');
		$data['text_ebay_txn_id'] = $this->language->get('text_ebay_txn_id');
		$data['text_name'] = $this->language->get('text_name');
		$data['text_qty'] = $this->language->get('text_qty');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_number'] = $this->language->get('text_number');
		$data['text_coupon_id'] = $this->language->get('text_coupon_id');
		$data['text_coupon_amount'] = $this->language->get('text_coupon_amount');
		$data['text_coupon_currency'] = $this->language->get('text_coupon_currency');
		$data['text_loyalty_currency'] = $this->language->get('text_loyalty_currency');
		$data['text_loyalty_disc_amt'] = $this->language->get('text_loyalty_disc_amt');
		$data['text_options_name'] = $this->language->get('text_options_name');
		$data['text_tax_amt'] = $this->language->get('text_tax_amt');
		$data['text_currency_code'] = $this->language->get('text_currency_code');
		$data['text_amount'] = $this->language->get('text_amount');
		$data['text_gift_msg'] = $this->language->get('text_gift_msg');
		$data['text_gift_receipt'] = $this->language->get('text_gift_receipt');
		$data['text_gift_wrap_name'] = $this->language->get('text_gift_wrap_name');
		$data['text_gift_wrap_amt'] = $this->language->get('text_gift_wrap_amt');
		$data['text_buyer_email_market'] = $this->language->get('text_buyer_email_market');
		$data['text_survey_question'] = $this->language->get('text_survey_question');
		$data['text_survey_chosen'] = $this->language->get('text_survey_chosen');
		$data['text_receiver_business'] = $this->language->get('text_receiver_business');
		$data['text_receiver_email'] = $this->language->get('text_receiver_email');
		$data['text_receiver_id'] = $this->language->get('text_receiver_id');
		$data['text_buyer_email'] = $this->language->get('text_buyer_email');
		$data['text_payer_id'] = $this->language->get('text_payer_id');
		$data['text_payer_status'] = $this->language->get('text_payer_status');
		$data['text_country_code'] = $this->language->get('text_country_code');
		$data['text_payer_business'] = $this->language->get('text_payer_business');
		$data['text_payer_salute'] = $this->language->get('text_payer_salute');
		$data['text_payer_firstname'] = $this->language->get('text_payer_firstname');
		$data['text_payer_middlename'] = $this->language->get('text_payer_middlename');
		$data['text_payer_lastname'] = $this->language->get('text_payer_lastname');
		$data['text_payer_suffix'] = $this->language->get('text_payer_suffix');
		$data['text_address_owner'] = $this->language->get('text_address_owner');
		$data['text_address_status'] = $this->language->get('text_address_status');
		$data['text_ship_sec_name'] = $this->language->get('text_ship_sec_name');
		$data['text_ship_name'] = $this->language->get('text_ship_name');
		$data['text_ship_street1'] = $this->language->get('text_ship_street1');
		$data['text_ship_street2'] = $this->language->get('text_ship_street2');
		$data['text_ship_city'] = $this->language->get('text_ship_city');
		$data['text_ship_state'] = $this->language->get('text_ship_state');
		$data['text_ship_zip'] = $this->language->get('text_ship_zip');
		$data['text_ship_country'] = $this->language->get('text_ship_country');
		$data['text_ship_phone'] = $this->language->get('text_ship_phone');
		$data['text_ship_sec_add1'] = $this->language->get('text_ship_sec_add1');
		$data['text_ship_sec_add2'] = $this->language->get('text_ship_sec_add2');
		$data['text_ship_sec_city'] = $this->language->get('text_ship_sec_city');
		$data['text_ship_sec_state'] = $this->language->get('text_ship_sec_state');
		$data['text_ship_sec_zip'] = $this->language->get('text_ship_sec_zip');
		$data['text_ship_sec_country'] = $this->language->get('text_ship_sec_country');
		$data['text_ship_sec_phone'] = $this->language->get('text_ship_sec_phone');
		$data['text_trans_id'] = $this->language->get('text_trans_id');
		$data['text_receipt_id'] = $this->language->get('text_receipt_id');
		$data['text_parent_trans_id'] = $this->language->get('text_parent_trans_id');
		$data['text_trans_type'] = $this->language->get('text_trans_type');
		$data['text_payment_type'] = $this->language->get('text_payment_type');
		$data['text_order_time'] = $this->language->get('text_order_time');
		$data['text_fee_amount'] = $this->language->get('text_fee_amount');
		$data['text_settle_amount'] = $this->language->get('text_settle_amount');
		$data['text_tax_amount'] = $this->language->get('text_tax_amount');
		$data['text_exchange'] = $this->language->get('text_exchange');
		$data['text_payment_status'] = $this->language->get('text_payment_status');
		$data['text_pending_reason'] = $this->language->get('text_pending_reason');
		$data['text_reason_code'] = $this->language->get('text_reason_code');
		$data['text_protect_elig'] = $this->language->get('text_protect_elig');
		$data['text_protect_elig_type'] = $this->language->get('text_protect_elig_type');
		$data['text_store_id'] = $this->language->get('text_store_id');
		$data['text_terminal_id'] = $this->language->get('text_terminal_id');
		$data['text_invoice_number'] = $this->language->get('text_invoice_number');
		$data['text_custom'] = $this->language->get('text_custom');
		$data['text_note'] = $this->language->get('text_note');
		$data['text_sales_tax'] = $this->language->get('text_sales_tax');
		$data['text_buyer_id'] = $this->language->get('text_buyer_id');
		$data['text_close_date'] = $this->language->get('text_close_date');
		$data['text_multi_item'] = $this->language->get('text_multi_item');
		$data['text_sub_amt'] = $this->language->get('text_sub_amt');
		$data['text_sub_period'] = $this->language->get('text_sub_period');
		$data['button_back'] = $this->language->get('button_back');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/pp_pro_iframe', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_transaction'),
			'href' => $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'] . '&transaction_id=' . $this->request->get['transaction_id'], 'SSL')
		);

		$transaction = $this->model_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);
		$transaction = array_map('urldecode', $transaction);

		$data['transaction'] = $transaction;
		$data['view_link'] = $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
		$data['token'] = $this->session->data['token'];

		$this->document->setTitle($this->language->get('text_transaction'));

		if (isset($this->request->get['order_id'])) {
			$data['back'] = $this->url->link('sale/order/info', 'token=' . $this->session->data['token'] . '&order_id=' . $this->request->get['order_id'], 'SSL');
		} else {
			$data['back'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/pp_pro_iframe_transaction.tpl', $data));
	}

	public function capture() {
		$this->load->language('payment/pp_pro_iframe');
		/*
		 * used to capture authorised payments
		 * capture can be full or partial amounts
		 */
		if (isset($this->request->post['order_id']) && $this->request->post['amount'] > 0 && isset($this->request->post['order_id']) && isset($this->request->post['complete'])) {

			$this->load->model('payment/pp_pro_iframe');

			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			if ($this->request->post['complete'] == 1) {
				$complete = 'Complete';
			} else {
				$complete = 'NotComplete';
			}

			$call_data = array();
			$call_data['METHOD'] = 'DoCapture';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];
			$call_data['AMT'] = number_format($this->request->post['amount'], 2);
			$call_data['CURRENCYCODE'] = $paypal_order['currency_code'];
			$call_data['COMPLETETYPE'] = $complete;
			$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			$transaction = array(
				'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
				'transaction_id' => '',
				'parent_transaction_id' => $paypal_order['authorization_id'],
				'note' => '',
				'msgsubid' => $call_data['MSGSUBID'],
				'receipt_id' => '',
				'payment_type' => '',
				'payment_status' => '',
				'pending_reason' => '',
				'transaction_entity' => 'payment',
				'amount' => '',
				'debug_data' => json_encode($result)
			);

			if ($result == false) {
				$transaction['amount'] = number_format($this->request->post['amount'], 2);
				$paypal_iframe_order_transaction_id = $this->model_payment_pp_pro_iframe->addTransaction($transaction, $call_data);

				$json['error'] = true;

				$json['failed_transaction']['paypal_iframe_order_transaction_id'] = $paypal_iframe_order_transaction_id;
				$json['failed_transaction']['amount'] = $transaction['amount'];
				$json['failed_transaction']['date_added'] = date("Y-m-d H:i:s");

				$json['msg'] = $this->language->get('error_timeout');
			} else if (isset($result['ACK']) && $result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$transaction['transaction_id'] = $result['TRANSACTIONID'];
				$transaction['payment_type'] = $result['PAYMENTTYPE'];
				$transaction['payment_status'] = $result['PAYMENTSTATUS'];
				$transaction['pending_reason'] = (isset($result['PENDINGREASON']) ? $result['PENDINGREASON'] : '');
				$transaction['amount'] = $result['AMT'];

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);

				unset($transaction['debug_data']);
				$transaction['date_added'] = date("Y-m-d H:i:s");

				$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($paypal_order['paypal_iframe_order_id']), 2);
				$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($paypal_order['paypal_iframe_order_id']), 2);

				$transaction['captured'] = $captured;
				$transaction['refunded'] = $refunded;
				$transaction['remaining'] = number_format($paypal_order['total'] - $captured, 2);

				$transaction['status'] = 0;
				if ($transaction['remaining'] == 0.00) {
					$transaction['status'] = 1;
					$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
				}

				$transaction['void'] = '';

				if ($this->request->post['complete'] == 1 && $transaction['remaining'] > 0) {
					$transaction['void'] = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_transaction_id' => $paypal_order['authorization_id'],
						'note' => '',
						'msgsubid' => '',
						'receipt_id' => '',
						'payment_type' => '',
						'payment_status' => 'Void',
						'pending_reason' => '',
						'amount' => '',
						'debug_data' => 'Voided after capture',
						'transaction_entity' => 'auth',
					);

					$this->model_payment_pp_pro_iframe->addTransaction($transaction['void']);
					$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
					$transaction['void']['date_added'] = date("Y-m-d H:i:s");
					$transaction['status'] = 1;
				}

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Ok';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error');
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function void() {
		$this->load->language('payment/pp_pro_iframe');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('payment/pp_pro_iframe');

			$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoVoid';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];

			$result = $this->model_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_transaction_id' => $paypal_order['authorization_id'],
					'note' => '',
					'msgsubid' => '',
					'receipt_id' => '',
					'payment_type' => 'void',
					'payment_status' => 'Void',
					'pending_reason' => '',
					'transaction_entity' => 'auth',
					'amount' => '',
					'debug_data' => json_encode($result)
				);

				$this->model_payment_pp_pro_iframe->addTransaction($transaction);
				$this->model_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);

				unset($transaction['debug_data']);
				$transaction['date_added'] = date("Y-m-d H:i:s");

				$json['data'] = $transaction;
				$json['error'] = false;
				$json['msg'] = 'Transaction void';
			} else {
				$json['error'] = true;
				$json['msg'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : $this->language->get('error_general'));
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function orderAction() {
		$this->load->model('payment/pp_pro_iframe');
		$this->load->language('payment/pp_pro_iframe');

		$paypal_order = $this->model_payment_pp_pro_iframe->getOrder($this->request->get['order_id']);

		if ($paypal_order) {
			$data['text_payment_info'] = $this->language->get('text_payment_info');
			$data['text_capture_status'] = $this->language->get('text_capture_status');
			$data['text_amount_auth'] = $this->language->get('text_amount_auth');
			$data['button_void'] = $this->language->get('button_void');
			$data['button_capture'] = $this->language->get('button_capture');
			$data['button_reauthorise'] = $this->language->get('button_reauthorise');
			$data['text_reauthorise'] = $this->language->get('text_reauthorise');
			$data['text_reauthorised'] = $this->language->get('text_reauthorised');
			$data['text_amount_captured'] = $this->language->get('text_amount_captured');
			$data['text_amount_refunded'] = $this->language->get('text_amount_refunded');
			$data['text_capture_amount'] = $this->language->get('text_capture_amount');
			$data['text_complete_capture'] = $this->language->get('text_complete_capture');
			$data['text_transactions'] = $this->language->get('text_transactions');
			$data['text_complete'] = $this->language->get('text_complete');
			$data['text_confirm_void'] = $this->language->get('text_confirm_void');
			$data['error_capture_amt'] = $this->language->get('error_capture_amt');
			$data['text_view'] = $this->language->get('text_view');
			$data['text_refund'] = $this->language->get('text_refund');
			$data['text_resend'] = $this->language->get('text_resend');
			$data['column_trans_id'] = $this->language->get('column_trans_id');
			$data['column_amount'] = $this->language->get('column_amount');
			$data['column_type'] = $this->language->get('column_type');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_pend_reason'] = $this->language->get('column_pend_reason');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_action'] = $this->language->get('column_action');

			$data['paypal_order'] = $paypal_order;
			$data['token'] = $this->session->data['token'];

			$data['order_id'] = $this->request->get['order_id'];

			$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($data['paypal_order']['paypal_iframe_order_id']), 2);
			$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($data['paypal_order']['paypal_iframe_order_id']), 2);

			$data['paypal_order']['captured'] = $captured;
			$data['paypal_order']['refunded'] = $refunded;
			$data['paypal_order']['remaining'] = number_format($data['paypal_order']['total'] - $captured, 2);

			$data['transactions'] = array();

			$data['view_link'] = $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'], 'SSL');
			$data['refund_link'] = $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'], 'SSL');
			$data['resend_link'] = $this->url->link('payment/pp_pro_iframe/resend', 'token=' . $this->session->data['token'], 'SSL');

			$captured = number_format($this->model_payment_pp_pro_iframe->totalCaptured($paypal_order['paypal_iframe_order_id']), 2);
			$refunded = number_format($this->model_payment_pp_pro_iframe->totalRefundedOrder($paypal_order['paypal_iframe_order_id']), 2);

			$data['paypal_order'] = $paypal_order;

			$data['paypal_order']['captured'] = $captured;
			$data['paypal_order']['refunded'] = $refunded;
			$data['paypal_order']['remaining'] = number_format($paypal_order['total'] - $captured, 2);

			foreach ($paypal_order['transactions'] as $transaction) {
				$data['transactions'][] = array(
					'paypal_iframe_order_transaction_id' => $transaction['paypal_iframe_order_transaction_id'],
					'transaction_id' => $transaction['transaction_id'],
					'amount' => $transaction['amount'],
					'date_added' => $transaction['date_added'],
					'payment_type' => $transaction['payment_type'],
					'payment_status' => $transaction['payment_status'],
					'pending_reason' => $transaction['pending_reason'],
					'view' => $this->url->link('payment/pp_pro_iframe/viewTransaction', 'token=' . $this->session->data['token'] . "&transaction_id=" . $transaction['transaction_id'] . '&order_id=' . $this->request->get['order_id'], 'SSL'),
					'refund' => $this->url->link('payment/pp_pro_iframe/refund', 'token=' . $this->session->data['token'] . "&transaction_id=" . $transaction['transaction_id'] . "&order_id=" . $this->request->get['order_id'], 'SSL'),
					'resend' => $this->url->link('payment/pp_pro_iframe/resend', 'token=' . $this->session->data['token'] . "&paypal_iframe_order_transaction_id=" . $transaction['paypal_iframe_order_transaction_id'], 'SSL'),
				);
			}

			$data['reauthorise_link'] = $this->url->link('payment/pp_pro_iframe/reauthorise', 'token=' . $this->session->data['token'], 'SSL');

			return $this->load->view('payment/pp_pro_iframe_order.tpl', $data);
		}
	}

	public function resend() {
		$this->load->model('payment/pp_pro_iframe');
		$this->load->language('payment/pp_pro_iframe');

		$json = array();

		if (isset($this->request->get['paypal_iframe_order_transaction_id'])) {
			$transaction = $this->model_payment_pp_pro_iframe->getFailedTransaction($this->request->get['paypal_iframe_order_transaction_id']);

			if ($transaction) {
				$call_data = unserialize($transaction['call_data']);

				$result = $this->model_payment_pp_pro_iframe->call($call_data);

				if ($result) {
					$parent_transaction = $this->model_payment_pp_pro_iframe->getLocalTransaction($transaction['parent_transaction_id']);

					if ($parent_transaction['amount'] == abs($transaction['amount'])) {
						$this->model_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_transaction_id'], 'Refunded');
					} else {
						$this->model_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_transaction_id'], 'Partially-Refunded');
					}

					if (isset($result['REFUNDTRANSACTIONID'])) {
						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
					} else {
						$transaction['transaction_id'] = $result['TRANSACTIONID'];
					}

					if (isset($result['PAYMENTTYPE'])) {
						$transaction['payment_type'] = $result['PAYMENTTYPE'];
					} else {
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
					}

					if (isset($result['PAYMENTSTATUS'])) {
						$transaction['payment_status'] = $result['PAYMENTSTATUS'];
					} else {
						$transaction['payment_status'] = 'Refunded';
					}

					if (isset($result['AMT'])) {
						$transaction['amount'] = $result['AMT'];
					} else {
						$transaction['amount'] = $transaction['amount'];
					}

					$transaction['pending_reason'] = (isset($result['PENDINGREASON']) ? $result['PENDINGREASON'] : '');

					$this->model_payment_pp_pro_iframe->updateTransaction($transaction);

					$json['success'] = $this->language->get('success_transaction_resent');
				} else {
					$json['error'] = $this->language->get('error_timeout');
				}
			} else {
				$json['error'] = $this->language->get('error_transaction_missing');
			}
		} else {
			$json['error'] = $this->language->get('error_missing_data');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/pp_pro_iframe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['pp_pro_iframe_sig']) {
			$this->error['sig'] = $this->language->get('error_sig');
		}

		if (!$this->request->post['pp_pro_iframe_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['pp_pro_iframe_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		return !$this->error;
	}
}