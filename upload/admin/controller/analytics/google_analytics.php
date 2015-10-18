<?php
class ControllerAnalyticsGoogleAnalytics extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('analytics/google_analytics');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('google_analytics', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_signup'] = $this->language->get('text_signup');

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
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_analytics'),
			'href' => $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('analytics/google_analytics', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('analytics/google_analytics', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/analytics', 'token=' . $this->session->data['token'], 'SSL');

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

		$this->response->setOutput($this->load->view('analytics/google_analytics.tpl', $data));
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
}
