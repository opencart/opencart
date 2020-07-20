<?php
class ControllerExtensionPaymentGlobalpayRemote extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/globalpay_remote');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_globalpay_remote', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['error_merchant_id'])) {
			$data['error_merchant_id'] = $this->error['error_merchant_id'];
		} else {
			$data['error_merchant_id'] = '';
		}

		if (isset($this->error['error_secret'])) {
			$data['error_secret'] = $this->error['error_secret'];
		} else {
			$data['error_secret'] = '';
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
			'href' => $this->url->link('extension/payment/globalpay_remote', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/globalpay_remote', 'user_token=' . $this->session->data['user_token'], true);
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_globalpay_remote_merchant_id'])) {
			$data['payment_globalpay_remote_merchant_id'] = $this->request->post['payment_globalpay_remote_merchant_id'];
		} else {
			$data['payment_globalpay_remote_merchant_id'] = $this->config->get('payment_globalpay_remote_merchant_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_secret'])) {
			$data['payment_globalpay_remote_secret'] = $this->request->post['payment_globalpay_remote_secret'];
		} else {
			$data['payment_globalpay_remote_secret'] = $this->config->get('payment_globalpay_remote_secret');
		}

		if (isset($this->request->post['payment_globalpay_remote_rebate_password'])) {
			$data['payment_globalpay_remote_rebate_password'] = $this->request->post['payment_globalpay_remote_rebate_password'];
		} else {
			$data['payment_globalpay_remote_rebate_password'] = $this->config->get('payment_globalpay_remote_rebate_password');
		}

		if (isset($this->request->post['payment_globalpay_remote_geo_zone_id'])) {
			$data['payment_globalpay_remote_geo_zone_id'] = $this->request->post['payment_globalpay_remote_geo_zone_id'];
		} else {
			$data['payment_globalpay_remote_geo_zone_id'] = $this->config->get('payment_globalpay_remote_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_globalpay_remote_total'])) {
			$data['payment_globalpay_remote_total'] = $this->request->post['payment_globalpay_remote_total'];
		} else {
			$data['payment_globalpay_remote_total'] = $this->config->get('payment_globalpay_remote_total');
		}

		if (isset($this->request->post['payment_globalpay_remote_sort_order'])) {
			$data['payment_globalpay_remote_sort_order'] = $this->request->post['payment_globalpay_remote_sort_order'];
		} else {
			$data['payment_globalpay_remote_sort_order'] = $this->config->get('payment_globalpay_remote_sort_order');
		}

		if (isset($this->request->post['payment_globalpay_remote_status'])) {
			$data['payment_globalpay_remote_status'] = $this->request->post['payment_globalpay_remote_status'];
		} else {
			$data['payment_globalpay_remote_status'] = $this->config->get('payment_globalpay_remote_status');
		}

		if (isset($this->request->post['payment_globalpay_remote_card_data_status'])) {
			$data['payment_globalpay_remote_card_data_status'] = $this->request->post['payment_globalpay_remote_card_data_status'];
		} else {
			$data['payment_globalpay_remote_card_data_status'] = $this->config->get('payment_globalpay_remote_card_data_status');
		}

		if (isset($this->request->post['payment_globalpay_remote_debug'])) {
			$data['payment_globalpay_remote_debug'] = $this->request->post['payment_globalpay_remote_debug'];
		} else {
			$data['payment_globalpay_remote_debug'] = $this->config->get('payment_globalpay_remote_debug');
		}

		if (isset($this->request->post['payment_globalpay_remote_account'])) {
			$data['payment_globalpay_remote_account'] = $this->request->post['payment_globalpay_remote_account'];
		} else {
			$data['payment_globalpay_remote_account'] = $this->config->get('payment_globalpay_remote_account');
		}

		if (isset($this->request->post['payment_globalpay_remote_auto_settle'])) {
			$data['payment_globalpay_remote_auto_settle'] = $this->request->post['payment_globalpay_remote_auto_settle'];
		} else {
			$data['payment_globalpay_remote_auto_settle'] = $this->config->get('payment_globalpay_remote_auto_settle');
		}

		if (isset($this->request->post['payment_globalpay_remote_tss_check'])) {
			$data['payment_globalpay_remote_tss_check'] = $this->request->post['payment_globalpay_remote_tss_check'];
		} else {
			$data['payment_globalpay_remote_tss_check'] = $this->config->get('payment_globalpay_remote_tss_check');
		}

		if (isset($this->request->post['payment_globalpay_remote_3d'])) {
			$data['payment_globalpay_remote_3d'] = $this->request->post['payment_globalpay_remote_3d'];
		} else {
			$data['payment_globalpay_remote_3d'] = $this->config->get('payment_globalpay_remote_3d');
		}

		if (isset($this->request->post['payment_globalpay_remote_liability'])) {
			$data['payment_globalpay_remote_liability'] = $this->request->post['payment_globalpay_remote_liability'];
		} else {
			$data['payment_globalpay_remote_liability'] = $this->config->get('payment_globalpay_remote_liability');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_success_settled_id'])) {
			$data['payment_globalpay_remote_order_status_success_settled_id'] = $this->request->post['payment_globalpay_remote_order_status_success_settled_id'];
		} else {
			$data['payment_globalpay_remote_order_status_success_settled_id'] = $this->config->get('payment_globalpay_remote_order_status_success_settled_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_success_unsettled_id'])) {
			$data['payment_globalpay_remote_order_status_success_unsettled_id'] = $this->request->post['payment_globalpay_remote_order_status_success_unsettled_id'];
		} else {
			$data['payment_globalpay_remote_order_status_success_unsettled_id'] = $this->config->get('payment_globalpay_remote_order_status_success_unsettled_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_decline_id'])) {
			$data['payment_globalpay_remote_order_status_decline_id'] = $this->request->post['payment_globalpay_remote_order_status_decline_id'];
		} else {
			$data['payment_globalpay_remote_order_status_decline_id'] = $this->config->get('payment_globalpay_remote_order_status_decline_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_decline_pending_id'])) {
			$data['payment_globalpay_remote_order_status_decline_pending_id'] = $this->request->post['payment_globalpay_remote_order_status_decline_pending_id'];
		} else {
			$data['payment_globalpay_remote_order_status_decline_pending_id'] = $this->config->get('payment_globalpay_remote_order_status_decline_pending_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_decline_stolen_id'])) {
			$data['payment_globalpay_remote_order_status_decline_stolen_id'] = $this->request->post['payment_globalpay_remote_order_status_decline_stolen_id'];
		} else {
			$data['payment_globalpay_remote_order_status_decline_stolen_id'] = $this->config->get('payment_globalpay_remote_order_status_decline_stolen_id');
		}

		if (isset($this->request->post['payment_globalpay_remote_order_status_decline_bank_id'])) {
			$data['payment_globalpay_remote_order_status_decline_bank_id'] = $this->request->post['payment_globalpay_remote_order_status_decline_bank_id'];
		} else {
			$data['payment_globalpay_remote_order_status_decline_bank_id'] = $this->config->get('payment_globalpay_remote_order_status_decline_bank_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/globalpay_remote', $data));
	}

	public function install() {
		$this->load->model('extension/payment/globalpay_remote');
		$this->model_extension_payment_globalpay_remote->install();
	}

	public function order() {
		if ($this->config->get('payment_globalpay_remote_status')) {
			$this->load->model('extension/payment/globalpay_remote');

			$globalpay_order = $this->model_extension_payment_globalpay_remote->getOrder($this->request->get['order_id']);

			if (!empty($globalpay_order)) {
				$this->load->language('extension/payment/globalpay_remote');

				$globalpay_order['total_captured'] = $this->model_extension_payment_globalpay_remote->getTotalCaptured($globalpay_order['globalpay_remote_order_id']);

				$globalpay_order['total_formatted'] = $this->currency->format($globalpay_order['total'], $globalpay_order['currency_code'], 1, true);
				$globalpay_order['total_captured_formatted'] = $this->currency->format($globalpay_order['total_captured'], $globalpay_order['currency_code'], 1, true);

				$data['globalpay_order'] = $globalpay_order;

				$data['auto_settle'] = $globalpay_order['settle_type'];

				$data['order_id'] = $this->request->get['order_id'];
				
				$data['user_token'] = $this->session->data['user_token'];

				return $this->load->view('extension/payment/globalpay_remote_order', $data);
			}
		}
	}

	public function void() {
		$this->load->language('extension/payment/globalpay_remote');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('extension/payment/globalpay_remote');

			$globalpay_order = $this->model_extension_payment_globalpay_remote->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_payment_globalpay_remote->void($this->request->post['order_id']);

			$this->model_extension_payment_globalpay_remote->logger('Void result:\r\n' . print_r($void_response, 1));

			if (isset($void_response->result) && $void_response->result == '00') {
				$this->model_extension_payment_globalpay_remote->addTransaction($globalpay_order['globalpay_remote_order_id'], 'void', 0.00);
				$this->model_extension_payment_globalpay_remote->updateVoidStatus($globalpay_order['globalpay_remote_order_id'], 1);

				$json['msg'] = $this->language->get('text_void_ok');
				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($void_response->message) && !empty($void_response->message) ? (string)$void_response->message : 'Unable to void';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function capture() {
		$this->load->language('extension/payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('extension/payment/globalpay_remote');

			$globalpay_order = $this->model_extension_payment_globalpay_remote->getOrder($this->request->post['order_id']);

			$capture_response = $this->model_extension_payment_globalpay_remote->capture($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_globalpay_remote->logger('Settle result:\r\n' . print_r($capture_response, 1));

			if (isset($capture_response->result) && $capture_response->result == '00') {
				$this->model_extension_payment_globalpay_remote->addTransaction($globalpay_order['globalpay_remote_order_id'], 'payment', $this->request->post['amount']);
				$total_captured = $this->model_extension_payment_globalpay_remote->getTotalCaptured($globalpay_order['globalpay_remote_order_id']);

				if ($total_captured >= $globalpay_order['total'] || $globalpay_order['settle_type'] == 0) {
					$this->model_extension_payment_globalpay_remote->updateCaptureStatus($globalpay_order['globalpay_remote_order_id'], 1);
					$capture_status = 1;
					$json['msg'] = $this->language->get('text_capture_ok_order');
				} else {
					$capture_status = 0;
					$json['msg'] = $this->language->get('text_capture_ok');
				}

				$this->model_extension_payment_globalpay_remote->updateForRebate($globalpay_order['globalpay_remote_order_id'], $capture_response->pasref, $capture_response->orderid);

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = (float)$this->request->post['amount'];
				$json['data']['capture_status'] = $capture_status;
				$json['data']['total'] = (float)$total_captured;
				$json['data']['total_formatted'] = $this->currency->format($total_captured, $globalpay_order['currency_code'], 1, true);
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($capture_response->message) && !empty($capture_response->message) ? (string)$capture_response->message : 'Unable to capture';

			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rebate() {
		$this->load->language('extension/payment/globalpay_remote');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('extension/payment/globalpay_remote');

			$globalpay_order = $this->model_extension_payment_globalpay_remote->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_payment_globalpay_remote->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_globalpay_remote->logger('Rebate result:\r\n' . print_r($rebate_response, 1));

			if (isset($rebate_response->result) && $rebate_response->result == '00') {
				$this->model_extension_payment_globalpay_remote->addTransaction($globalpay_order['globalpay_remote_order_id'], 'rebate', $this->request->post['amount']*-1);

				$total_rebated = $this->model_extension_payment_globalpay_remote->getTotalRebated($globalpay_order['globalpay_remote_order_id']);
				$total_captured = $this->model_extension_payment_globalpay_remote->getTotalCaptured($globalpay_order['globalpay_remote_order_id']);

				if ($total_captured <= 0 && $globalpay_order['capture_status'] == 1) {
					$this->model_extension_payment_globalpay_remote->updateRebateStatus($globalpay_order['globalpay_remote_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('text_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('text_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'] * -1;
				$json['data']['total_captured'] = (float)$total_captured;
				$json['data']['total_rebated'] = (float)$total_rebated;
				$json['data']['rebate_status'] = $rebate_status;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($rebate_response->message) && !empty($rebate_response->message) ? (string)$rebate_response->message : 'Unable to rebate';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/globalpay_remote')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_globalpay_remote_merchant_id']) {
			$this->error['error_merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['payment_globalpay_remote_secret']) {
			$this->error['error_secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}