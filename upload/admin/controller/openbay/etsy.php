<?php
class ControllerOpenbayEtsy extends Controller {
	public function install() {
		$this->load->language('openbay/etsy');
		$this->load->model('openbay/etsy');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_etsy->install();
	}

	public function uninstall() {
		$this->load->model('openbay/etsy');
		$this->load->model('setting/setting');
		$this->load->model('setting/extension');

		$this->model_openbay_etsy->uninstall();
		$this->model_setting_extension->uninstall('openbay', $this->request->get['extension']);
		$this->model_setting_setting->deleteSetting($this->request->get['extension']);
	}

	public function index() {
		$data = $this->load->language('openbay/etsy_overview');

		$this->document->setTitle($this->language->get('text_title'));
		$this->document->addScript('view/javascript/openbay/faq.js');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/ebay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_heading'),
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['validation']               = $this->openbay->ebay->validate();
		$data['links_settings']           = $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], 'SSL');

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/etsy_overview.tpl', $data));
	}

	public function settings() {
		$data = $this->load->language('openbay/etsy_settings');

		$this->load->model('setting/setting');
		$this->load->model('openbay/etsy');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('etsy', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success_settings');
			$this->response->redirect($this->url->link('openbay/etsy/index&token=' . $this->session->data['token']));
		}

		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('view/javascript/openbay/faq.js');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('extension/openbay', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_openbay'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_etsy'),
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
		);

		$data['action'] = $this->url->link('openbay/etsy/settings', 'token=' . $this->session->data['token'], 'SSL');
		$data['cancel'] = $this->url->link('openbay/etsy', 'token=' . $this->session->data['token'], 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->post['etsy_status'])) {
			$data['etsy_status'] = $this->request->post['etsy_status'];
		} else {
			$data['etsy_status'] = $this->config->get('etsy_status');
		}

		if (isset($this->request->post['etsy_token'])) {
			$data['etsy_token'] = $this->request->post['etsy_token'];
		} else {
			$data['etsy_token'] = $this->config->get('etsy_token');
		}

		if (isset($this->request->post['etsy_enc1'])) {
			$data['etsy_enc1'] = $this->request->post['etsy_enc1'];
		} else {
			$data['etsy_enc1'] = $this->config->get('etsy_enc1');
		}

		if (isset($this->request->post['etsy_enc2'])) {
			$data['etsy_enc2'] = $this->request->post['etsy_enc2'];
		} else {
			$data['etsy_enc2'] = $this->config->get('etsy_enc2');
		}

		$data['api_server']       = $this->openbay->etsy->getApiServer();

		$data['header'] = $this->load->controller('common/header');
		$data['menu'] = $this->load->controller('common/menu');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('openbay/etsy_settings.tpl', $data));
	}

	public function settingsUpdate() {
		$this->openbay->etsy->settingsUpdate();
	}

	public function verifyDetails() {
		echo json_encode(array('error' => false));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'openbay/ebay')) {
			$this->error['warning'] = $this->language->get('invalid_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	public function test() {
		$response = $this->openbay->etsy->call('product/default/categories', 'GET', array());

		echo '<pre>';
		print_r($response);
	}
}