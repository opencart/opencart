<?php
class ControllerExtensionFraudFraudLabsPro extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/fraud/fraudlabspro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('fraud_fraudlabspro', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/fraud/fraudlabspro', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/fraud/fraudlabspro', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=fraud', true);

		if (isset($this->request->post['fraud_fraudlabspro_key'])) {
			$data['fraud_fraudlabspro_key'] = $this->request->post['fraud_fraudlabspro_key'];
		} else {
			$data['fraud_fraudlabspro_key'] = $this->config->get('fraud_fraudlabspro_key');
		}

		if (isset($this->request->post['fraud_fraudlabspro_score'])) {
			$data['fraud_fraudlabspro_score'] = $this->request->post['fraud_fraudlabspro_score'];
		} else {
			$data['fraud_fraudlabspro_score'] = $this->config->get('fraud_fraudlabspro_score');
		}

		if (isset($this->request->post['fraud_fraudlabspro_order_status_id'])) {
			$data['fraud_fraudlabspro_order_status_id'] = $this->request->post['fraud_fraudlabspro_order_status_id'];
		} else {
			$data['fraud_fraudlabspro_order_status_id'] = $this->config->get('fraud_fraudlabspro_order_status_id');
		}

		if (isset($this->request->post['fraud_fraudlabspro_review_status_id'])) {
			$data['fraud_fraudlabspro_review_status_id'] = $this->request->post['fraud_fraudlabspro_review_status_id'];
		} else {
			$data['fraud_fraudlabspro_review_status_id'] = $this->config->get('fraud_fraudlabspro_review_status_id');
		}

		if (isset($this->request->post['fraud_fraudlabspro_approve_status_id'])) {
			$data['fraud_fraudlabspro_approve_status_id'] = $this->request->post['fraud_fraudlabspro_approve_status_id'];
		} else {
			$data['fraud_fraudlabspro_approve_status_id'] = $this->config->get('fraud_fraudlabspro_approve_status_id');
		}

		if (isset($this->request->post['fraud_fraudlabspro_reject_status_id'])) {
			$data['fraud_fraudlabspro_reject_status_id'] = $this->request->post['fraud_fraudlabspro_reject_status_id'];
		} else {
			$data['fraud_fraudlabspro_reject_status_id'] = $this->config->get('fraud_fraudlabspro_reject_status_id');
		}

		if (isset($this->request->post['fraud_fraudlabspro_simulate_ip'])) {
			$data['fraud_fraudlabspro_simulate_ip'] = $this->request->post['fraud_fraudlabspro_simulate_ip'];
		} else {
			$data['fraud_fraudlabspro_simulate_ip'] = $this->config->get('fraud_fraudlabspro_simulate_ip');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['fraud_fraudlabspro_status'])) {
			$data['fraud_fraudlabspro_status'] = $this->request->post['fraud_fraudlabspro_status'];
		} else {
			$data['fraud_fraudlabspro_status'] = $this->config->get('fraud_fraudlabspro_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/fraud/fraudlabspro', $data));
	}

	public function install() {
		$this->load->model('extension/fraud/fraudlabspro');

		$this->model_extension_fraud_fraudlabspro->install();
	}

	public function uninstall() {
		$this->load->model('extension/fraud/fraudlabspro');

		$this->model_extension_fraud_fraudlabspro->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/fraud/fraudlabspro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['fraud_fraudlabspro_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		return !$this->error;
	}

	public function order() {
		$this->load->language('extension/fraud/fraudlabspro');

		$this->load->model('extension/fraud/fraudlabspro');

		// Action of the Approve/Reject button click
		if (isset($_POST['flp_id'])){
			$flp_status = $_POST['new_status'];
			$data['flp_status'] = $flp_status;

			//Feedback FLP status to server
			$fraud_fraudlabspro_key = $this->config->get('fraud_fraudlabspro_key');

			for($i=0; $i<3; $i++){
				$result = @file_get_contents('https://api.fraudlabspro.com/v1/order/feedback?key=' . $fraud_fraudlabspro_key . '&format=json&id=' . $_POST['flp_id'] . '&action=' . $flp_status);

				if($result) break;
			}

			// Update fraud status into table
			$this->db->query("UPDATE `" . DB_PREFIX . "fraudlabspro` SET fraudlabspro_status = '" . $this->db->escape($flp_status) . "' WHERE order_id = " . $this->db->escape($this->request->get['order_id']));

			//Update history record
			if (strtolower($flp_status) == 'approve'){
				$data_temp = array(
					'order_status_id'=>$this->config->get('fraud_fraudlabspro_approve_status_id'),
					'notify'=>0,
					'comment'=>'Approved using FraudLabs Pro.'
				);

				$this->model_extension_fraud_fraudlabspro->addOrderHistory($this->request->get['order_id'], $data_temp);
			}
			else if (strtolower($flp_status) == "reject"){
				$data_temp = array(
					'order_status_id'=>$this->config->get('fraud_fraudlabspro_reject_status_id'),
					'notify'=>0,
					'comment'=>'Rejected using FraudLabs Pro.'
				);

				$this->model_extension_fraud_fraudlabspro->addOrderHistory($this->request->get['order_id'], $data_temp);
			}
		}

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		$fraud_info = $this->model_extension_fraud_fraudlabspro->getOrder($order_id);

		if ($fraud_info) {
			if ($fraud_info['ip_address']) {
				$data['flp_ip_address'] = $fraud_info['ip_address'];
			} else {
				$data['flp_ip_address'] = '';
			}

			if ($fraud_info['ip_netspeed']) {
				$data['flp_ip_net_speed'] = $fraud_info['ip_netspeed'];
			} else {
				$data['flp_ip_net_speed'] = '';
			}

			if ($fraud_info['ip_isp_name']) {
				$data['flp_ip_isp_name'] = $fraud_info['ip_isp_name'];
			} else {
				$data['flp_ip_isp_name'] = '';
			}

			if ($fraud_info['ip_usage_type']) {
				$data['flp_ip_usage_type'] = $fraud_info['ip_usage_type'];
			} else {
				$data['flp_ip_usage_type'] = '';
			}

			if ($fraud_info['ip_domain']) {
				$data['flp_ip_domain'] = $fraud_info['ip_domain'];
			} else {
				$data['flp_ip_domain'] = '';
			}

			if ($fraud_info['ip_timezone']) {
				$data['flp_ip_time_zone'] = $fraud_info['ip_timezone'];
			} else {
				$data['flp_ip_time_zone'] = '';
			}

			if ($fraud_info['ip_country']) {
				$data['flp_ip_location'] = $this->fix_case($fraud_info['ip_continent']) . ", " . $fraud_info['ip_country'] . ", " . $fraud_info['ip_region'] . ", " . $fraud_info['ip_city'] . " <a href=\"http://www.geolocation.com/" . $fraud_info['ip_address'] . "\" target=\"_blank\">[Map]</a>";
			} else {
				$data['flp_ip_location'] = '-';
			}

			if ($fraud_info['distance_in_mile'] != '-') {
				$data['flp_ip_distance'] = $fraud_info['distance_in_mile'] . " miles";
			} else {
				$data['flp_ip_distance'] = '';
			}

			if ($fraud_info['ip_latitude']) {
				$data['flp_ip_latitude'] = $fraud_info['ip_latitude'];
			} else {
				$data['flp_ip_latitude'] = '';
			}

			if ($fraud_info['ip_longitude']) {
				$data['flp_ip_longitude'] = $fraud_info['ip_longitude'];
			} else {
				$data['flp_ip_longitude'] = '';
			}

			if ($fraud_info['is_high_risk_country']) {
				$data['flp_risk_country'] = $fraud_info['is_high_risk_country'];
			} else {
				$data['flp_risk_country'] = '';
			}

			if ($fraud_info['is_free_email']) {
				$data['flp_free_email'] = $fraud_info['is_free_email'];
			} else {
				$data['flp_free_email'] = '';
			}

			if ($fraud_info['is_address_ship_forward']) {
				$data['flp_ship_forward'] = $fraud_info['is_address_ship_forward'];
			} else {
				$data['flp_ship_forward'] = '';
			}

			if ($fraud_info['is_proxy_ip_address']) {
				$data['flp_using_proxy'] = $fraud_info['is_proxy_ip_address'];
			} else {
				$data['flp_using_proxy'] = '';
			}

			if ($fraud_info['is_bin_found']) {
				$data['flp_bin_found'] = $fraud_info['is_bin_found'];
			} else {
				$data['flp_bin_found'] = '';
			}

			if ($fraud_info['is_email_blacklist']) {
				$data['flp_email_blacklist'] = $fraud_info['is_email_blacklist'];
			} else {
				$data['flp_email_blacklist'] = '';
			}

			if ($fraud_info['is_credit_card_blacklist']) {
				$data['flp_credit_card_blacklist'] = $fraud_info['is_credit_card_blacklist'];
			} else {
				$data['flp_credit_card_blacklist'] = '';
			}

			if ($fraud_info['fraudlabspro_score']) {
				$data['flp_score'] = $fraud_info['fraudlabspro_score'];
			} else {
				$data['flp_score'] = '';
			}

			if ($fraud_info['fraudlabspro_status']) {
				$data['flp_status'] = $fraud_info['fraudlabspro_status'];
			} else {
				$data['flp_status'] = '';
			}

			if ($fraud_info['fraudlabspro_message']) {
				$data['flp_message'] = $fraud_info['fraudlabspro_message'];
			} else {
				$data['flp_message'] = '';
			}

			if ($fraud_info['fraudlabspro_id']) {
				$data['flp_id'] = $fraud_info['fraudlabspro_id'];
				$data['flp_link'] = $fraud_info['fraudlabspro_id'];
			} else {
				$data['flp_id'] = '';
				$data['flp_link'] = '';
			}

			if ($fraud_info['fraudlabspro_credits']) {
				$data['flp_credits'] = $fraud_info['fraudlabspro_credits'];
			} else {
				$data['flp_credits'] = '';
			}

			return $this->load->view('extension/fraud/fraudlabspro_info', $data);
		}
	}

	private function fix_case($s) {
		$s = ucwords(strtolower($s));
		$s = preg_replace_callback("/( [ a-zA-Z]{1}')([a-zA-Z0-9]{1})/s", create_function('$matches', 'return $matches[1].strtoupper($matches[2]);'), $s);
		return $s;
	}
}