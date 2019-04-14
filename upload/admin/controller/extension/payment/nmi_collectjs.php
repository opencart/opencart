<?php
class ControllerExtensionPaymentNMICollectJS extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/nmi_collectjs');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_nmi_collectjs', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
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
			'href' => $this->url->link('extension/payment/nmi_collectjs', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/payment/nmi_collectjs', 'user_token=' . $this->session->data['user_token']);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment');

		if (isset($this->request->post['payment_nmi_collectjs_username'])) {
			$data['payment_nmi_collectjs_username'] = $this->request->post['payment_nmi_collectjs_username'];
		} else {
			$data['payment_nmi_collectjs_username'] = $this->config->get('payment_nmi_collectjs_username');
		}

		if (isset($this->request->post['payment_nmi_collectjs_password'])) {
			$data['payment_nmi_collectjs_password'] = $this->request->post['payment_nmi_collectjs_password'];
		} else {
			$data['payment_nmi_collectjs_password'] = $this->config->get('payment_nmi_collectjs_password');
		}

		if (isset($this->request->post['payment_nmi_collectjs_api_key'])) {
			$data['payment_nmi_collectjs_api_key'] = $this->request->post['payment_nmi_collectjs_api_key'];
		} else {
			$data['payment_nmi_collectjs_api_key'] = $this->config->get('payment_nmi_collectjs_api_key');
		}

		if (isset($this->request->post['payment_nmi_collectjs_tokenization_key'])) {
			$data['payment_nmi_collectjs_tokenization_key'] = $this->request->post['payment_nmi_collectjs_tokenization_key'];
		} else {
			$data['payment_nmi_collectjs_tokenization_key'] = $this->config->get('payment_nmi_collectjs_tokenization_key');
		}

		if (isset($this->request->post['payment_nmi_collectjs_dup_seconds'])) {
			$data['payment_nmi_collectjs_dup_seconds'] = $this->request->post['payment_nmi_collectjs_dup_seconds'];
		} else {
			$data['payment_nmi_collectjs_dup_seconds'] = $this->config->get('payment_nmi_collectjs_dup_seconds');
		}

		if (isset($this->request->post['payment_nmi_collectjs_method'])) {
			$data['payment_nmi_collectjs_method'] = $this->request->post['payment_nmi_collectjs_method'];
		} else {
			$data['payment_nmi_collectjs_method'] = $this->config->get('payment_nmi_collectjs_method');
		}

		if (isset($this->request->post['payment_nmi_collectjs_total'])) {
			$data['payment_nmi_collectjs_total'] = $this->request->post['payment_nmi_collectjs_total'];
		} else {
			$data['payment_nmi_collectjs_total'] = $this->config->get('payment_nmi_collectjs_total');
		}

		if (isset($this->request->post['payment_nmi_collectjs_debug'])) {
			$data['payment_nmi_collectjs_debug'] = $this->request->post['payment_nmi_collectjs_debug'];
		} else {
			$data['payment_nmi_collectjs_debug'] = $this->config->get('payment_nmi_collectjs_debug');
		}

		if (isset($this->request->post['payment_nmi_collectjs_order_status_id'])) {
			$data['payment_nmi_collectjs_order_status_id'] = $this->request->post['payment_nmi_collectjs_order_status_id'];
		} else {
			$data['payment_nmi_collectjs_order_status_id'] = $this->config->get('payment_nmi_collectjs_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_nmi_collectjs_geo_zone_id'])) {
			$data['payment_nmi_collectjs_geo_zone_id'] = $this->request->post['payment_nmi_collectjs_geo_zone_id'];
		} else {
			$data['payment_nmi_collectjs_geo_zone_id'] = $this->config->get('payment_nmi_collectjs_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_nmi_collectjs_status'])) {
			$data['payment_nmi_collectjs_status'] = $this->request->post['payment_nmi_collectjs_status'];
		} else {
			$data['payment_nmi_collectjs_status'] = $this->config->get('payment_nmi_collectjs_status');
		}

		if (isset($this->request->post['payment_nmi_collectjs_sort_order'])) {
			$data['payment_nmi_collectjs_sort_order'] = $this->request->post['payment_nmi_collectjs_sort_order'];
		} else {
			$data['payment_nmi_collectjs_sort_order'] = $this->config->get('payment_nmi_collectjs_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/nmi_collectjs', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/nmi_collectjs')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_nmi_collectjs_api_key']) {
			if (!$this->request->post['payment_nmi_collectjs_username']) {
				$this->error['username'] = $this->language->get('error_username');
			}

			if (!$this->request->post['payment_nmi_collectjs_password']) {
				$this->error['password'] = $this->language->get('error_password');
			}
		}

		return !$this->error;
	}
}
