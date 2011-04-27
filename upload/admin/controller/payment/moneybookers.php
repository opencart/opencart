<?php
class ControllerPaymentMoneyBookers extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/moneybookers');

		$this->document->title = $this->language->get('heading_title');

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('moneybookers', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_order_status_pending'] = $this->language->get('entry_order_status_pending');
		$this->data['entry_order_status_canceled'] = $this->language->get('entry_order_status_canceled');
		$this->data['entry_order_status_failed'] = $this->language->get('entry_order_status_failed');
		$this->data['entry_order_status_chargeback'] = $this->language->get('entry_order_status_chargeback');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_mb_id'] = $this->language->get('entry_mb_id');
		$this->data['entry_secret'] = $this->language->get('entry_secret');
		$this->data['entry_custnote'] = $this->language->get('entry_custnote');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['email'])) {
			$this->data['error_email'] = $this->error['email'];
		} else {
			$this->data['error_email'] = '';
		}

		if (isset($this->error['secret'])) {
			$this->data['error_secret'] = $this->error['secret'];
		} else {
			$this->data['error_secret'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/moneybookers&token=' . $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);

		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/moneybookers&token=' . $this->session->data['token'];

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

		if (isset($this->request->post['moneybookers_email'])) {
			$this->data['moneybookers_email'] = $this->request->post['moneybookers_email'];
		} else {
			$this->data['moneybookers_email'] = $this->config->get('moneybookers_email');
		}

		if (isset($this->request->post['moneybookers_order_status_id'])) {
			$this->data['moneybookers_order_status_id'] = $this->request->post['moneybookers_order_status_id'];
		} else {
			$this->data['moneybookers_order_status_id'] = $this->config->get('moneybookers_order_status_id');
		}

		if (isset($this->request->post['moneybookers_order_status_pending_id'])) {
			$this->data['moneybookers_order_status_pending_id'] = $this->request->post['moneybookers_order_status_pending_id'];
		} else {
			$this->data['moneybookers_order_status_pending_id'] = $this->config->get('moneybookers_order_status_pending_id');
		}

		if (isset($this->request->post['moneybookers_order_status_canceled_id'])) {
			$this->data['moneybookers_order_status_canceled_id'] = $this->request->post['moneybookers_order_status_canceled_id'];
		} else {
			$this->data['moneybookers_order_status_canceled_id'] = $this->config->get('moneybookers_order_status_canceled_id');
		}

		if (isset($this->request->post['moneybookers_order_status_failed_id'])) {
			$this->data['moneybookers_order_status_failed_id'] = $this->request->post['moneybookers_order_status_failed_id'];
		} else {
			$this->data['moneybookers_order_status_failed_id'] = $this->config->get('moneybookers_order_status_failed_id');
		}

		if (isset($this->request->post['moneybookers_order_status_chargeback_id'])) {
			$this->data['moneybookers_order_status_chargeback_id'] = $this->request->post['moneybookers_order_status_chargeback_id'];
		} else {
			$this->data['moneybookers_order_status_chargeback_id'] = $this->config->get('moneybookers_order_status_chargeback_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['moneybookers_geo_zone_id'])) {
			$this->data['moneybookers_geo_zone_id'] = $this->request->post['moneybookers_geo_zone_id'];
		} else {
			$this->data['moneybookers_geo_zone_id'] = $this->config->get('moneybookers_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['moneybookers_status'])) {
			$this->data['moneybookers_status'] = $this->request->post['moneybookers_status'];
		} else {
			$this->data['moneybookers_status'] = $this->config->get('moneybookers_status');
		}

		if (isset($this->request->post['moneybookers_sort_order'])) {
			$this->data['moneybookers_sort_order'] = $this->request->post['moneybookers_sort_order'];
		} else {
			$this->data['moneybookers_sort_order'] = $this->config->get('moneybookers_sort_order');
		}

		if (isset($this->request->post['moneybookers_secret'])) {
			$this->data['moneybookers_secret'] = $this->request->post['moneybookers_secret'];
		} else {
			$this->data['moneybookers_secret'] = $this->config->get('moneybookers_secret');
		}

		if (isset($this->request->post['moneybookers_rid'])) {
			$this->data['moneybookers_rid'] = $this->request->post['moneybookers_rid'];
		} else {
			$this->data['moneybookers_rid'] = $this->config->get('moneybookers_rid');
		}

		if (isset($this->request->post['moneybookers_custnote'])) {
			$this->data['moneybookers_custnote'] = $this->request->post['moneybookers_custnote'];
		} else {
			$this->data['moneybookers_custnote'] = $this->config->get('moneybookers_custnote');
		}

		$this->template = 'payment/moneybookers.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/moneybookers')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['moneybookers_email']) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if (!$this->request->post['moneybookers_secret']) {
			$this->error['secret'] = $this->language->get('error_secret');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>