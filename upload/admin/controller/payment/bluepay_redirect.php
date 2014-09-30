<?php
class ControllerPaymentBluepayredirect extends Controller {
	private $error = array();

	public function index() {

		$this->load->language('payment/bluepay_redirect');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bluepay_redirect', $this->request->post);

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
		$data['text_sale'] = $this->language->get('text_sale');
		$data['text_authenticate'] = $this->language->get('text_authenticate');

		$data['entry_vendor'] = $this->language->get('entry_vendor');
		$data['entry_secret_key'] = $this->language->get('entry_secret_key');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_transaction'] = $this->language->get('entry_transaction');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');
		$data['entry_card'] = $this->language->get('entry_card');

		$data['help_total'] = $this->language->get('help_total');
		$data['help_debug'] = $this->language->get('help_debug');
		$data['help_transaction'] = $this->language->get('help_transaction');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['account_id'])) {
			$data['error_account_id'] = $this->error['account_id'];
		} else {
			$data['error_account_id'] = '';
		}

		if (isset($this->error['secret_key'])) {
			$data['error_secret_key'] = $this->error['secret_key'];
		} else {
			$data['error_secret_key'] = '';
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
			'href' => $this->url->link('payment/bluepay_redirect', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/bluepay_redirect', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['bluepay_redirect_account_id'])) {
			$data['bluepay_redirect_account_id'] = $this->request->post['bluepay_redirect_account_id'];
		} else {
			$data['bluepay_redirect_account_id'] = $this->config->get('bluepay_redirect_account_id');
		}

		if (isset($this->request->post['bluepay_redirect_secret_key'])) {
			$data['bluepay_redirect_secret_key'] = $this->request->post['bluepay_redirect_secret_key'];
		} else {
			$data['bluepay_redirect_secret_key'] = $this->config->get('bluepay_redirect_secret_key');
		}

		if (isset($this->request->post['bluepay_redirect_test'])) {
			$data['bluepay_redirect_test'] = $this->request->post['bluepay_redirect_test'];
		} else {
			$data['bluepay_redirect_test'] = $this->config->get('bluepay_redirect_test');
		}

		if (isset($this->request->post['bluepay_redirect_transaction'])) {
			$data['bluepay_redirect_transaction'] = $this->request->post['bluepay_redirect_transaction'];
		} else {
			$data['bluepay_redirect_transaction'] = $this->config->get('bluepay_redirect_transaction');
		}

		if (isset($this->request->post['bluepay_redirect_total'])) {
			$data['bluepay_redirect_total'] = $this->request->post['bluepay_redirect_total'];
		} else {
			$data['bluepay_redirect_total'] = $this->config->get('bluepay_redirect_total');
		}

		if (isset($this->request->post['bluepay_redirect_card'])) {
			$data['bluepay_redirect_card'] = $this->request->post['bluepay_redirect_card'];
		} else {
			$data['bluepay_redirect_card'] = $this->config->get('bluepay_redirect_card');
		}

		if (isset($this->request->post['bluepay_redirect_order_status_id'])) {
			$data['bluepay_redirect_order_status_id'] = $this->request->post['bluepay_redirect_order_status_id'];
		} else {
			$data['bluepay_redirect_order_status_id'] = $this->config->get('bluepay_redirect_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bluepay_redirect_geo_zone_id'])) {
			$data['bluepay_redirect_geo_zone_id'] = $this->request->post['bluepay_redirect_geo_zone_id'];
		} else {
			$data['bluepay_redirect_geo_zone_id'] = $this->config->get('bluepay_redirect_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['bluepay_redirect_status'])) {
			$data['bluepay_redirect_status'] = $this->request->post['bluepay_redirect_status'];
		} else {
			$data['bluepay_redirect_status'] = $this->config->get('bluepay_redirect_status');
		}

		if (isset($this->request->post['bluepay_redirect_debug'])) {
			$data['bluepay_redirect_debug'] = $this->request->post['bluepay_redirect_debug'];
		} else {
			$data['bluepay_redirect_debug'] = $this->config->get('bluepay_redirect_debug');
		}

		if (isset($this->request->post['bluepay_redirect_sort_order'])) {
			$data['bluepay_redirect_sort_order'] = $this->request->post['bluepay_redirect_sort_order'];
		} else {
			$data['bluepay_redirect_sort_order'] = $this->config->get('bluepay_redirect_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/bluepay_redirect.tpl', $data));
	}

	public function install() {
		$this->load->model('payment/bluepay_redirect');

		$this->model_payment_bluepay_redirect->install();
	}

	public function uninstall() {
		$this->load->model('payment/bluepay_redirect');

		$this->model_payment_bluepay_redirect->uninstall();
	}

	public function orderAction() {
		if ($this->config->get('bluepay_redirect_status')) {
			$this->load->model('payment/bluepay_redirect');

			$bluepay_redirect_order = $this->model_payment_bluepay_redirect->getOrder($this->request->get['order_id']);

			if (!empty($bluepay_redirect_order)) {
				$this->load->language('payment/bluepay_redirect');

				$bluepay_redirect_order['total_released'] = $this->model_payment_bluepay_redirect->getTotalReleased($bluepay_redirect_order['bluepay_redirect_order_id']);

				$bluepay_redirect_order['total_formatted'] = $this->currency->format($bluepay_redirect_order['total'], $bluepay_redirect_order['currency_code'], false, false);
				$bluepay_redirect_order['total_released_formatted'] = $this->currency->format($bluepay_redirect_order['total_released'], $bluepay_redirect_order['currency_code'], false, false);

				$data['bluepay_redirect_order'] = $bluepay_redirect_order;

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

				return $this->load->view('payment/bluepay_redirect_order.tpl', $data);
			}
		}
	}

	public function void() {
		$this->load->language('payment/bluepay_redirect');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '') {
			$this->load->model('payment/bluepay_redirect');

			$bluepay_redirect_order = $this->model_payment_bluepay_redirect->getOrder($this->request->post['order_id']);

			$void_response = $this->model_payment_bluepay_redirect->void($this->request->post['order_id']);

			$this->model_payment_bluepay_redirect->logger('Void result:\r\n' . print_r($void_response, 1));

			if ($void_response['Result'] == 'APPROVED') {
				$this->model_payment_bluepay_redirect->addTransaction($bluepay_redirect_order['bluepay_redirect_order_id'], 'void', 0.00);
				$this->model_payment_bluepay_redirect->updateVoidStatus($bluepay_redirect_order['bluepay_redirect_order_id'], 1);

				$json['msg'] = $this->language->get('text_void_ok');
				$json['data'] = array();
				$json['data']['column_date_added'] = date("Y-m-d H:i:s");
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($void_response['MESSAGE']) && !empty($void_response['MESSAGE']) ? (string)$void_response['MESSAGE'] : 'Unable to void';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function release() {
		$this->load->language('payment/bluepay_redirect');
		$json = array();

		if (isset($this->request->post['order_id']) && $this->request->post['order_id'] != '' && isset($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('payment/bluepay_redirect');

			$bluepay_redirect_order = $this->model_payment_bluepay_redirect->getOrder($this->request->post['order_id']);

			$release_response = $this->model_payment_bluepay_redirect->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_bluepay_redirect->logger('Release result:\r\n' . print_r($release_response, 1));

			if ($release_response['Result'] == 'APPROVED') {
				$this->model_payment_bluepay_redirect->addTransaction($bluepay_redirect_order['bluepay_redirect_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_payment_bluepay_redirect->getTotalReleased($bluepay_redirect_order['bluepay_redirect_order_id']);

				if ($total_released >= $bluepay_redirect_order['total']) {
					$this->model_payment_bluepay_redirect->updateReleaseStatus($bluepay_redirect_order['bluepay_redirect_order_id'], 1);
					$release_status = 1;
					$json['msg'] = $this->language->get('text_release_ok_order');
				} else {
					$release_status = 0;
					$json['msg'] = $this->language->get('text_release_ok');
				}

				$json['data'] = array();
				$json['data']['column_date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'];
				$json['data']['release_status'] = $release_status;
				$json['data']['total'] = (float)$total_released;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($release_response['MESSAGE']) && !empty($release_response['MESSAGE']) ? (string)$release_response['MESSAGE'] : 'Unable to release';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = $this->language->get('error_data_missing');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function rebate() {
		$this->load->language('payment/bluepay_redirect');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('payment/bluepay_redirect');

			$bluepay_redirect_order = $this->model_payment_bluepay_redirect->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_payment_bluepay_redirect->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_payment_bluepay_redirect->logger('Rebate result:\r\n' . print_r($rebate_response, 1));

			if ($rebate_response['Result'] == 'APPROVED') {
				$this->model_payment_bluepay_redirect->addTransaction($bluepay_redirect_order['bluepay_redirect_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_payment_bluepay_redirect->getTotalRebated($bluepay_redirect_order['bluepay_redirect_order_id']);
				$total_released = $this->model_payment_bluepay_redirect->getTotalReleased($bluepay_redirect_order['bluepay_redirect_order_id']);

				if ($total_released <= 0 && $bluepay_redirect_order['release_status'] == 1) {
					$this->model_payment_bluepay_redirect->updateRebateStatus($bluepay_redirect_order['bluepay_redirect_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('text_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('text_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['column_date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->request->post['amount'] * -1;
				$json['data']['total_released'] = (float)$total_released;
				$json['data']['total_rebated'] = (float)$total_rebated;
				$json['data']['rebate_status'] = $rebate_status;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = isset($rebate_response['MESSAGE']) && !empty($rebate_response['MESSAGE']) ? (string)$rebate_response['MESSAGE'] : 'Unable to rebate';
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bluepay_redirect')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['bluepay_redirect_account_id']) {
			$this->error['account_id'] = $this->language->get('error_account_id');
		}

		if (!$this->request->post['bluepay_redirect_secret_key']) {
			$this->error['secret_key'] = $this->language->get('error_secret_key');
		}

		return !$this->error;
	}

	public function callback() {
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->request->get));
	}
}