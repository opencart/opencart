<?php
class ControllerExtensionPaymentPaywiser extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/paywiser');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_paywiser', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));

		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_test'] = $this->language->get('entry_test');
		$data['entry_total'] = $this->language->get('entry_total');	
		$data['entry_canceled_reversal_status'] = $this->language->get('entry_canceled_reversal_status');
		$data['entry_completed_status'] = $this->language->get('entry_completed_status');
		$data['entry_denied_status'] = $this->language->get('entry_denied_status');
		$data['entry_expired_status'] = $this->language->get('entry_expired_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_processed_status'] = $this->language->get('entry_processed_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_reversed_status'] = $this->language->get('entry_reversed_status');
		$data['entry_voided_status'] = $this->language->get('entry_voided_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['api_key'])) {
			$data['error_api_key'] = $this->error['api_key'];
		} else {
			$data['error_api_key'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'user_token=' . $this->session->data['user_token'], true),      		
			'separator' => false
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('marketplace/extension/payment', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/payment/paywiser', 'user_token=' . $this->session->data['user_token'], true),
			'separator' => ' :: '
		);

		$data['action'] = $this->url->link('extension/payment/paywiser', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension/payment', 'user_token=' . $this->session->data['user_token'], true);

		if (isset($this->request->post['payment_paywiser_api_key'])) {
			$data['payment_paywiser_api_key'] = $this->request->post['payment_paywiser_api_key'];
		} else {
			$data['payment_paywiser_api_key'] = $this->config->get('payment_paywiser_api_key');
		}

		if (isset($this->request->post['payment_paywiser_test'])) {
			$data['payment_paywiser_test'] = $this->request->post['payment_paywiser_test'];
		} else {
			$data['payment_paywiser_test'] = $this->config->get('payment_paywiser_test');
		}

		if (isset($this->request->post['payment_paywiser_total'])) {
			$data['payment_paywiser_total'] = $this->request->post['payment_paywiser_total'];
		} else {
			$data['payment_paywiser_total'] = $this->config->get('payment_paywiser_total'); 
		} 

		if (isset($this->request->post['payment_paywiser_completed_status_id'])) {
			$data['payment_paywiser_completed_status_id'] = $this->request->post['payment_paywiser_completed_status_id'];
		} else {
			$data['payment_paywiser_completed_status_id'] = $this->config->get('payment_paywiser_completed_status_id');
		}	

		if (isset($this->request->post['payment_paywiser_failed_status_id'])) {
			$data['payment_paywiser_failed_status_id'] = $this->request->post['payment_paywiser_failed_status_id'];
		} else {
			$data['payment_paywiser_failed_status_id'] = $this->config->get('payment_paywiser_failed_status_id');
		}	

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_paywiser_geo_zone_id'])) {
			$data['payment_paywiser_geo_zone_id'] = $this->request->post['payment_paywiser_geo_zone_id'];
		} else {
			$data['payment_paywiser_geo_zone_id'] = $this->config->get('payment_paywiser_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_paywiser_status'])) {
			$data['payment_paywiser_status'] = $this->request->post['payment_paywiser_status'];
		} else {
			$data['payment_paywiser_status'] = $this->config->get('payment_paywiser_status');
		}

		if (isset($this->request->post['payment_paywiser_sort_order'])) {
			$data['payment_paywiser_sort_order'] = $this->request->post['payment_paywiser_sort_order'];
		} else {
			$data['payment_paywiser_sort_order'] = $this->config->get('payment_paywiser_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/paywiser', $data));

	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/paywiser')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_paywiser_api_key']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		return !$this->error;
	}
}
?>
