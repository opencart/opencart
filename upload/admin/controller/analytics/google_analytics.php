<?php
class ControllerAnalyticsGoogleAnalytics extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('analytics/google_analytics');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_analytics', $this->request->post, $this->request->post['store_id']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->ssl('extension/analytics', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_signup'] = $this->language->get('text_signup');
		
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_code'] = $this->language->get('entry_code');
		$data['entry_status'] = $this->language->get('entry_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->ssl('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_analytics'),
			'href' => $this->url->ssl('extension/analytics', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->ssl('analytics/google_analytics', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->ssl('analytics/google_analytics', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->ssl('extension/analytics', 'token=' . $this->session->data['token'], true);
		
		$data['token'] = $this->session->data['token'];
		
		if (isset($this->request->post['store_id'])) {
			$data['store_id'] = $this->request->post['store_id'];
		} else {
			$data['store_id'] = $this->config->get('store_id');
		}
		
		// Stores
		$this->load->model('setting/store');

		$data['stores'] = array();

		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$results = $this->model_setting_store->getStores();

		foreach ($results as $result) {
			$data['stores'][] = array(
				'store_id' => $result['store_id'],
				'name'     => $result['name']
			);
		}
					
		if (isset($this->request->post['google_analytics_code'])) {
			$data['google_analytics_code'] = $this->request->post['google_analytics_code'];
		} else {
			$data['google_analytics_code'] = $this->config->get('google_analytics_code');
		}

		if (isset($this->request->post['google_analytics_status'])) {
			$data['google_analytics_status'] = $this->request->post['google_analytics_status'];
		} else {
			$data['google_analytics_status'] = $this->config->get('google_analytics_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('analytics/google_analytics', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'analytics/google_analytics')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['google_analytics_code']) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}
	
	public function setting() {
		$this->load->model('setting/setting');
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($this->model_setting_setting->getSetting('google_analytics', $this->request->get['store_id'])));
	}
}
