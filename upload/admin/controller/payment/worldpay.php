<?php
class ControllerPaymentWorldpay extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('payment/worldpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('worldpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_test'] = $this->language->get('text_test');
		$data['text_live'] = $this->language->get('text_live');
		$data['text_payment'] = $this->language->get('text_payment');
		$data['text_authenticate'] = $this->language->get('text_authenticate');

		$data['entry_service_key'] = $this->language->get('entry_service_key');
		$data['entry_client_key'] = $this->language->get('entry_client_key');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_card'] = $this->language->get('entry_card');
		$data['entry_secret_token'] = $this->language->get('entry_secret_token');
		$data['entry_webhook_url'] = $this->language->get('entry_webhook_url');
		$data['entry_cron_job_url'] = $this->language->get('entry_cron_job_url');
		$data['entry_last_cron_job_run'] = $this->language->get('entry_last_cron_job_run');

		$data['entry_success_status'] = $this->language->get('entry_success_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_settled_status'] = $this->language->get('entry_settled_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_partially_refunded_status'] = $this->language->get('entry_partially_refunded_status');
		$data['entry_charged_back_status'] = $this->language->get('entry_charged_back_status');
		$data['entry_information_requested_status'] = $this->language->get('entry_information_requested_status');
		$data['entry_information_supplied_status'] = $this->language->get('entry_information_supplied_status');
		$data['entry_chargeback_reversed_status'] = $this->language->get('entry_chargeback_reversed_status');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_secret_token'] = $this->language->get('help_secret_token');
		$data['help_webhook_url'] = $this->language->get('help_webhook_url');
		$data['help_cron_job_url'] = $this->language->get('help_cron_job_url');

		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['error_service_key'])) {
			$data['error_service_key'] = $this->error['error_service_key'];
		} else {
			$data['error_service_key'] = '';
		}

		if (isset($this->error['error_client_key'])) {
			$data['error_client_key'] = $this->error['error_client_key'];
		} else {
			$data['error_client_key'] = '';
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
			'href' => $this->url->link('payment/worldpay', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/worldpay', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['worldpay_service_key'])) {
			$data['worldpay_service_key'] = $this->request->post['worldpay_service_key'];
		} else {
			$data['worldpay_service_key'] = $this->config->get('worldpay_service_key');
		}

		if (isset($this->request->post['worldpay_client_key'])) {
			$data['worldpay_client_key'] = $this->request->post['worldpay_client_key'];
		} else {
			$data['worldpay_client_key'] = $this->config->get('worldpay_client_key');
		}

		if (isset($this->request->post['worldpay_total'])) {
			$data['worldpay_total'] = $this->request->post['worldpay_total'];
		} else {
			$data['worldpay_total'] = $this->config->get('worldpay_total');
		}

		if (isset($this->request->post['worldpay_card'])) {
			$data['worldpay_card'] = $this->request->post['worldpay_card'];
		} else {
			$data['worldpay_card'] = $this->config->get('worldpay_card');
		}

		if (isset($this->request->post['worldpay_order_status_id'])) {
			$data['worldpay_order_status_id'] = $this->request->post['worldpay_order_status_id'];
		} else {
			$data['worldpay_order_status_id'] = $this->config->get('worldpay_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['worldpay_geo_zone_id'])) {
			$data['worldpay_geo_zone_id'] = $this->request->post['worldpay_geo_zone_id'];
		} else {
			$data['worldpay_geo_zone_id'] = $this->config->get('worldpay_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['worldpay_status'])) {
			$data['worldpay_status'] = $this->request->post['worldpay_status'];
		} else {
			$data['worldpay_status'] = $this->config->get('worldpay_status');
		}

		if (isset($this->request->post['worldpay_debug'])) {
			$data['worldpay_debug'] = $this->request->post['worldpay_debug'];
		} else {
			$data['worldpay_debug'] = $this->config->get('worldpay_debug');
		}

		if (isset($this->request->post['worldpay_sort_order'])) {
			$data['worldpay_sort_order'] = $this->request->post['worldpay_sort_order'];
		} else {
			$data['worldpay_sort_order'] = $this->config->get('worldpay_sort_order');
		}

		if (isset($this->request->post['worldpay_secret_token'])) {
			$data['worldpay_secret_token'] = $this->request->post['worldpay_secret_token'];
		} elseif ($this->config->get('worldpay_secret_token')) {
			$data['worldpay_secret_token'] = $this->config->get('worldpay_secret_token');
		} else {
			$data['worldpay_secret_token'] = sha1(uniqid(mt_rand(), 1));
		}

		$data['worldpay_webhook_url'] = HTTPS_CATALOG . 'index.php?route=payment/worldpay/webhook&token=' . $data['worldpay_secret_token'];

		$data['worldpay_cron_job_url'] = HTTPS_CATALOG . 'index.php?route=payment/worldpay/cron&token=' . $data['worldpay_secret_token'];

		if ($this->config->get('worldpay_last_cron_job_run')) {
			$data['worldpay_last_cron_job_run'] = $this->config->get('worldpay_last_cron_job_run');
		} else {
			$data['worldpay_last_cron_job_run'] = '';
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['worldpay_entry_success_status_id'])) {
			$data['worldpay_entry_success_status_id'] = $this->request->post['worldpay_entry_success_status_id'];
		} else {
			$data['worldpay_entry_success_status_id'] = $this->config->get('worldpay_entry_success_status_id');
		}

		if (isset($this->request->post['worldpay_entry_failed_status_id'])) {
			$data['worldpay_entry_failed_status_id'] = $this->request->post['worldpay_entry_failed_status_id'];
		} else {
			$data['worldpay_entry_failed_status_id'] = $this->config->get('worldpay_entry_failed_status_id');
		}

		if (isset($this->request->post['worldpay_entry_settled_status_id'])) {
			$data['worldpay_entry_settled_status_id'] = $this->request->post['worldpay_entry_settled_status_id'];
		} else {
			$data['worldpay_entry_settled_status_id'] = $this->config->get('worldpay_entry_settled_status_id');
		}

		if (isset($this->request->post['worldpay_refunded_status_id'])) {
			$data['worldpay_refunded_status_id'] = $this->request->post['worldpay_refunded_status_id'];
		} else {
			$data['worldpay_refunded_status_id'] = $this->config->get('worldpay_refunded_status_id');
		}

		if (isset($this->request->post['worldpay_entry_partially_refunded_status_id'])) {
			$data['worldpay_entry_partially_refunded_status_id'] = $this->request->post['worldpay_entry_partially_refunded_status_id'];
		} else {
			$data['worldpay_entry_partially_refunded_status_id'] = $this->config->get('worldpay_entry_partially_refunded_status_id');
		}

		if (isset($this->request->post['worldpay_entry_charged_back_status_id'])) {
			$data['worldpay_entry_charged_back_status_id'] = $this->request->post['worldpay_entry_charged_back_status_id'];
		} else {
			$data['worldpay_entry_charged_back_status_id'] = $this->config->get('worldpay_entry_charged_back_status_id');
		}

		if (isset($this->request->post['worldpay_entry_information_requested_status_id'])) {
			$data['worldpay_entry_information_requested_status_id'] = $this->request->post['worldpay_entry_information_requested_status_id'];
		} else {
			$data['worldpay_entry_information_requested_status_id'] = $this->config->get('worldpay_entry_information_requested_status_id');
		}

		if (isset($this->request->post['worldpay_entry_information_supplied_status_id'])) {
			$data['worldpay_entry_information_supplied_status_id'] = $this->request->post['worldpay_entry_information_supplied_status_id'];
		} else {
			$data['worldpay_entry_information_supplied_status_id'] = $this->config->get('worldpay_entry_information_supplied_status_id');
		}

		if (isset($this->request->post['worldpay_entry_chargeback_reversed_status_id'])) {
			$data['worldpay_entry_chargeback_reversed_status_id'] = $this->request->post['worldpay_entry_chargeback_reversed_status_id'];
		} else {
			$data['worldpay_entry_chargeback_reversed_status_id'] = $this->config->get('worldpay_entry_chargeback_reversed_status_id');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/worldpay.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/worldpay');
		$this->model_payment_worldpay->install();
	}

	public function uninstall() {
		$this->load->model('payment/worldpay');
		$this->model_payment_worldpay->uninstall();
	}

	public function orderAction() {

		if ($this->config->get('worldpay_status')) {

			$this->load->model('payment/worldpay');

			$worldpay_order = $this->model_payment_worldpay->getOrder($this->request->get['order_id']);

			if (!empty($worldpay_order)) {
				$this->load->language('payment/worldpay');

				$worldpay_order['total_released'] = $this->model_payment_worldpay->getTotalReleased($worldpay_order['worldpay_order_id']);

				$worldpay_order['total_formatted'] = $this->currency->format($worldpay_order['total'], $worldpay_order['currency_code'], false);
				$worldpay_order['total_released_formatted'] = $this->currency->format($worldpay_order['total_released'], $worldpay_order['currency_code'], false);

				$data['worldpay_order'] = $worldpay_order;

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_ref'] = $this->language->get('text_order_ref');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_total_released'] = $this->language->get('text_total_released');
				$data['text_release_status'] = $this->language->get('text_release_status');
				$data['text_void_status'] = $this->language->get('text_void_status');
				$data['text_refund_status'] = $this->language->get('text_refund_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_yes'] = $this->language->get('text_yes');
				$data['text_no'] = $this->language->get('text_no');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_date_added'] = $this->language->get('text_column_date_added');
				$data['btn_release'] = $this->language->get('btn_release');
				$data['btn_refund'] = $this->language->get('btn_refund');
				$data['btn_void'] = $this->language->get('btn_void');
				$data['text_confirm_void'] = $this->language->get('text_confirm_void');
				$data['text_confirm_release'] = $this->language->get('text_confirm_release');
				$data['text_confirm_refund'] = $this->language->get('text_confirm_refund');

				$data['order_id'] = $this->request->get['order_id'];
				$data['token'] = $this->request->get['token'];

				return $this->load->view('payment/worldpay_order.tpl', $data);
			}
		}
	}

	public function refund() {
		$this->load->language('payment/worldpay');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('payment/worldpay');

			$worldpay_order = $this->model_payment_worldpay->getOrder($this->request->post['order_id']);

			$refund_response = $this->model_payment_worldpay->refund($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_worldpay->logger('Refund result: ' . print_r($refund_response, 1));

			if ($refund_response['status'] == 'success') {
				$this->model_payment_worldpay->addTransaction($worldpay_order['worldpay_order_id'], 'refund', $this->request->post['amount'] * -1);

				$total_refunded = $this->model_payment_worldpay->getTotalRefunded($worldpay_order['worldpay_order_id']);
				$total_released = $this->model_payment_worldpay->getTotalReleased($worldpay_order['worldpay_order_id']);

				$this->model_payment_worldpay->updateRefundStatus($worldpay_order['worldpay_order_id'], 1);

				$json['msg'] = $this->language->get('text_refund_ok_order');
				$json['data'] = array();
				$json['data']['created'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->currency->format(($this->request->post['amount'] * -1), $worldpay_order['currency_code'], false);
				$json['data']['total_released'] = $this->currency->format($total_released, $worldpay_order['currency_code'], false);
				$json['data']['total_refund'] = $this->currency->format($total_refunded, $worldpay_order['currency_code'], false);
				$json['data']['refund_status'] = 1;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($refund_response['message']) && !empty($refund_response['message']) ? (string)$refund_response['message'] : 'Unable to refund';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/realex')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['worldpay_service_key']) {
			$this->error['error_service_key'] = $this->language->get('error_service_key');
		}

		if (!$this->request->post['worldpay_client_key']) {
			$this->error['error_client_key'] = $this->language->get('error_client_key');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
