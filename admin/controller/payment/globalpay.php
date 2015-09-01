<?php
class ControllerPaymentGlobalpay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/globalpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('globalpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_demo'] = $this->language->get('text_demo');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_card_type'] = $this->language->get('text_card_type');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_use_default'] = $this->language->get('text_use_default');
		$data['text_merchant_id'] = $this->language->get('text_merchant_id');
		$data['text_subaccount'] = $this->language->get('text_subaccount');
		$data['text_secret'] = $this->language->get('text_secret');
		$data['text_card_visa'] = $this->language->get('text_card_visa');
		$data['text_card_master'] = $this->language->get('text_card_master');
		$data['text_card_amex'] = $this->language->get('text_card_amex');
		$data['text_card_switch'] = $this->language->get('text_card_switch');
		$data['text_card_laser'] = $this->language->get('text_card_laser');
		$data['text_card_diners'] = $this->language->get('text_card_diners');
		$data['text_settle_delayed'] = $this->language->get('text_settle_delayed');
		$data['text_settle_auto'] = $this->language->get('text_settle_auto');
		$data['text_settle_multi'] = $this->language->get('text_settle_multi');
		$data['text_url_message'] = $this->language->get('text_url_message');

		$data['entry_merchant_id'] = $this->language->get('entry_merchant_id');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_rebate_password'] = $this->language->get('entry_rebate_password');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_live_demo'] = $this->language->get('entry_live_demo');
		$data['entry_auto_settle'] = $this->language->get('entry_auto_settle');
		$data['entry_card_select'] = $this->language->get('entry_card_select');
		$data['entry_tss_check'] = $this->language->get('entry_tss_check');
		$data['entry_live_url'] = $this->language->get('entry_live_url');
		$data['entry_demo_url'] = $this->language->get('entry_demo_url');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['entry_status_success_settled'] = $this->language->get('entry_status_success_settled');
		$data['entry_status_success_unsettled'] = $this->language->get('entry_status_success_unsettled');
		$data['entry_status_decline'] = $this->language->get('entry_status_decline');
		$data['entry_status_decline_pending'] = $this->language->get('entry_status_decline_pending');
		$data['entry_status_decline_stolen'] = $this->language->get('entry_status_decline_stolen');
		$data['entry_status_decline_bank'] = $this->language->get('entry_status_decline_bank');
		$data['entry_status_void'] = $this->language->get('entry_status_void');
		$data['entry_status_rebate'] = $this->language->get('entry_status_rebate');
		$data['entry_notification_url'] = $this->language->get('entry_notification_url');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_card_select'] = $this->language->get('help_card_select');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_dcc_settle'] = $this->language->get('help_dcc_settle');
		$data['help_notification'] = $this->language->get('help_notification');

		$data['tab_api'] = $this->language->get('tab_api');
		$data['tab_account'] = $this->language->get('tab_account');
		$data['tab_order_status'] = $this->language->get('tab_order_status');
		$data['tab_payment'] = $this->language->get('tab_payment');
		$data['tab_advanced'] = $this->language->get('tab_advanced');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['error_use_select_card'] = $this->language->get('error_use_select_card');

		$data['notify_url'] = HTTPS_CATALOG . 'index.php?route=payment/globalpay/notify';

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/globalpay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/globalpay', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['globalpay_merchant_id'])) {
			$data['globalpay_merchant_id'] = $this->request->post['globalpay_merchant_id'];
		} else {
			$data['globalpay_merchant_id'] = $this->config->get('globalpay_merchant_id');
		}

		if (isset($this->request->post['globalpay_secret'])) {
			$data['globalpay_secret'] = $this->request->post['globalpay_secret'];
		} else {
			$data['globalpay_secret'] = $this->config->get('globalpay_secret');
		}

		if (isset($this->request->post['globalpay_rebate_password'])) {
			$data['globalpay_rebate_password'] = $this->request->post['globalpay_rebate_password'];
		} else {
			$data['globalpay_rebate_password'] = $this->config->get('globalpay_rebate_password');
		}

		if (isset($this->request->post['globalpay_live_demo'])) {
			$data['globalpay_live_demo'] = $this->request->post['globalpay_live_demo'];
		} else {
			$data['globalpay_live_demo'] = $this->config->get('globalpay_live_demo');
		}

		if (isset($this->request->post['globalpay_geo_zone_id'])) {
			$data['globalpay_geo_zone_id'] = $this->request->post['globalpay_geo_zone_id'];
		} else {
			$data['globalpay_geo_zone_id'] = $this->config->get('globalpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['globalpay_total'])) {
			$data['globalpay_total'] = $this->request->post['globalpay_total'];
		} else {
			$data['globalpay_total'] = $this->config->get('globalpay_total');
		}

		if (isset($this->request->post['globalpay_sort_order'])) {
			$data['globalpay_sort_order'] = $this->request->post['globalpay_sort_order'];
		} else {
			$data['globalpay_sort_order'] = $this->config->get('globalpay_sort_order');
		}

		if (isset($this->request->post['globalpay_status'])) {
			$data['globalpay_status'] = $this->request->post['globalpay_status'];
		} else {
			$data['globalpay_status'] = $this->config->get('globalpay_status');
		}

		if (isset($this->request->post['globalpay_debug'])) {
			$data['globalpay_debug'] = $this->request->post['globalpay_debug'];
		} else {
			$data['globalpay_debug'] = $this->config->get('globalpay_debug');
		}

		if (isset($this->request->post['globalpay_account'])) {
			$data['globalpay_account'] = $this->request->post['globalpay_account'];
		} else {
			$data['globalpay_account'] = $this->config->get('globalpay_account');
		}

		if (isset($this->request->post['globalpay_auto_settle'])) {
			$data['globalpay_auto_settle'] = $this->request->post['globalpay_auto_settle'];
		} else {
			$data['globalpay_auto_settle'] = $this->config->get('globalpay_auto_settle');
		}

		if (isset($this->request->post['globalpay_card_select'])) {
			$data['globalpay_card_select'] = $this->request->post['globalpay_card_select'];
		} else {
			$data['globalpay_card_select'] = $this->config->get('globalpay_card_select');
		}

		if (isset($this->request->post['globalpay_tss_check'])) {
			$data['globalpay_tss_check'] = $this->request->post['globalpay_tss_check'];
		} else {
			$data['globalpay_tss_check'] = $this->config->get('globalpay_tss_check');
		}

		if (isset($this->request->post['globalpay_order_status_success_settled_id'])) {
			$data['globalpay_order_status_success_settled_id'] = $this->request->post['globalpay_order_status_success_settled_id'];
		} else {
			$data['globalpay_order_status_success_settled_id'] = $this->config->get('globalpay_order_status_success_settled_id');
		}

		if (isset($this->request->post['globalpay_order_status_success_unsettled_id'])) {
			$data['globalpay_order_status_success_unsettled_id'] = $this->request->post['globalpay_order_status_success_unsettled_id'];
		} else {
			$data['globalpay_order_status_success_unsettled_id'] = $this->config->get('globalpay_order_status_success_unsettled_id');
		}

		if (isset($this->request->post['globalpay_order_status_decline_id'])) {
			$data['globalpay_order_status_decline_id'] = $this->request->post['globalpay_order_status_decline_id'];
		} else {
			$data['globalpay_order_status_decline_id'] = $this->config->get('globalpay_order_status_decline_id');
		}

		if (isset($this->request->post['globalpay_order_status_decline_pending_id'])) {
			$data['globalpay_order_status_decline_pending_id'] = $this->request->post['globalpay_order_status_decline_pending_id'];
		} else {
			$data['globalpay_order_status_decline_pending_id'] = $this->config->get('globalpay_order_status_decline_pending_id');
		}

		if (isset($this->request->post['globalpay_order_status_decline_stolen_id'])) {
			$data['globalpay_order_status_decline_stolen_id'] = $this->request->post['globalpay_order_status_decline_stolen_id'];
		} else {
			$data['globalpay_order_status_decline_stolen_id'] = $this->config->get('globalpay_order_status_decline_stolen_id');
		}

		if (isset($this->request->post['globalpay_order_status_decline_bank_id'])) {
			$data['globalpay_order_status_decline_bank_id'] = $this->request->post['globalpay_order_status_decline_bank_id'];
		} else {
			$data['globalpay_order_status_decline_bank_id'] = $this->config->get('globalpay_order_status_decline_bank_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['globalpay_live_url'])) {
			$data['globalpay_live_url'] = $this->request->post['globalpay_live_url'];
		} else {
			$data['globalpay_live_url'] = $this->config->get('globalpay_live_url');
		}

		if (empty($data['globalpay_live_url'])) {
			$data['globalpay_live_url'] = 'https://hpp.globaliris.com/pay';
		}

		if (isset($this->request->post['globalpay_demo_url'])) {
			$data['globalpay_demo_url'] = $this->request->post['globalpay_demo_url'];
		} else {
			$data['globalpay_demo_url'] = $this->config->get('globalpay_demo_url');
		}

		if (empty($data['globalpay_demo_url'])) {
			$data['globalpay_demo_url'] = 'https://hpp.sandbox.globaliris.com/pay';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/globalpay.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/globalpay');

		$this->model_payment_globalpay->install();
	}

	public function action() {
		if ($this->config->get('globalpay_status')) {
			$this->load->model('payment/globalpay');

			$globalpay_order = $this->model_payment_globalpay->getOrder($this->request->get['order_id']);

			if (!empty($globalpay_order)) {
				$this->load->language('payment/globalpay');

				$globalpay_order['total_captured'] = $this->model_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				$globalpay_order['total_formatted'] = $this->currency->format($globalpay_order['total'], $globalpay_order['currency_code'], 1, true);
				$globalpay_order['total_captured_formatted'] = $this->currency->format($globalpay_order['total_captured'], $globalpay_order['currency_code'], 1, true);

				$data['globalpay_order'] = $globalpay_order;

				$data['auto_settle'] = $globalpay_order['settle_type'];

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_ref'] = $this->language->get('text_order_ref');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_total_captured'] = $this->language->get('text_total_captured');
				$data['text_capture_status'] = $this->language->get('text_capture_status');
				$data['text_void_status'] = $this->language->get('text_void_status');
				$data['text_rebate_status'] = $this->language->get('text_rebate_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_yes'] = $this->language->get('text_yes');
				$data['text_no'] = $this->language->get('text_no');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_date_added'] = $this->language->get('text_column_date_added');
				$data['button_capture'] = $this->language->get('button_capture');
				$data['button_rebate'] = $this->language->get('button_rebate');
				$data['button_void'] = $this->language->get('button_void');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_confirm_capture'] = $this->language->get('text_confirm_capture');
				$data['text_confirm_rebate'] = $this->language->get('text_confirm_rebate');

				$data['order_id'] = $this->request->get['order_id'];
				$data['token'] = $this->request->get['token'];

				return $this->load->view('payment/globalpay_order.tpl', $data);
			}
		}
	}

	public function void() {
		$this->load->language('payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('payment/globalpay');

			$globalpay_order = $this->model_payment_globalpay->getOrder($this->request->post['order_id']);

			$void_response = $this->model_payment_globalpay->void($this->request->post['order_id']);

			$this->model_payment_globalpay->logger('Void result:\r\n' . print_r($void_response, 1));

			if (isset($void_response->result) && $void_response->result == '00') {
				$this->model_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'void', 0.00);
				$this->model_payment_globalpay->updateVoidStatus($globalpay_order['globalpay_order_id'], 1);

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
		$this->load->language('payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('payment/globalpay');

			$globalpay_order = $this->model_payment_globalpay->getOrder($this->request->post['order_id']);

			$capture_response = $this->model_payment_globalpay->capture($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_globalpay->logger('Settle result:\r\n' . print_r($capture_response, 1));

			if (isset($capture_response->result) && $capture_response->result == '00') {
				$this->model_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'payment', $this->request->post['amount']);

				$total_captured = $this->model_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				if ($total_captured >= $globalpay_order['total'] || $globalpay_order['settle_type'] == 0) {
					$this->model_payment_globalpay->updateCaptureStatus($globalpay_order['globalpay_order_id'], 1);
					$capture_status = 1;
					$json['msg'] = $this->language->get('text_capture_ok_order');
				} else {
					$capture_status = 0;
					$json['msg'] = $this->language->get('text_capture_ok');
				}

				$this->model_payment_globalpay->updateForRebate($globalpay_order['globalpay_order_id'], $capture_response->pasref, $capture_response->orderid);

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
		$this->load->language('payment/globalpay');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('payment/globalpay');

			$globalpay_order = $this->model_payment_globalpay->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_payment_globalpay->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_globalpay->logger('Rebate result:\r\n' . print_r($rebate_response, 1));

			if (isset($rebate_response->result) && $rebate_response->result == '00') {
				$this->model_payment_globalpay->addTransaction($globalpay_order['globalpay_order_id'], 'rebate', $this->request->post['amount']*-1);

				$total_rebated = $this->model_payment_globalpay->getTotalRebated($globalpay_order['globalpay_order_id']);
				$total_captured = $this->model_payment_globalpay->getTotalCaptured($globalpay_order['globalpay_order_id']);

				if ($total_captured <= 0 && $globalpay_order['capture_status'] == 1) {
					$this->model_payment_globalpay->updateRebateStatus($globalpay_order['globalpay_order_id'], 1);
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
		if (!$this->user->hasPermission('modify', 'payment/globalpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['globalpay_merchant_id']) {
			$this->error['error_merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['globalpay_secret']) {
			$this->error['error_secret'] = $this->language->get('error_secret');
		}

		if (!$this->request->post['globalpay_live_url']) {
			$this->error['error_live_url'] = $this->language->get('error_live_url');
		}

		if (!$this->request->post['globalpay_demo_url']) {
			$this->error['error_demo_url'] = $this->language->get('error_demo_url');
		}

		return !$this->error;
	}
}