<?php
class ControllerExtensionPaymentSkrill extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/skrill');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('payment_skrill', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_canceled_status'] = $this->language->get('entry_canceled_status');
		$data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$data['entry_chargeback_status'] = $this->language->get('entry_chargeback_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_mb_id'] = $this->language->get('entry_mb_id');
		$data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_custnote'] = $this->language->get('entry_custnote');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
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
			'href' => $this->url->link('extension/payment/skrill', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/skrill', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);

		if (isset($this->request->post['payment_skrill_email'])) {
			$data['payment_skrill_email'] = $this->request->post['payment_skrill_email'];
		} else {
			$data['payment_skrill_email'] = $this->config->get('payment_skrill_email');
		}

		if (isset($this->request->post['payment_skrill_secret'])) {
			$data['payment_skrill_secret'] = $this->request->post['payment_skrill_secret'];
		} else {
			$data['payment_skrill_secret'] = $this->config->get('payment_skrill_secret');
		}

		if (isset($this->request->post['payment_skrill_total'])) {
			$data['payment_skrill_total'] = $this->request->post['payment_skrill_total'];
		} else {
			$data['payment_skrill_total'] = $this->config->get('payment_skrill_total');
		}

		if (isset($this->request->post['payment_skrill_order_status_id'])) {
			$data['payment_skrill_order_status_id'] = $this->request->post['payment_skrill_order_status_id'];
		} else {
			$data['payment_skrill_order_status_id'] = $this->config->get('payment_skrill_order_status_id');
		}

		if (isset($this->request->post['payment_skrill_pending_status_id'])) {
			$data['payment_skrill_pending_status_id'] = $this->request->post['payment_skrill_pending_status_id'];
		} else {
			$data['payment_skrill_pending_status_id'] = $this->config->get('payment_skrill_pending_status_id');
		}

		if (isset($this->request->post['payment_skrill_canceled_status_id'])) {
			$data['payment_skrill_canceled_status_id'] = $this->request->post['payment_skrill_canceled_status_id'];
		} else {
			$data['payment_skrill_canceled_status_id'] = $this->config->get('payment_skrill_canceled_status_id');
		}

		if (isset($this->request->post['payment_skrill_failed_status_id'])) {
			$data['payment_skrill_failed_status_id'] = $this->request->post['payment_skrill_failed_status_id'];
		} else {
			$data['payment_skrill_failed_status_id'] = $this->config->get('payment_skrill_failed_status_id');
		}

		if (isset($this->request->post['payment_skrill_chargeback_status_id'])) {
			$data['payment_skrill_chargeback_status_id'] = $this->request->post['payment_skrill_chargeback_status_id'];
		} else {
			$data['payment_skrill_chargeback_status_id'] = $this->config->get('payment_skrill_chargeback_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['payment_skrill_geo_zone_id'])) {
			$data['payment_skrill_geo_zone_id'] = $this->request->post['payment_skrill_geo_zone_id'];
		} else {
			$data['payment_skrill_geo_zone_id'] = $this->config->get('payment_skrill_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['payment_skrill_status'])) {
			$data['payment_skrill_status'] = $this->request->post['payment_skrill_status'];
		} else {
			$data['payment_skrill_status'] = $this->config->get('payment_skrill_status');
		}

		if (isset($this->request->post['payment_skrill_sort_order'])) {
			$data['payment_skrill_sort_order'] = $this->request->post['payment_skrill_sort_order'];
		} else {
			$data['payment_skrill_sort_order'] = $this->config->get('payment_skrill_sort_order');
		}

		if (isset($this->request->post['payment_skrill_rid'])) {
			$data['payment_skrill_rid'] = $this->request->post['payment_skrill_rid'];
		} else {
			$data['payment_skrill_rid'] = $this->config->get('payment_skrill_rid');
		}

		if (isset($this->request->post['payment_skrill_custnote'])) {
			$data['payment_skrill_custnote'] = $this->request->post['payment_skrill_custnote'];
		} else {
			$data['payment_skrill_custnote'] = $this->config->get('payment_skrill_custnote');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/skrill', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/skrill')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['payment_skrill_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		return !$this->error;
	}
}