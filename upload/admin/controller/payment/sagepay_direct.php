<?php
class ControllerPaymentSagepayDirect extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/sagepay_direct');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('sagepay_direct', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_sim'] = $this->language->get('text_sim');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_defered'] = $this->language->get('text_defered');
		$data['text_authenticate'] = $this->language->get('text_authenticate');

		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_cron_job_token'] = $this->language->get('entry_cron_job_token');
		$data['entry_cron_job_url'] = $this->language->get('entry_cron_job_url');
		$data['entry_last_cron_job_run'] = $this->language->get('entry_last_cron_job_run');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_transaction'] = $this->language->get('help_transaction');
		$data['help_cron_job_token'] = $this->language->get('help_cron_job_token');
		$data['help_cron_job_url'] = $this->language->get('help_cron_job_url');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['vendor'])) {
			$data['error_vendor'] = $this->error['vendor'];
		} else {
			$data['error_vendor'] = '';
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
			'href' => $this->url->link('payment/sagepay_direct', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/sagepay_direct', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['sagepay_direct_vendor'])) {
			$data['sagepay_direct_vendor'] = $this->request->post['sagepay_direct_vendor'];
		} else {
			$data['sagepay_direct_vendor'] = $this->config->get('sagepay_direct_vendor');
		}

		if (isset($this->request->post['sagepay_direct_password'])) {
			$data['sagepay_direct_password'] = $this->request->post['sagepay_direct_password'];
		} else {
			$data['sagepay_direct_password'] = $this->config->get('sagepay_direct_password');
		}

		if (isset($this->request->post['sagepay_direct_test'])) {
			$data['sagepay_direct_test'] = $this->request->post['sagepay_direct_test'];
		} else {
			$data['sagepay_direct_test'] = $this->config->get('sagepay_direct_test');
		}

		if (isset($this->request->post['sagepay_direct_transaction'])) {
			$data['sagepay_direct_transaction'] = $this->request->post['sagepay_direct_transaction'];
		} else {
			$data['sagepay_direct_transaction'] = $this->config->get('sagepay_direct_transaction');
		}

		if (isset($this->request->post['sagepay_direct_total'])) {
			$data['sagepay_direct_total'] = $this->request->post['sagepay_direct_total'];
		} else {
			$data['sagepay_direct_total'] = $this->config->get('sagepay_direct_total');
		}

		if (isset($this->request->post['sagepay_direct_card'])) {
			$data['sagepay_direct_card'] = $this->request->post['sagepay_direct_card'];
		} else {
			$data['sagepay_direct_card'] = $this->config->get('sagepay_direct_card');
		}

		if (isset($this->request->post['sagepay_direct_order_status_id'])) {
			$data['sagepay_direct_order_status_id'] = $this->request->post['sagepay_direct_order_status_id'];
		} else {
			$data['sagepay_direct_order_status_id'] = $this->config->get('sagepay_direct_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['sagepay_direct_geo_zone_id'])) {
			$data['sagepay_direct_geo_zone_id'] = $this->request->post['sagepay_direct_geo_zone_id'];
		} else {
			$data['sagepay_direct_geo_zone_id'] = $this->config->get('sagepay_direct_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['sagepay_direct_status'])) {
			$data['sagepay_direct_status'] = $this->request->post['sagepay_direct_status'];
		} else {
			$data['sagepay_direct_status'] = $this->config->get('sagepay_direct_status');
		}

		if (isset($this->request->post['sagepay_direct_debug'])) {
			$data['sagepay_direct_debug'] = $this->request->post['sagepay_direct_debug'];
		} else {
			$data['sagepay_direct_debug'] = $this->config->get('sagepay_direct_debug');
		}

		if (isset($this->request->post['sagepay_direct_sort_order'])) {
			$data['sagepay_direct_sort_order'] = $this->request->post['sagepay_direct_sort_order'];
		} else {
			$data['sagepay_direct_sort_order'] = $this->config->get('sagepay_direct_sort_order');
		}

		if (isset($this->request->post['sagepay_direct_cron_job_token'])) {
			$data['sagepay_direct_cron_job_token'] = $this->request->post['sagepay_direct_cron_job_token'];
		} elseif ($this->config->get('sagepay_direct_cron_job_token')) {
			$data['sagepay_direct_cron_job_token'] = $this->config->get('sagepay_direct_cron_job_token');
		} else {
			$data['sagepay_direct_cron_job_token'] = sha1(uniqid(mt_rand(), 1));
		}

		$data['sagepay_direct_cron_job_url'] = HTTPS_CATALOG . 'index.php?route=payment/sagepay_direct/cron&token=' . $data['sagepay_direct_cron_job_token'];

		if ($this->config->get('sagepay_direct_last_cron_job_run')) {
			$data['sagepay_direct_last_cron_job_run'] = $this->config->get('sagepay_direct_last_cron_job_run');
		} else {
			$data['sagepay_direct_last_cron_job_run'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/sagepay_direct.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/sagepay_direct');
		$this->model_payment_sagepay_direct->install();
	}

	public function uninstall() {
		$this->load->model('payment/sagepay_direct');
		$this->model_payment_sagepay_direct->uninstall();
	}

	public function orderAction() {

		if ($this->config->get('sagepay_direct_status')) {

			$this->load->model('payment/sagepay_direct');

			$sagepay_direct_order = $this->model_payment_sagepay_direct->getOrder($this->request->get['order_id']);

			if (!empty($sagepay_direct_order)) {
				$this->load->language('payment/sagepay_direct');

				$sagepay_direct_order['total_released'] = $this->model_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				$sagepay_direct_order['total_formatted'] = $this->currency->format($sagepay_direct_order['total'], $sagepay_direct_order['currency_code'], false, false);
				$sagepay_direct_order['total_released_formatted'] = $this->currency->format($sagepay_direct_order['total_released'], $sagepay_direct_order['currency_code'], false, false);

				$data['sagepay_direct_order'] = $sagepay_direct_order;

				$data['auto_settle'] = $sagepay_direct_order['settle_type'];

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_ref'] = $this->language->get('text_order_ref');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_total_released'] = $this->language->get('text_total_released');
				$data['text_release_status'] = $this->language->get('text_release_status');
				$data['text_void_status'] = $this->language->get('text_void_status');
				$data['text_rebate_status'] = $this->language->get('text_rebate_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_yes'] = $this->language->get('text_yes');
				$data['text_no'] = $this->language->get('text_no');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_date_added'] = $this->language->get('text_column_date_added');
				$data['button_release'] = $this->language->get('button_release');
				$data['button_rebate'] = $this->language->get('button_rebate');
				$data['button_void'] = $this->language->get('button_void');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_confirm_release'] = $this->language->get('text_confirm_release');
				$data['text_confirm_rebate'] = $this->language->get('text_confirm_rebate');

				$data['order_id'] = $this->request->get['order_id'];
				$data['token'] = $this->request->get['token'];

				return $this->load->view('payment/sagepay_direct_order.tpl', $data);
			}
		}
	}

	public function void() {
		$this->load->language('payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('payment/sagepay_direct');

			$sagepay_direct_order = $this->model_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$void_response = $this->model_payment_sagepay_direct->void($this->request->post['order_id']);

			$this->model_payment_sagepay_direct->logger('Void result:\r\n' . print_r($void_response, 1));

			if ($void_response['Status'] == 'OK') {
				$this->model_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'void', 0.00);
				$this->model_payment_sagepay_direct->updateVoidStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);

				$json['msg'] = $this->language->get('text_void_ok');

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($void_response['StatuesDetail']) && !empty($void_response['StatuesDetail']) ? (string)$void_response['StatuesDetail'] : 'Unable to void';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function release() {
		$this->load->language('payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('payment/sagepay_direct');

			$sagepay_direct_order = $this->model_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$release_response = $this->model_payment_sagepay_direct->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_sagepay_direct->logger('Release result:\r\n' . print_r($release_response, 1));

			if ($release_response['Status'] == 'OK') {
				$this->model_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				if ($total_released >= $sagepay_direct_order['total'] || $sagepay_direct_order['settle_type'] == 0) {
					$this->model_payment_sagepay_direct->updateReleaseStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);
					$release_status = 1;
					$json['msg'] = $this->language->get('text_release_ok_order');
				} else {
					$release_status = 0;
					$json['msg'] = $this->language->get('text_release_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'];
				$json['data']['release_status'] = $release_status;
				$json['data']['total'] = (float)$total_released;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($release_response['StatusDetail']) && !empty($release_response['StatusDetail']) ? (string)$release_response['StatusDetail'] : 'Unable to release';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rebate() {
		$this->load->language('payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('payment/sagepay_direct');

			$sagepay_direct_order = $this->model_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_payment_sagepay_direct->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_sagepay_direct->logger('Rebate result:\r\n' . print_r($rebate_response, 1));

			if ($rebate_response['Status'] == 'OK') {
				$this->model_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_payment_sagepay_direct->getTotalRebated($sagepay_direct_order['sagepay_direct_order_id']);
				$total_released = $this->model_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				if ($total_released <= 0 && $sagepay_direct_order['release_status'] == 1) {
					$this->model_payment_sagepay_direct->updateRebateStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('text_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('text_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'] * -1;
				$json['data']['total_released'] = (float)$total_released;
				$json['data']['total_rebated'] = (float)$total_rebated;
				$json['data']['rebate_status'] = $rebate_status;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($rebate_response['StatusDetail']) && !empty($rebate_response['StatusDetail']) ? (string)$rebate_response['StatusDetail'] : 'Unable to rebate';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/sagepay_direct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['sagepay_direct_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		return !$this->error;
	}
}