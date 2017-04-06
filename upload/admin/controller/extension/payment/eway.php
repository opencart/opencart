<?php
class ControllerExtensionPaymentEway extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/payment/eway');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('eway', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_authorisation'] = $this->language->get('text_authorisation');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_transparent'] = $this->language->get('text_transparent');
		$data['text_iframe'] = $this->language->get('text_iframe');

		$data['entry_paymode'] = $this->language->get('entry_paymode');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_payment_type'] = $this->language->get('entry_payment_type');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_order_status_refund'] = $this->language->get('entry_order_status_refund');
		$data['entry_order_status_auth'] = $this->language->get('entry_order_status_auth');
		$data['entry_order_status_fraud'] = $this->language->get('entry_order_status_fraud');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_password'] = $this->language->get('entry_password');
		$data['entry_transaction_method'] = $this->language->get('entry_transaction_method');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_testmode'] = $this->language->get('help_testmode');
		$data['help_username'] = $this->language->get('help_username');
		$data['help_password'] = $this->language->get('help_password');
		$data['help_transaction_method'] = $this->language->get('help_transaction_method');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}

		if (isset($this->error['payment_type'])) {
			$data['error_payment_type'] = $this->error['payment_type'];
		} else {
			$data['error_payment_type'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/eway', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/eway', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['eway_payment_gateway'])) {
			$data['eway_payment_gateway'] = $this->request->post['eway_payment_gateway'];
		} else {
			$data['eway_payment_gateway'] = $this->config->get('eway_payment_gateway');
		}

		if (isset($this->request->post['eway_paymode'])) {
			$data['eway_paymode'] = $this->request->post['eway_paymode'];
		} else {
			$data['eway_paymode'] = $this->config->get('eway_paymode');
		}

		if (isset($this->request->post['eway_test'])) {
			$data['eway_test'] = $this->request->post['eway_test'];
		} else {
			$data['eway_test'] = $this->config->get('eway_test');
		}

		if (isset($this->request->post['eway_payment_type'])) {
			$data['eway_payment_type'] = $this->request->post['eway_payment_type'];
		} else {
			$data['eway_payment_type'] = $this->config->get('eway_payment_type');
		}

		if (isset($this->request->post['eway_transaction'])) {
			$data['eway_transaction'] = $this->request->post['eway_transaction'];
		} else {
			$data['eway_transaction'] = $this->config->get('eway_transaction');
		}

		if (isset($this->request->post['eway_standard_geo_zone_id'])) {
			$data['eway_standard_geo_zone_id'] = $this->request->post['eway_standard_geo_zone_id'];
		} else {
			$data['eway_standard_geo_zone_id'] = $this->config->get('eway_standard_geo_zone_id');
		}

		if (isset($this->request->post['eway_order_status_id'])) {
			$data['eway_order_status_id'] = $this->request->post['eway_order_status_id'];
		} else {
			$data['eway_order_status_id'] = $this->config->get('eway_order_status_id');
		}

		if (isset($this->request->post['eway_order_status_refunded_id'])) {
			$data['eway_order_status_refunded_id'] = $this->request->post['eway_order_status_refunded_id'];
		} else {
			$data['eway_order_status_refunded_id'] = $this->config->get('eway_order_status_refunded_id');
		}

		if (isset($this->request->post['eway_order_status_auth_id'])) {
			$data['eway_order_status_auth_id'] = $this->request->post['eway_order_status_auth_id'];
		} else {
			$data['eway_order_status_auth_id'] = $this->config->get('eway_order_status_auth_id');
		}

		if (isset($this->request->post['eway_order_status_fraud_id'])) {
			$data['eway_order_status_fraud_id'] = $this->request->post['eway_order_status_fraud_id'];
		} else {
			$data['eway_order_status_fraud_id'] = $this->config->get('eway_order_status_fraud_id');
		}

		if (isset($this->request->post['eway_transaction_method'])) {
			$data['eway_transaction_method'] = $this->request->post['eway_transaction_method'];
		} else {
			$data['eway_transaction_method'] = $this->config->get('eway_transaction_method');
		}

		if (isset($this->request->post['eway_username'])) {
			$data['eway_username'] = $this->request->post['eway_username'];
		} else {
			$data['eway_username'] = $this->config->get('eway_username');
		}

		if (isset($this->request->post['eway_password'])) {
			$data['eway_password'] = $this->request->post['eway_password'];
		} else {
			$data['eway_password'] = $this->config->get('eway_password');
		}

		if (isset($this->request->post['eway_status'])) {
			$data['eway_status'] = $this->request->post['eway_status'];
		} else {
			$data['eway_status'] = $this->config->get('eway_status');
		}

		if (isset($this->request->post['eway_sort_order'])) {
			$data['eway_sort_order'] = $this->request->post['eway_sort_order'];
		} else {
			$data['eway_sort_order'] = $this->config->get('eway_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/eway', $data));
	}

	public function install() {
		$this->load->model('extension/payment/eway');
		$this->model_extension_payment_eway->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/eway');
		$this->model_extension_payment_eway->uninstall();
	}

	// Legacy 2.0.0
	public function orderAction() {
		return $this->order();
	}

	// Legacy 2.0.3
	public function action() {
		return $this->order();
	}

	public function order() {
		if ($this->config->get('eway_status')) {
			$this->load->model('extension/payment/eway');

			$eway_order = $this->model_extension_payment_eway->getOrder($this->request->get['order_id']);

			if (!empty($eway_order)) {
				$this->load->language('extension/payment/eway');

				$eway_order['total'] = $eway_order['amount'];
				$eway_order['total_formatted'] = $this->currency->format($eway_order['amount'], $eway_order['currency_code'], 1, true);

				$eway_order['total_captured'] = $this->model_extension_payment_eway->getTotalCaptured($eway_order['eway_order_id']);
				$eway_order['total_captured_formatted'] = $this->currency->format($eway_order['total_captured'], $eway_order['currency_code'], 1, true);

				$eway_order['uncaptured'] = $eway_order['total'] - $eway_order['total_captured'];

				$eway_order['total_refunded'] = $this->model_extension_payment_eway->getTotalRefunded($eway_order['eway_order_id']);
				$eway_order['total_refunded_formatted'] = $this->currency->format($eway_order['total_refunded'], $eway_order['currency_code'], 1, true);

				$eway_order['unrefunded'] = $eway_order['total_captured'] - $eway_order['total_refunded'];

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_void_status'] = $this->language->get('text_void_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_created'] = $this->language->get('text_column_created');
				$data['text_column_transactionid'] = $this->language->get('text_column_transactionid');
				$data['btn_refund'] = $this->language->get('btn_refund');
				$data['btn_capture'] = $this->language->get('btn_capture');
				$data['text_confirm_refund'] = $this->language->get('text_confirm_refund');
				$data['text_confirm_capture'] = $this->language->get('text_confirm_capture');

				$data['text_total_captured'] = $this->language->get('text_total_captured');
				$data['text_total_refunded'] = $this->language->get('text_total_refunded');
				$data['text_capture_status'] = $this->language->get('text_capture_status');
				$data['text_refund_status'] = $this->language->get('text_refund_status');

				$data['text_empty_refund'] = $this->language->get('text_empty_refund');
				$data['text_empty_capture'] = $this->language->get('text_empty_capture');

				$data['eway_order'] = $eway_order;
				$data['token'] = $this->request->get['token'];
				$data['order_id'] = $this->request->get['order_id'];

				return $this->load->view('extension/payment/eway_order', $data);
			}
		}
	}

	public function refund() {
		$this->load->language('extension/payment/eway');

		$order_id = $this->request->post['order_id'];
		$refund_amount = (double)$this->request->post['refund_amount'];

		if ($order_id && $refund_amount > 0) {
			$this->load->model('extension/payment/eway');
			$result = $this->model_extension_payment_eway->refund($order_id, $refund_amount);

			// Check if any error returns
			if (isset($result->Errors) || $result === false) {
				$json['error'] = true;
				$reason = '';
				if ($result === false) {
					$reason = $this->language->get('text_unknown_failure');
				} else {
					$errors = explode(',', $result->Errors);
					foreach ($errors as $error) {
						$reason .= $this->language->get('text_card_message_' . $result->Errors);
					}
				}
				$json['message'] = $this->language->get('text_refund_failed') . $reason;
			} else {
				$eway_order = $this->model_extension_payment_eway->getOrder($order_id);
				$this->model_extension_payment_eway->addTransaction($eway_order['eway_order_id'], $result->Refund->TransactionID, 'refund', $result->Refund->TotalAmount / 100, $eway_order['currency_code']);

				$total_captured = $this->model_extension_payment_eway->getTotalCaptured($eway_order['eway_order_id']);
				$total_refunded = $this->model_extension_payment_eway->getTotalRefunded($eway_order['eway_order_id']);
				$refund_status = 0;

				if ($total_captured == $total_refunded) {
					$refund_status = 1;
					$this->model_extension_payment_eway->updateRefundStatus($eway_order['eway_order_id'], $refund_status);
				}

				$json['data'] = array();
				$json['data']['transactionid'] = $result->TransactionID;
				$json['data']['created'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = number_format($refund_amount, 2, '.', '');
				$json['data']['total_refunded_formatted'] = $this->currency->format($total_refunded, $eway_order['currency_code'], 1, true);
				$json['data']['refund_status'] = $refund_status;
				$json['data']['remaining'] = $total_captured - $total_refunded;
				$json['message'] = $this->language->get('text_refund_success');
				$json['error'] = false;
			}
		} else {
			$json['error'] = true;
			$json['message'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function capture() {
		$this->load->language('extension/payment/eway');

		$order_id = $this->request->post['order_id'];
		$capture_amount = (double)$this->request->post['capture_amount'];

		if ($order_id && $capture_amount > 0) {
			$this->load->model('extension/payment/eway');
			$eway_order = $this->model_extension_payment_eway->getOrder($order_id);
			$result = $this->model_extension_payment_eway->capture($order_id, $capture_amount, $eway_order['currency_code']);

			// Check if any error returns
			if (isset($result->Errors) || $result === false) {
				$json['error'] = true;
				$reason = '';
				if ($result === false) {
					$reason = $this->language->get('text_unknown_failure');
				} else {
					$errors = explode(',', $result->Errors);
					foreach ($errors as $error) {
						$reason .= $this->language->get('text_card_message_' . $result->Errors);
					}
				}
				$json['message'] = $this->language->get('text_capture_failed') . $reason;
			} else {
				$this->model_extension_payment_eway->addTransaction($eway_order['eway_order_id'], $result->TransactionID, 'payment', $capture_amount, $eway_order['currency_code']);

				$total_captured = $this->model_extension_payment_eway->getTotalCaptured($eway_order['eway_order_id']);
				$total_refunded = $this->model_extension_payment_eway->getTotalRefunded($eway_order['eway_order_id']);

				$remaining = $eway_order['amount'] - $capture_amount;
				if ($remaining <= 0) {
					$remaining = 0;
				}

				$this->model_extension_payment_eway->updateCaptureStatus($eway_order['eway_order_id'], 1);
				$this->model_extension_payment_eway->updateTransactionId($eway_order['eway_order_id'], $result->TransactionID);

				$json['data'] = array();
				$json['data']['transactionid'] = $result->TransactionID;
				$json['data']['created'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = number_format($capture_amount, 2, '.', '');
				$json['data']['total_captured_formatted'] = $this->currency->format($total_captured, $eway_order['currency_code'], 1, true);
				$json['data']['capture_status'] = 1;
				$json['data']['remaining'] = $remaining;
				$json['message'] = $this->language->get('text_capture_success');
				$json['error'] = false;
			}
		} else {
			$json['error'] = true;
			$json['message'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/eway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->request->post['eway_username']) {
			$this->error['username'] = $this->language->get('error_username');
		}
		if (!$this->request->post['eway_password']) {
			$this->error['password'] = $this->language->get('error_password');
		}
		if (!isset($this->request->post['eway_payment_type'])) {
			$this->error['payment_type'] = $this->language->get('error_payment_type');
		}

		return !$this->error;
	}

}
