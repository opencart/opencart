<?php
class ControllerExtensionPaymentPPProIframe extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_pp_pro_iframe', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		} else {
			$data['error'] = @$this->error;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_pro_iframe', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/pp_pro_iframe', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_pp_pro_iframe_sig'])) {
			$data['payment_pp_pro_iframe_sig'] = $this->request->post['payment_pp_pro_iframe_sig'];
		} else {
			$data['payment_pp_pro_iframe_sig'] = $this->config->get('payment_pp_pro_iframe_sig');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_user'])) {
			$data['payment_pp_pro_iframe_user'] = $this->request->post['payment_pp_pro_iframe_user'];
		} else {
			$data['payment_pp_pro_iframe_user'] = $this->config->get('payment_pp_pro_iframe_user');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_password'])) {
			$data['payment_pp_pro_iframe_password'] = $this->request->post['payment_pp_pro_iframe_password'];
		} else {
			$data['payment_pp_pro_iframe_password'] = $this->config->get('payment_pp_pro_iframe_password');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_transaction_method'])) {
			$data['payment_pp_pro_iframe_transaction_method'] = $this->request->post['payment_pp_pro_iframe_transaction_method'];
		} else {
			$data['payment_pp_pro_iframe_transaction_method'] = $this->config->get('payment_pp_pro_iframe_transaction_method');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_test'])) {
			$data['payment_pp_pro_iframe_test'] = $this->request->post['payment_pp_pro_iframe_test'];
		} else {
			$data['payment_pp_pro_iframe_test'] = $this->config->get('payment_pp_pro_iframe_test');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_total'])) {
			$data['payment_pp_pro_iframe_total'] = $this->request->post['payment_pp_pro_iframe_total'];
		} else {
			$data['payment_pp_pro_iframe_total'] = $this->config->get('payment_pp_pro_iframe_total');
		}

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_pp_pro_iframe_canceled_reversal_status_id'])) {
			$data['payment_pp_pro_iframe_canceled_reversal_status_id'] = $this->request->post['payment_pp_pro_iframe_canceled_reversal_status_id'];
		} else {
			$data['payment_pp_pro_iframe_canceled_reversal_status_id'] = $this->config->get('payment_pp_pro_iframe_canceled_reversal_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_completed_status_id'])) {
			$data['payment_pp_pro_iframe_completed_status_id'] = $this->request->post['payment_pp_pro_iframe_completed_status_id'];
		} else {
			$data['payment_pp_pro_iframe_completed_status_id'] = $this->config->get('payment_pp_pro_iframe_completed_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_denied_status_id'])) {
			$data['payment_pp_pro_iframe_denied_status_id'] = $this->request->post['payment_pp_pro_iframe_denied_status_id'];
		} else {
			$data['payment_pp_pro_iframe_denied_status_id'] = $this->config->get('payment_pp_pro_iframe_denied_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_expired_status_id'])) {
			$data['payment_pp_pro_iframe_expired_status_id'] = $this->request->post['payment_pp_pro_iframe_expired_status_id'];
		} else {
			$data['payment_pp_pro_iframe_expired_status_id'] = $this->config->get('payment_pp_pro_iframe_expired_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_failed_status_id'])) {
			$data['payment_pp_pro_iframe_failed_status_id'] = $this->request->post['payment_pp_pro_iframe_failed_status_id'];
		} else {
			$data['payment_pp_pro_iframe_failed_status_id'] = $this->config->get('payment_pp_pro_iframe_failed_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_pending_status_id'])) {
			$data['payment_pp_pro_iframe_pending_status_id'] = $this->request->post['payment_pp_pro_iframe_pending_status_id'];
		} else {
			$data['payment_pp_pro_iframe_pending_status_id'] = $this->config->get('payment_pp_pro_iframe_pending_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_processed_status_id'])) {
			$data['payment_pp_pro_iframe_processed_status_id'] = $this->request->post['payment_pp_pro_iframe_processed_status_id'];
		} else {
			$data['payment_pp_pro_iframe_processed_status_id'] = $this->config->get('payment_pp_pro_iframe_processed_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_processed_status_id'])) {
			$data['payment_pp_pro_iframe_processed_status_id'] = $this->request->post['payment_pp_pro_iframe_processed_status_id'];
		} else {
			$data['payment_pp_pro_iframe_processed_status_id'] = $this->config->get('payment_pp_pro_iframe_processed_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_reversed_status_id'])) {
			$data['payment_pp_pro_iframe_reversed_status_id'] = $this->request->post['payment_pp_pro_iframe_reversed_status_id'];
		} else {
			$data['payment_pp_pro_iframe_reversed_status_id'] = $this->config->get('payment_pp_pro_iframe_reversed_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_voided_status_id'])) {
			$data['payment_pp_pro_iframe_voided_status_id'] = $this->request->post['payment_pp_pro_iframe_voided_status_id'];
		} else {
			$data['payment_pp_pro_iframe_voided_status_id'] = $this->config->get('payment_pp_pro_iframe_voided_status_id');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_geo_zone_id'])) {
			$data['payment_pp_pro_iframe_geo_zone_id'] = $this->request->post['payment_pp_pro_iframe_geo_zone_id'];
		} else {
			$data['payment_pp_pro_iframe_geo_zone_id'] = $this->config->get('payment_pp_pro_iframe_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_pp_pro_iframe_status'])) {
			$data['payment_pp_pro_iframe_status'] = $this->request->post['payment_pp_pro_iframe_status'];
		} else {
			$data['payment_pp_pro_iframe_status'] = $this->config->get('payment_pp_pro_iframe_status');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_sort_order'])) {
			$data['payment_pp_pro_iframe_sort_order'] = $this->request->post['payment_pp_pro_iframe_sort_order'];
		} else {
			$data['payment_pp_pro_iframe_sort_order'] = $this->config->get('payment_pp_pro_iframe_sort_order');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_checkout_method'])) {
			$data['payment_pp_pro_iframe_checkout_method'] = $this->request->post['payment_pp_pro_iframe_checkout_method'];
		} else {
			$data['payment_pp_pro_iframe_checkout_method'] = $this->config->get('payment_pp_pro_iframe_checkout_method');
		}

		if (isset($this->request->post['payment_pp_pro_iframe_debug'])) {
			$data['payment_pp_pro_iframe_debug'] = $this->request->post['payment_pp_pro_iframe_debug'];
		} else {
			$data['payment_pp_pro_iframe_debug'] = $this->config->get('payment_pp_pro_iframe_debug');
		}

		$data['ipn_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/pp_pro_iframe/notify';

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_pro_iframe', $data));
	}

	public function install() {
		$this->load->model('extension/payment/pp_pro_iframe');

		$this->model_extension_payment_pp_pro_iframe->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/pp_pro_iframe');

		$this->model_extension_payment_pp_pro_iframe->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/pp_pro_iframe')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_pp_pro_iframe_sig']) {
			$this->error['sig'] = $this->language->get('error_sig');
		}

		if (!$this->request->post['payment_pp_pro_iframe_user']) {
			$this->error['user'] = $this->language->get('error_user');
		}

		if (!$this->request->post['payment_pp_pro_iframe_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}

		return !$this->error;
	}

	public function order() {
		$this->load->model('extension/payment/pp_pro_iframe');
		$this->load->language('extension/payment/pp_pro_iframe');

		$paypal_order = $this->model_extension_payment_pp_pro_iframe->getOrder($this->request->get['order_id']);

		if ($paypal_order) {
			$data['paypal_order'] = $paypal_order;
			
			$data['user_token'] = $this->session->data['user_token'];

			$data['order_id'] = (int)$this->request->get['order_id'];

			$captured = number_format($this->model_extension_payment_pp_pro_iframe->getTotalCaptured($data['paypal_order']['paypal_iframe_order_id']), 2);
			$refunded = number_format($this->model_extension_payment_pp_pro_iframe->getTotalRefunded($data['paypal_order']['paypal_iframe_order_id']), 2);

			$data['paypal_order']['captured'] = $captured;
			$data['paypal_order']['refunded'] = $refunded;
			$data['paypal_order']['remaining'] = number_format($data['paypal_order']['total'] - $captured, 2);

			$data['transactions'] = array();

			$data['view_link'] = $this->url->link('extension/payment/pp_pro_iframe/info', 'user_token=' . $this->session->data['user_token'], true);
			$data['refund_link'] = $this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'], true);
			$data['resend_link'] = $this->url->link('extension/payment/pp_pro_iframe/resend', 'user_token=' . $this->session->data['user_token'], true);

			$captured = number_format($this->model_extension_payment_pp_pro_iframe->getTotalCaptured($paypal_order['paypal_iframe_order_id']), 2);
			$refunded = number_format($this->model_extension_payment_pp_pro_iframe->getTotalRefunded($paypal_order['paypal_iframe_order_id']), 2);

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
					'view' => $this->url->link('extension/payment/pp_pro_iframe/info', 'user_token=' . $this->session->data['user_token'] . "&transaction_id=" . $transaction['transaction_id'] . '&order_id=' . $this->request->get['order_id'], true),
					'refund' => $this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'] . "&transaction_id=" . $transaction['transaction_id'] . "&order_id=" . $this->request->get['order_id'], true),
					'resend' => $this->url->link('extension/payment/pp_pro_iframe/resend', 'user_token=' . $this->session->data['user_token'] . "&paypal_iframe_order_transaction_id=" . $transaction['paypal_iframe_order_transaction_id'], true),
				);
			}

			$data['reauthorise_link'] = $this->url->link('extension/payment/pp_pro_iframe/reauthorise', 'user_token=' . $this->session->data['user_token'], true);

			return $this->load->view('extension/payment/pp_pro_iframe_order', $data);
		}
	}

	public function refund() {
		$this->load->language('extension/payment/pp_pro_iframe');
		$this->load->model('extension/payment/pp_pro_iframe');

		$this->document->setTitle($this->language->get('text_refund'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_pro_iframe', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_refund'),
			'href' => $this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'], true)
		);

		//button actions
		$data['action'] = $this->url->link('extension/payment/pp_pro_iframe/doRefund', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->get['order_id'])) {
			$data['cancel'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], true);
		} else {
			$data['cancel'] = '';
		}

		$data['transaction_id'] = $this->request->get['transaction_id'];

		$pp_transaction = $this->model_extension_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);

		$data['amount_original'] = $pp_transaction['AMT'];
		$data['currency_code'] = $pp_transaction['CURRENCYCODE'];

		$refunded = number_format($this->model_extension_payment_pp_pro_iframe->getTotalRefundedTransaction($this->request->get['transaction_id']), 2);

		if ($refunded != 0.00) {
			$data['refund_available'] = number_format($data['amount_original'] + $refunded, 2);
			$data['attention'] = $this->language->get('text_current_refunds') . ': ' . $data['refund_available'];
		} else {
			$data['refund_available'] = '';
			$data['attention'] = '';
		}

		$data['user_token'] = $this->session->data['user_token'];

		if (isset($this->session->data['error'])) {
			$data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		} else {
			$data['error'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_pro_iframe_refund', $data));
	}

	public function doRefund() {
		/**
		 * used to issue a refund for a captured payment
		 *
		 * refund can be full or partial
		 */
		if (isset($this->request->post['transaction_id']) && isset($this->request->post['refund_full'])) {

			$this->load->model('extension/payment/pp_pro_iframe');
			$this->load->language('extension/payment/pp_pro_iframe');

			if ($this->request->post['refund_full'] == 0 && $this->request->post['amount'] == 0) {
				$this->session->data['error'] = $this->language->get('error_capture');
				$this->response->redirect($this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
			} else {
				$order_id = $this->model_extension_payment_pp_pro_iframe->getOrderId($this->request->post['transaction_id']);
				$paypal_order = $this->model_extension_payment_pp_pro_iframe->getOrder($order_id);

				if ($paypal_order) {
					$call_data = array();
					$call_data['METHOD'] = 'RefundTransaction';
					$call_data['TRANSACTIONID'] = $this->request->post['transaction_id'];
					$call_data['NOTE'] = urlencode($this->request->post['refund_message']);
					$call_data['MSGSUBID'] = uniqid(mt_rand(), true);

					$current_transaction = $this->model_extension_payment_pp_pro_iframe->getLocalTransaction($this->request->post['transaction_id']);

					if ($this->request->post['refund_full'] == 1) {
						$call_data['REFUNDTYPE'] = 'Full';
					} else {
						$call_data['REFUNDTYPE'] = 'Partial';
						$call_data['AMT'] = number_format($this->request->post['amount'], 2);
						$call_data['CURRENCYCODE'] = $this->request->post['currency_code'];
					}

					$result = $this->model_extension_payment_pp_pro_iframe->call($call_data);

					$transaction = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_id' => $this->request->post['transaction_id'],
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
						$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction, $call_data);
						$this->response->redirect($this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $paypal_order['order_id'], true));
					} else if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {

						$transaction['transaction_id'] = $result['REFUNDTRANSACTIONID'];
						$transaction['payment_type'] = $result['REFUNDSTATUS'];
						$transaction['pending_reason'] = $result['PENDINGREASON'];
						$transaction['amount'] = '-' . $result['GROSSREFUNDAMT'];

						$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction);

						if ($result['TOTALREFUNDEDAMOUNT'] == $this->request->post['amount_original']) {
							$this->model_extension_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Refunded');
						} else {
							$this->model_extension_payment_pp_pro_iframe->updateRefundTransaction($this->request->post['transaction_id'], 'Partially-Refunded');
						}

						//redirect back to the order
						$this->response->redirect($this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $paypal_order['order_id'], true));
					} else {
						if ($this->config->get('payment_pp_pro_iframe_debug')) {
							$log = new Log('pp_pro_iframe.log');
							$log->write(json_encode($result));
						}

						$this->session->data['error'] = (isset($result['L_SHORTMESSAGE0']) ? $result['L_SHORTMESSAGE0'] : 'There was an error') . (isset($result['L_LONGMESSAGE0']) ? '<br />' . $result['L_LONGMESSAGE0'] : '');
						$this->response->redirect($this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
					}
				} else {
					$this->session->data['error'] = $this->language->get('error_data_missing');
					$this->response->redirect($this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
				}
			}
		} else {
			$this->session->data['error'] = $this->language->get('error_data');
			$this->response->redirect($this->url->link('extension/payment/pp_pro_iframe/refund', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->post['transaction_id'], true));
		}
	}

	public function reauthorise() {
		$this->load->language('extension/payment/pp_pro_iframe');
		$this->load->model('extension/payment/pp_pro_iframe');

		$json = array();

		if (isset($this->request->post['order_id'])) {
			$paypal_order = $this->model_extension_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoReauthorization';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];
			$call_data['AMT'] = number_format($paypal_order['total'], 2);
			$call_data['CURRENCYCODE'] = $paypal_order['currency_code'];

			$result = $this->model_extension_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$this->model_extension_payment_pp_pro_iframe->updateAuthorizationId($paypal_order['paypal_iframe_order_id'], $result['AUTHORIZATIONID']);

				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_id' => $paypal_order['authorization_id'],
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

				$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction);

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

	public function info() {
		$this->load->model('extension/payment/pp_pro_iframe');
		$this->load->language('extension/payment/pp_pro_iframe');
 
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/pp_pro_iframe', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_transaction'),
			'href' => $this->url->link('extension/payment/pp_pro_iframe/info', 'user_token=' . $this->session->data['user_token'] . '&transaction_id=' . $this->request->get['transaction_id'], true)
		);

		$transaction = $this->model_extension_payment_pp_pro_iframe->getTransaction($this->request->get['transaction_id']);
		$transaction = array_map('urldecode', $transaction);

		$data['transaction'] = $transaction;
		$data['view_link'] = $this->url->link('extension/payment/pp_pro_iframe/info', 'user_token=' . $this->session->data['user_token'], true);
		$data['user_token'] = $this->session->data['user_token'];

		$this->document->setTitle($this->language->get('text_transaction'));

		if (isset($this->request->get['order_id'])) {
			$data['back'] = $this->url->link('sale/order/info', 'user_token=' . $this->session->data['user_token'] . '&order_id=' . $this->request->get['order_id'], true);
		} else {
			$data['back'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/pp_pro_iframe_transaction', $data));
	}

	public function capture() {
		$this->load->language('extension/payment/pp_pro_iframe');
		/*
		 * used to capture authorised payments
		 * capture can be full or partial amounts
		 */
		if (isset($this->request->post['order_id']) && $this->request->post['amount'] > 0 && isset($this->request->post['order_id']) && isset($this->request->post['complete'])) {

			$this->load->model('extension/payment/pp_pro_iframe');

			$paypal_order = $this->model_extension_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

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

			$result = $this->model_extension_payment_pp_pro_iframe->call($call_data);

			$transaction = array(
				'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
				'transaction_id' => '',
				'parent_id' => $paypal_order['authorization_id'],
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
				$paypal_iframe_order_transaction_id = $this->model_extension_payment_pp_pro_iframe->addTransaction($transaction, $call_data);

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

				$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction);

				unset($transaction['debug_data']);
				$transaction['date_added'] = date("Y-m-d H:i:s");

				$captured = number_format($this->model_extension_payment_pp_pro_iframe->getTotalCaptured($paypal_order['paypal_iframe_order_id']), 2);
				$refunded = number_format($this->model_extension_payment_pp_pro_iframe->getTotalRefunded($paypal_order['paypal_iframe_order_id']), 2);

				$transaction['captured'] = $captured;
				$transaction['refunded'] = $refunded;
				$transaction['remaining'] = number_format($paypal_order['total'] - $captured, 2);

				$transaction['status'] = 0;
				if ($transaction['remaining'] == 0.00) {
					$transaction['status'] = 1;
					$this->model_extension_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
				}

				$transaction['void'] = '';

				if ($this->request->post['complete'] == 1 && $transaction['remaining'] > 0) {
					$transaction['void'] = array(
						'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
						'transaction_id' => '',
						'parent_id' => $paypal_order['authorization_id'],
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

					$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction['void']);
					$this->model_extension_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);
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
		$this->load->language('extension/payment/pp_pro_iframe');

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('extension/payment/pp_pro_iframe');

			$paypal_order = $this->model_extension_payment_pp_pro_iframe->getOrder($this->request->post['order_id']);

			$call_data = array();
			$call_data['METHOD'] = 'DoVoid';
			$call_data['AUTHORIZATIONID'] = $paypal_order['authorization_id'];

			$result = $this->model_extension_payment_pp_pro_iframe->call($call_data);

			if ($result['ACK'] != 'Failure' && $result['ACK'] != 'FailureWithWarning') {
				$transaction = array(
					'paypal_iframe_order_id' => $paypal_order['paypal_iframe_order_id'],
					'transaction_id' => '',
					'parent_id' => $paypal_order['authorization_id'],
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

				$this->model_extension_payment_pp_pro_iframe->addTransaction($transaction);
				$this->model_extension_payment_pp_pro_iframe->updateOrder('Complete', $this->request->post['order_id']);

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

	public function resend() {
		$this->load->model('extension/payment/pp_pro_iframe');
		$this->load->language('extension/payment/pp_pro_iframe');

		$json = array();

		if (isset($this->request->get['paypal_iframe_order_transaction_id'])) {
			$transaction = $this->model_extension_payment_pp_pro_iframe->getFailedTransaction($this->request->get['paypal_iframe_order_transaction_id']);

			if ($transaction) {
				$call_data = json_decode($transaction['call_data'], true);

				$result = $this->model_extension_payment_pp_pro_iframe->call($call_data);

				if ($result) {
					$parent_transaction = $this->model_extension_payment_pp_pro_iframe->getLocalTransaction($transaction['parent_id']);

					if ($parent_transaction['amount'] == abs($transaction['amount'])) {
						$this->model_extension_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_id'], 'Refunded');
					} else {
						$this->model_extension_payment_pp_pro_iframe->updateRefundTransaction($transaction['parent_id'], 'Partially-Refunded');
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

					$this->model_extension_payment_pp_pro_iframe->updateTransaction($transaction);

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
}