<?php
class ControllerPaymentBankTransfer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/bank_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bank_transfer', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_bank'] = $this->language->get('entry_bank');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_total'] = $this->language->get('help_total');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (isset($this->error['bank_' . $language['language_id']])) {
				$data['error_bank' . $language['language_id']] = $this->error['bank' . $language['language_id']];
			} else {
				$data['error_bank' . $language['language_id']] = '';
			}
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
			'href' => $this->url->link('payment/bank_transfer', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/bank_transfer', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		$this->load->model('localisation/language');

		foreach ($languages as $language) {
			if (isset($this->request->post['bank_transfer_bank' . $language['language_id']])) {
				$data['bank_transfer_bank' . $language['language_id']] = $this->request->post['bank_transfer_bank' . $language['language_id']];
			} else {
				$data['bank_transfer_bank' . $language['language_id']] = $this->config->get('bank_transfer_bank' . $language['language_id']);
			}
		}

		$data['languages'] = $languages;

		if (isset($this->request->post['bank_transfer_total'])) {
			$data['bank_transfer_total'] = $this->request->post['bank_transfer_total'];
		} else {
			$data['bank_transfer_total'] = $this->config->get('bank_transfer_total');
		}

		if (isset($this->request->post['bank_transfer_order_status_id'])) {
			$data['bank_transfer_order_status_id'] = $this->request->post['bank_transfer_order_status_id'];
		} else {
			$data['bank_transfer_order_status_id'] = $this->config->get('bank_transfer_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bank_transfer_geo_zone_id'])) {
			$data['bank_transfer_geo_zone_id'] = $this->request->post['bank_transfer_geo_zone_id'];
		} else {
			$data['bank_transfer_geo_zone_id'] = $this->config->get('bank_transfer_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['bank_transfer_status'])) {
			$data['bank_transfer_status'] = $this->request->post['bank_transfer_status'];
		} else {
			$data['bank_transfer_status'] = $this->config->get('bank_transfer_status');
		}

		if (isset($this->request->post['bank_transfer_sort_order'])) {
			$data['bank_transfer_sort_order'] = $this->request->post['bank_transfer_sort_order'];
		} else {
			$data['bank_transfer_sort_order'] = $this->config->get('bank_transfer_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/bank_transfer.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bank_transfer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');

		$languages = $this->model_localisation_language->getLanguages();

		foreach ($languages as $language) {
			if (empty($this->request->post['bank_transfer_bank' . $language['language_id']])) {
				$this->error['bank' .  $language['language_id']] = $this->language->get('error_bank');
			}
		}

		return !$this->error;
	}
}