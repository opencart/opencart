<?php
class ControllerFraudMaxMind extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('fraud/maxmind');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('maxmind', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_signup'] = $this->language->get('text_signup');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_score'] = $this->language->get('entry_score');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['help_score'] = $this->language->get('help_score');
		$data['help_order_status'] = $this->language->get('help_order_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_fraud'),
			'href' => $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('fraud/maxmind', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('fraud/maxmind', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/fraud', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['maxmind_key'])) {
			$data['maxmind_key'] = $this->request->post['maxmind_key'];
		} else {
			$data['maxmind_key'] = $this->config->get('maxmind_key');
		}

		if (isset($this->request->post['maxmind_score'])) {
			$data['maxmind_score'] = $this->request->post['maxmind_score'];
		} else {
			$data['maxmind_score'] = $this->config->get('maxmind_score');
		}

		if (isset($this->request->post['maxmind_order_status_id'])) {
			$data['maxmind_order_status_id'] = $this->request->post['maxmind_order_status_id'];
		} else {
			$data['maxmind_order_status_id'] = $this->config->get('maxmind_order_status_id');
		}
		
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['maxmind_status'])) {
			$data['maxmind_status'] = $this->request->post['maxmind_status'];
		} else {
			$data['maxmind_status'] = $this->config->get('maxmind_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('fraud/maxmind.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'fraud/maxmind')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['maxmind_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}
		
		return !$this->error;
	}
}