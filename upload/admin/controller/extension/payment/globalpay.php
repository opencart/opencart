<?php
class ControllerExtensionPaymentGlobalpay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/globalpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_globalpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		$data['notify_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/globalpay/notify';

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

		if (isset($this->error['error_live_url'])) {
			$data['error_live_url'] = $this->error['error_live_url'];
		} else {
			$data['error_live_url'] = '';
		}

		if (isset($this->error['error_demo_url'])) {
			$data['error_demo_url'] = $this->error['error_demo_url'];
		} else {
			$data['error_demo_url'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/globalpay', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/globalpay', 'user_token=' . $this->session->data['user_token']);
		
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_globalpay_merchant_id'])) {
			$data['payment_globalpay_merchant_id'] = $this->request->post['payment_globalpay_merchant_id'];
		} else {
			$data['payment_globalpay_merchant_id'] = $this->config->get('payment_globalpay_merchant_id');
		}

		if (isset($this->request->post['payment_globalpay_secret'])) {
			$data['payment_globalpay_secret'] = $this->request->post['payment_globalpay_secret'];
		} else {
			$data['payment_globalpay_secret'] = $this->config->get('payment_globalpay_secret');
		}

		if (isset($this->request->post['payment_globalpay_rebate_password'])) {
			$data['payment_globalpay_rebate_password'] = $this->request->post['payment_globalpay_rebate_password'];
		} else {
			$data['payment_globalpay_rebate_password'] = $this->config->get('payment_globalpay_rebate_password');
		}

		if (isset($this->request->post['payment_globalpay_live_demo'])) {
			$data['payment_globalpay_live_demo'] = $this->request->post['payment_globalpay_live_demo'];
		} else {
			$data['payment_globalpay_live_demo'] = $this->config->get('payment_globalpay_live_demo');
		}

		if (isset($this->request->post['payment_globalpay_geo_zone_id'])) {
			$data['payment_globalpay_geo_zone_id'] = $this->request->post['payment_globalpay_geo_zone_id'];
		} else {
			$data['payment_globalpay_geo_zone_id'] = $this->config->get('payment_globalpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_globalpay_total'])) {
			$data['payment_globalpay_total'] = $this->request->post['payment_globalpay_total'];
		} else {
			$data['payment_globalpay_total'] = $this->config->get('payment_globalpay_total');
		}

		if (isset($this->request->post['payment_globalpay_sort_order'])) {
			$data['payment_globalpay_sort_order'] = $this->request->post['payment_globalpay_sort_order'];
		} else {
			$data['payment_globalpay_sort_order'] = $this->config->get('payment_globalpay_sort_order');
		}

		if (isset($this->request->post['payment_globalpay_status'])) {
			$data['payment_globalpay_status'] = $this->request->post['payment_globalpay_status'];
		} else {
			$data['payment_globalpay_status'] = $this->config->get('payment_globalpay_status');
		}

		if (isset($this->request->post['payment_globalpay_debug'])) {
			$data['payment_globalpay_debug'] = $this->request->post['payment_globalpay_debug'];
		} else {
			$data['payment_globalpay_debug'] = $this->config->get('payment_globalpay_debug');
		}

		if (isset($this->request->post['payment_globalpay_account'])) {
			$data['payment_globalpay_account'] = $this->request->post['payment_globalpay_account'];
		} else {
			$data['payment_globalpay_account'] = $this->config->get('payment_globalpay_account');
		}

		if (isset($this->request->post['payment_globalpay_auto_settle'])) {
			$data['payment_globalpay_auto_settle'] = $this->request->post['payment_globalpay_auto_settle'];
		} else {
			$data['payment_globalpay_auto_settle'] = $this->config->get('payment_globalpay_auto_settle');
		}

		if (isset($this->request->post['payment_globalpay_card_select'])) {
			$data['payment_globalpay_card_select'] = $this->request->post['payment_globalpay_card_select'];
		} else {
			$data['payment_globalpay_card_select'] = $this->config->get('payment_globalpay_card_select');
		}

		if (isset($this->request->post['payment_globalpay_tss_check'])) {
			$data['payment_globalpay_tss_check'] = $this->request->post['payment_globalpay_tss_check'];
		} else {
			$data['payment_globalpay_tss_check'] = $this->config->get('payment_globalpay_tss_check');
		}

		if (isset($this->request->post['globalpay_order_status_success_settled_id'])) {
			$data['globalpay_order_status_success_settled_id'] = $this->request->post['globalpay_order_status_success_settled_id'];
		} else {
			$data['globalpay_order_status_success_settled_id'] = $this->config->get('globalpay_order_status_success_settled_id');
		}

		if (isset($this->request->post['payment_globalpay_order_status_success_unsettled_id'])) {
			$data['payment_globalpay_order_status_success_unsettled_id'] = $this->request->post['payment_globalpay_order_status_success_unsettled_id'];
		} else {
			$data['payment_globalpay_order_status_success_unsettled_id'] = $this->config->get('payment_globalpay_order_status_success_unsettled_id');
		}

		if (isset($this->request->post['payment_globalpay_order_status_decline_id'])) {
			$data['payment_globalpay_order_status_decline_id'] = $this->request->post['payment_globalpay_order_status_decline_id'];
		} else {
			$data['payment_globalpay_order_status_decline_id'] = $this->config->get('payment_globalpay_order_status_decline_id');
		}

		if (isset($this->request->post['payment_globalpay_order_status_decline_pending_id'])) {
			$data['payment_globalpay_order_status_decline_pending_id'] = $this->request->post['payment_globalpay_order_status_decline_pending_id'];
		} else {
			$data['payment_globalpay_order_status_decline_pending_id'] = $this->config->get('payment_globalpay_order_status_decline_pending_id');
		}

		if (isset($this->request->post['payment_globalpay_order_status_decline_stolen_id'])) {
			$data['payment_globalpay_order_status_decline_stolen_id'] = $this->request->post['payment_globalpay_order_status_decline_stolen_id'];
		} else {
			$data['payment_globalpay_order_status_decline_stolen_id'] = $this->config->get('payment_globalpay_order_status_decline_stolen_id');
		}

		if (isset($this->request->post['payment_globalpay_order_status_decline_bank_id'])) {
			$data['payment_globalpay_order_status_decline_bank_id'] = $this->request->post['payment_globalpay_order_status_decline_bank_id'];
		} else {
			$data['payment_globalpay_order_status_decline_bank_id'] = $this->config->get('payment_globalpay_order_status_decline_bank_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_globalpay_live_url'])) {
			$data['payment_globalpay_live_url'] = $this->request->post['payment_globalpay_live_url'];
		} else {
			$data['payment_globalpay_live_url'] = $this->config->get('payment_globalpay_live_url');
		}

		if (empty($data['payment_globalpay_live_url'])) {
			$data['payment_globalpay_live_url'] = 'https://hpp.globaliris.com/pay';
		}

		if (isset($this->request->post['payment_globalpay_demo_url'])) {
			$data['payment_globalpay_demo_url'] = $this->request->post['payment_globalpay_demo_url'];
		} else {
			$data['payment_globalpay_demo_url'] = $this->config->get('payment_globalpay_demo_url');
		}

		if (empty($data['payment_globalpay_demo_url'])) {
			$data['payment_globalpay_demo_url'] = 'https://hpp.sandbox.globaliris.com/pay';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/globalpay', $data));
	}

	public function install() {
		$this->load->model('extension/payment/globalpay');

		$this->model_extension_payment_globalpay->install();
	}

	public function order() {
		if ($this->config->get('payment_globalpay_status')) {
			$this->load->model('extension/payment/globalpay');

			$globalpay_order = $this->model_extension_payment_globalpay->getOrder($this->request->get['order_id']);

			if (!empty($globalpay_order)) {
				$this->load->language('extension/payment/globalpay');

				$globalpay_order['total_captured'] = $this->model_extension_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				$globalpay_order['total_formatted'] = $this->currency->format($globalpay_order['total'], $globalpay_order['currency_code'], 1, true);
				$globalpay_order['total_captured_formatted'] = $this->currency->format($globalpay_order['total_captured'], $globalpay_order['currency_code'], 1, true);

				$data['globalpay_order'] = $globalpay_order;

				$data['auto_settle'] = $globalpay_order['settle_type'];

				$data['order_id'] = (int)$this->request->get['order_id'];
				
				$data['user_token'] = $this->session->data['user_token'];

				return $this->load->view('extension/payment/globalpay_order', $data);
			}
		}
	}

	public function void() {
		$this->load->language('extension/payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('extension/payment/globalpay');

			$globalpay_order = $this->model_extension_payment_globalpay->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_payment_globalpay->void($this->request->post['order_id']);

			$this->model_extension_payment_globalpay->logger('Void result:\r\n' . print_r($void_response, 1));

			if (isset($void_response->result) && $void_response->result == '00') {
				$this->model_extension_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'void', 0.00);
				$this->model_extension_payment_globalpay->updateVoidStatus($globalpay_order['globalpay_order_id'], 1);

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
			$this->load->model('extension/payment/globalpay');

			$globalpay_order = $this->model_extension_payment_globalpay->getOrder($this->request->post['order_id']);

			$capture_response = $this->model_extension_payment_globalpay->capture($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_globalpay->logger('Settle result:\r\n' . print_r($capture_response, 1));

			if (isset($capture_response->result) && $capture_response->result == '00') {
				$this->model_extension_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'payment', $this->request->post['amount']);

				$total_captured = $this->model_extension_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				if ($total_captured >= $globalpay_order['total'] || $globalpay_order['settle_type'] == 0) {
					$this->model_extension_payment_globalpay->updateCaptureStatus($globalpay_order['globalpay_order_id'], 1);
					$capture_status = 1;
					$json['msg'] = $this->language->get('text_capture_ok_order');
				} else {
					$capture_status = 0;
					$json['msg'] = $this->language->get('text_capture_ok');
				}

				$this->model_extension_payment_globalpay->updateForRebate($globalpay_order['globalpay_order_id'], $capture_response->pasref, $capture_response->orderid);

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'];
				$json['data']['capture_status'] = $capture_status;
				$json['data']['total'] = (float)$total_captured;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($capture_response->message) && !empty($capture_response->message) ? (string)$capture_response->message : 'Unable to capture';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rebate() {
		$this->load->language('extension/payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/globalpay');

			$globalpay_order = $this->model_extension_payment_globalpay->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_payment_globalpay->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_globalpay->logger('Rebate result:\r\n' . print_r($rebate_response, 1));

			if (isset($rebate_response->result) && $rebate_response->result == '00') {
				$this->model_extension_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'rebate', $this->request->post['amount']*-1);

				$total_rebated = $this->model_extension_payment_globalpay->getTotalRebated($globalpay_order['globalpay_order_id']);
				$total_captured = $this->model_extension_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				if ($total_captured <= 0 && $globalpay_order['capture_status'] == 1) {
					$this->model_extension_payment_globalpay->updateRebateStatus($globalpay_order['globalpay_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('text_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('text_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount']*-1;
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
		if (!$this->user->hasPermission('modify', 'extension/payment/globalpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_globalpay_merchant_id']) {
			$this->error['error_merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['payment_globalpay_secret']) {
			$this->error['error_secret'] = $this->language->get('error_secret');
		}

		if (!$this->request->post['payment_globalpay_live_url']) {
			$this->error['error_live_url'] = $this->language->get('error_live_url');
		}

		if (!$this->request->post['payment_globalpay_demo_url']) {
			$this->error['error_demo_url'] = $this->language->get('error_demo_url');
		}

		return !$this->error;
	}
}