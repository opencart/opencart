<?php
class ControllerExtensionPaymentWorldpay extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/worldpay');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_worldpay', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/worldpay', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/worldpay', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_worldpay_service_key'])) {
			$data['payment_worldpay_service_key'] = $this->request->post['payment_worldpay_service_key'];
		} else {
			$data['payment_worldpay_service_key'] = $this->config->get('payment_worldpay_service_key');
		}

		if (isset($this->request->post['payment_worldpay_client_key'])) {
			$data['payment_worldpay_client_key'] = $this->request->post['payment_worldpay_client_key'];
		} else {
			$data['payment_worldpay_client_key'] = $this->config->get('payment_worldpay_client_key');
		}

		if (isset($this->request->post['payment_worldpay_total'])) {
			$data['payment_worldpay_total'] = $this->request->post['payment_worldpay_total'];
		} else {
			$data['payment_worldpay_total'] = $this->config->get('payment_worldpay_total');
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

		$data['worldpay_webhook_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/worldpay/webhook&token=' . $data['worldpay_secret_token'];

		$data['worldpay_cron_job_url'] = HTTPS_CATALOG . 'index.php?route=extension/payment/worldpay/cron&token=' . $data['worldpay_secret_token'];

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

		$this->response->setOutput($this->load->view('extension/payment/worldpay', $data));
	}

	public function install() {
		$this->load->model('extension/payment/worldpay');
		$this->model_extension_payment_worldpay->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/worldpay');
		$this->model_extension_payment_worldpay->uninstall();
	}

	public function order() {

		if ($this->config->get('worldpay_status')) {

			$this->load->model('extension/payment/worldpay');

			$worldpay_order = $this->model_extension_payment_worldpay->getOrder($this->request->get['order_id']);

			if (!empty($worldpay_order)) {
				$this->load->language('extension/payment/worldpay');

				$worldpay_order['total_released'] = $this->model_extension_payment_worldpay->getTotalReleased($worldpay_order['worldpay_order_id']);

				$worldpay_order['total_formatted'] = $this->currency->format($worldpay_order['total'], $worldpay_order['currency_code'], false);
				$worldpay_order['total_released_formatted'] = $this->currency->format($worldpay_order['total_released'], $worldpay_order['currency_code'], false);

				$data['worldpay_order'] = $worldpay_order;

				$data['order_id'] = $this->request->get['order_id'];
				
				$data['user_token'] = $this->request->get['user_token'];

				return $this->load->view('extension/payment/worldpay_order', $data);
			}
		}
	}

	public function refund() {
		$this->load->language('extension/payment/worldpay');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/worldpay');

			$worldpay_order = $this->model_extension_payment_worldpay->getOrder($this->request->post['order_id']);

			$refund_response = $this->model_extension_payment_worldpay->refund($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_worldpay->logger('Refund result: ' . print_r($refund_response, 1));

			if ($refund_response['status'] == 'success') {
				$this->model_extension_payment_worldpay->addTransaction($worldpay_order['worldpay_order_id'], 'refund', $this->request->post['amount'] * -1);

				$total_refunded = $this->model_extension_payment_worldpay->getTotalRefunded($worldpay_order['worldpay_order_id']);
				$total_released = $this->model_extension_payment_worldpay->getTotalReleased($worldpay_order['worldpay_order_id']);

				$this->model_extension_payment_worldpay->updateRefundStatus($worldpay_order['worldpay_order_id'], 1);

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
		if (!$this->user->hasPermission('modify', 'extension/payment/worldpay')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_worldpay_service_key']) {
			$this->error['error_service_key'] = $this->language->get('error_service_key');
		}

		if (!$this->request->post['payment_worldpay_client_key']) {
			$this->error['error_client_key'] = $this->language->get('error_client_key');
		}

		return !$this->error;
	}
}
