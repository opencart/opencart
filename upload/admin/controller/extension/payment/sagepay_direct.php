<?php
class ControllerExtensionPaymentSagepayDirect extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/sagepay_direct');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_sagepay_direct', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/sagepay_direct', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/sagepay_direct', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_sagepay_direct_vendor'])) {
			$data['payment_sagepay_direct_vendor'] = $this->request->post['payment_sagepay_direct_vendor'];
		} else {
			$data['payment_sagepay_direct_vendor'] = $this->config->get('payment_sagepay_direct_vendor');
		}

		if (isset($this->request->post['payment_sagepay_direct_password'])) {
			$data['payment_sagepay_direct_password'] = $this->request->post['payment_sagepay_direct_password'];
		} else {
			$data['payment_sagepay_direct_password'] = $this->config->get('payment_sagepay_direct_password');
		}

		if (isset($this->request->post['payment_sagepay_direct_test'])) {
			$data['payment_sagepay_direct_test'] = $this->request->post['payment_sagepay_direct_test'];
		} else {
			$data['payment_sagepay_direct_test'] = $this->config->get('payment_sagepay_direct_test');
		}

		if (isset($this->request->post['payment_sagepay_direct_transaction'])) {
			$data['payment_sagepay_direct_transaction'] = $this->request->post['payment_sagepay_direct_transaction'];
		} else {
			$data['payment_sagepay_direct_transaction'] = $this->config->get('payment_sagepay_direct_transaction');
		}

		if (isset($this->request->post['payment_sagepay_direct_total'])) {
			$data['payment_sagepay_direct_total'] = $this->request->post['payment_sagepay_direct_total'];
		} else {
			$data['payment_sagepay_direct_total'] = $this->config->get('payment_sagepay_direct_total');
		}

		if (isset($this->request->post['payment_sagepay_direct_card'])) {
			$data['payment_sagepay_direct_card'] = $this->request->post['payment_sagepay_direct_card'];
		} else {
			$data['payment_sagepay_direct_card'] = $this->config->get('payment_sagepay_direct_card');
		}

		if (isset($this->request->post['payment_sagepay_direct_order_status_id'])) {
			$data['payment_sagepay_direct_order_status_id'] = $this->request->post['payment_sagepay_direct_order_status_id'];
		} else {
			$data['payment_sagepay_direct_order_status_id'] = $this->config->get('payment_sagepay_direct_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_sagepay_direct_geo_zone_id'])) {
			$data['payment_sagepay_direct_geo_zone_id'] = $this->request->post['payment_sagepay_direct_geo_zone_id'];
		} else {
			$data['payment_sagepay_direct_geo_zone_id'] = $this->config->get('payment_sagepay_direct_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_sagepay_direct_status'])) {
			$data['payment_sagepay_direct_status'] = $this->request->post['payment_sagepay_direct_status'];
		} else {
			$data['payment_sagepay_direct_status'] = $this->config->get('payment_sagepay_direct_status');
		}

		if (isset($this->request->post['payment_sagepay_direct_debug'])) {
			$data['payment_sagepay_direct_debug'] = $this->request->post['payment_sagepay_direct_debug'];
		} else {
			$data['payment_sagepay_direct_debug'] = $this->config->get('payment_sagepay_direct_debug');
		}

		if (isset($this->request->post['payment_sagepay_direct_sort_order'])) {
			$data['payment_sagepay_direct_sort_order'] = $this->request->post['payment_sagepay_direct_sort_order'];
		} else {
			$data['payment_sagepay_direct_sort_order'] = $this->config->get('payment_sagepay_direct_sort_order');
		}

		if (isset($this->request->post['payment_sagepay_direct_cron_job_token'])) {
			$data['payment_sagepay_direct_cron_job_token'] = $this->request->post['payment_sagepay_direct_cron_job_token'];
		} elseif ($this->config->get('payment_sagepay_direct_cron_job_token')) {
			$data['payment_sagepay_direct_cron_job_token'] = $this->config->get('payment_sagepay_direct_cron_job_token');
		} else {
			$data['payment_sagepay_direct_cron_job_token'] = sha1(uniqid(mt_rand(), 1));
		}

		$data['sagepay_direct_cron_job_url'] = HTTP_CATALOG . 'index.php?route=extension/payment/sagepay_direct/cron&token=' . $data['payment_sagepay_direct_cron_job_token'];

		if ($this->config->get('payment_sagepay_direct_last_cron_job_run')) {
			$data['payment_sagepay_direct_last_cron_job_run'] = $this->config->get('payment_sagepay_direct_last_cron_job_run');
		} else {
			$data['payment_sagepay_direct_last_cron_job_run'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/sagepay_direct', $data));
	}

	public function install() {
		$this->load->model('extension/payment/sagepay_direct');
		$this->model_extension_payment_sagepay_direct->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/sagepay_direct');
		$this->model_extension_payment_sagepay_direct->uninstall();
	}

	public function order() {
		if ($this->config->get('payment_sagepay_direct_status')) {
			$this->load->model('extension/payment/sagepay_direct');

			$sagepay_direct_order = $this->model_extension_payment_sagepay_direct->getOrder($this->request->get['order_id']);

			if (!empty($sagepay_direct_order)) {
				$this->load->language('extension/payment/sagepay_direct');

				$sagepay_direct_order['total_released'] = $this->model_extension_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				$sagepay_direct_order['total_formatted'] = $this->currency->format($sagepay_direct_order['total'], $sagepay_direct_order['currency_code'], false, false);
				$sagepay_direct_order['total_released_formatted'] = $this->currency->format($sagepay_direct_order['total_released'], $sagepay_direct_order['currency_code'], false, false);

				$data['sagepay_direct_order'] = $sagepay_direct_order;

				$data['auto_settle'] = $sagepay_direct_order['settle_type'];

				$data['order_id'] = (int)$this->request->get['order_id'];
				
				$data['user_token'] = $this->session->data['user_token'];

				return $this->load->view('extension/payment/sagepay_direct_order', $data);
			}
		}
	}

	public function void() {
		$this->load->language('extension/payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('extension/payment/sagepay_direct');

			$sagepay_direct_order = $this->model_extension_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_payment_sagepay_direct->void($this->request->post['order_id']);

			$this->model_extension_payment_sagepay_direct->logger('Void result', $void_response);

			if ($void_response['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'void', 0.00);
				$this->model_extension_payment_sagepay_direct->updateVoidStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);

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
		$this->load->language('extension/payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('extension/payment/sagepay_direct');

			$sagepay_direct_order = $this->model_extension_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$release_response = $this->model_extension_payment_sagepay_direct->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_sagepay_direct->logger('Release result', $release_response);

			if ($release_response['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_extension_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				if ($total_released >= $sagepay_direct_order['total'] || $sagepay_direct_order['settle_type'] == 0) {
					$this->model_extension_payment_sagepay_direct->updateReleaseStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);
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
		$this->load->language('extension/payment/sagepay_direct');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/sagepay_direct');

			$sagepay_direct_order = $this->model_extension_payment_sagepay_direct->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_payment_sagepay_direct->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_sagepay_direct->logger('Rebate result', $rebate_response);

			if ($rebate_response['Status'] == 'OK') {
				$this->model_extension_payment_sagepay_direct->addTransaction($sagepay_direct_order['sagepay_direct_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_extension_payment_sagepay_direct->getTotalRebated($sagepay_direct_order['sagepay_direct_order_id']);
				$total_released = $this->model_extension_payment_sagepay_direct->getTotalReleased($sagepay_direct_order['sagepay_direct_order_id']);

				if ($total_released <= 0 && $sagepay_direct_order['release_status'] == 1) {
					$this->model_extension_payment_sagepay_direct->updateRebateStatus($sagepay_direct_order['sagepay_direct_order_id'], 1);
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
		if (!$this->user->hasPermission('modify', 'extension/payment/sagepay_direct')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_sagepay_direct_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
		}

		return !$this->error;
	}
}
