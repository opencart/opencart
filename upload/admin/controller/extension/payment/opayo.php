<?php
class ControllerExtensionPaymentOpayo extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/opayo');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_opayo', $this->request->post);

			$this->session->data['success'] = $this->language->get('success_save');
			
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
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
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extensions'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/opayo', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/opayo', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = HTTPS_SERVER;
			$catalog = HTTPS_CATALOG;
		} else {
			$server = HTTP_SERVER;
			$catalog = HTTP_CATALOG;
		}
		
		// Setting 		
		$_config = new Config();
		$_config->load('opayo');
		
		$data['setting'] = $_config->get('opayo_setting');
		
		if (isset($this->request->post['payment_opayo_setting'])) {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->request->post['payment_opayo_setting']);
		} else {
			$data['setting'] = array_replace_recursive((array)$data['setting'], (array)$this->config->get('payment_opayo_setting'));
		}
		
		if (isset($this->request->post['payment_opayo_vendor'])) {
			$data['vendor'] = $this->request->post['payment_opayo_vendor'];
		} else {
			$data['vendor'] = $this->config->get('payment_opayo_vendor');
		}

		if (isset($this->request->post['payment_opayo_total'])) {
			$data['total'] = $this->request->post['payment_opayo_total'];
		} else {
			$data['total'] = $this->config->get('payment_opayo_total');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_opayo_geo_zone_id'])) {
			$data['geo_zone_id'] = $this->request->post['payment_opayo_geo_zone_id'];
		} else {
			$data['geo_zone_id'] = $this->config->get('payment_opayo_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_opayo_status'])) {
			$data['status'] = $this->request->post['payment_opayo_status'];
		} else {
			$data['status'] = $this->config->get('payment_opayo_status');
		}

		if (isset($this->request->post['payment_opayo_sort_order'])) {
			$data['sort_order'] = $this->request->post['payment_opayo_sort_order'];
		} else {
			$data['sort_order'] = $this->config->get('payment_opayo_sort_order');
		}
		
		if (!$data['setting']['cron']['token']) {
			$data['setting']['cron']['token'] = sha1(uniqid(mt_rand(), 1));
		}

		if (!$data['setting']['cron']['url']) {
			$data['setting']['cron']['url'] = $catalog . 'index.php?route=extension/payment/opayo/cron&token=' . $data['setting']['cron']['token'];
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/opayo/opayo', $data));
	}

	public function install() {
		$this->load->model('extension/payment/opayo');
		
		$this->model_extension_payment_opayo->install();
	}

	public function uninstall() {
		$this->load->model('extension/payment/opayo');
		
		$this->model_extension_payment_opayo->uninstall();
	}

	public function order() {
		if ($this->config->get('payment_opayo_status')) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->get['order_id']);

			if (!empty($opayo_order)) {
				$this->load->language('extension/payment/opayo');

				$opayo_order['total_released'] = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				$opayo_order['total_formatted'] = $this->currency->format($opayo_order['total'], $opayo_order['currency_code'], false, false);
				$opayo_order['total_released_formatted'] = $this->currency->format($opayo_order['total_released'], $opayo_order['currency_code'], false, false);

				$data['opayo_order'] = $opayo_order;

				$data['auto_settle'] = $opayo_order['settle_type'];

				$data['order_id'] = (int)$this->request->get['order_id'];
				
				$data['user_token'] = $this->session->data['user_token'];

				return $this->load->view('extension/payment/opayo/order', $data);
			}
		}
	}

	public function void() {
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$void_response = $this->model_extension_payment_opayo->void($this->request->post['order_id']);

			$this->model_extension_payment_opayo->log('Void result', $void_response);

			if (!empty($void_response) && $void_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'void', 0.00);
				$this->model_extension_payment_opayo->updateVoidStatus($opayo_order['opayo_order_id'], 1);

				$json['msg'] = $this->language->get('success_void_ok');

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
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
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id']) && !empty($this->request->post['amount']) && $this->request->post['amount'] > 0) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$release_response = $this->model_extension_payment_opayo->release($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_opayo->log('Release result', $release_response);

			if (!empty($release_response) && $release_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'payment', $this->request->post['amount']);

				$total_released = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if ($total_released >= $opayo_order['total'] || $opayo_order['settle_type'] == 0) {
					$this->model_extension_payment_opayo->updateReleaseStatus($opayo_order['opayo_order_id'], 1);
					$release_status = 1;
					$json['msg'] = $this->language->get('success_release_ok_order');
				} else {
					$release_status = 0;
					$json['msg'] = $this->language->get('success_release_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
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
		$this->load->language('extension/payment/opayo');
		
		$json = array();

		if (!empty($this->request->post['order_id'])) {
			$this->load->model('extension/payment/opayo');

			$opayo_order = $this->model_extension_payment_opayo->getOrder($this->request->post['order_id']);

			$rebate_response = $this->model_extension_payment_opayo->rebate($this->request->post['order_id'], $this->request->post['amount']);

			$this->model_extension_payment_opayo->log('Rebate result', $rebate_response);

			if (!empty($rebate_response) && $rebate_response['Status'] == 'OK') {
				$this->model_extension_payment_opayo->addOrderTransaction($opayo_order['opayo_order_id'], 'rebate', $this->request->post['amount'] * -1);

				$total_rebated = $this->model_extension_payment_opayo->getTotalRebated($opayo_order['opayo_order_id']);
				$total_released = $this->model_extension_payment_opayo->getTotalReleased($opayo_order['opayo_order_id']);

				if (($total_released <= 0) && ($opayo_order['release_status'] == 1)) {
					$this->model_extension_payment_opayo->updateRebateStatus($opayo_order['opayo_order_id'], 1);
					$rebate_status = 1;
					$json['msg'] = $this->language->get('success_rebate_ok_order');
				} else {
					$rebate_status = 0;
					$json['msg'] = $this->language->get('success_rebate_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date('Y-m-d H:i:s');
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
	
	public function recurringButtons() {
		$content = '';
		
		if (!empty($this->request->get['order_recurring_id'])) {
			$this->load->language('extension/payment/opayo');
		
			$this->load->model('sale/recurring');
			
			$data['order_recurring_id'] = $this->request->get['order_recurring_id'];

			$order_recurring_info = $this->model_sale_recurring->getRecurring($data['order_recurring_id']);
			
			if ($order_recurring_info) {
				$data['recurring_status'] = $order_recurring_info['status'];
				
				$data['info_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/opayo/getRecurringInfo', 'user_token=' . $this->session->data['user_token'] . '&order_recurring_id=' . $data['order_recurring_id'], true));
				$data['enable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/opayo/enableRecurring', 'user_token=' . $this->session->data['user_token'], true));
				$data['disable_url'] = str_replace('&amp;', '&', $this->url->link('extension/payment/opayo/disableRecurring', 'user_token=' . $this->session->data['user_token'], true));
				
				$content = $this->load->view('extension/payment/opayo/recurring', $data);
			}
		}
		
		return $content;
	}
		
	public function getRecurringInfo() {
		$this->response->setOutput($this->recurringButtons());
	}
	
	public function enableRecurring() {
		if (!empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/opayo');
			
			$this->load->model('extension/payment/opayo');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_opayo->editRecurringStatus($order_recurring_id, 1);
			
			$data['success'] = $this->language->get('success_enable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
	
	public function disableRecurring() {
		if (!empty($this->request->post['order_recurring_id'])) {
			$this->load->language('extension/payment/opayo');
			
			$this->load->model('extension/payment/opayo');
			
			$order_recurring_id = $this->request->post['order_recurring_id'];
			
			$this->model_extension_payment_opayo->editRecurringStatus($order_recurring_id, 2);
			
			$data['success'] = $this->language->get('success_disable_recurring');	
		}
						
		$data['error'] = $this->error;
				
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/opayo')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->request->post['payment_opayo_vendor'] = trim($this->request->post['payment_opayo_vendor']);

		if (!$this->request->post['payment_opayo_vendor']) {
			$this->error['vendor'] = $this->language->get('error_vendor');
			$this->error['warning'] = $this->language->get('error_warning');
		} 

		return !$this->error;
	}
}
