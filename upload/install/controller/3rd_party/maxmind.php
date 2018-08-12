<?php
class Controller3rdPartyMaxmind extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('3rd_party/maxmind');
		
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('3rd_party/maxmind');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_3rd_party_maxmind->editSetting('fraud_maxmind', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('install/step_4'));
		} 
	
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_maxmind'] = $this->language->get('text_maxmind');
		$data['text_signup'] = sprintf($this->language->get('text_signup'), '');

		$data['entry_key'] = $this->language->get('entry_key');
		$data['entry_score'] = $this->language->get('entry_score');
		$data['entry_order_status'] = $this->language->get('entry_order_status');

		$data['help_score'] = $this->language->get('help_score');
		$data['help_order_status'] = $this->language->get('help_order_status');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		$data['action'] = $this->url->link('3rd_party/maxmind');

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['score'])) {
			$data['error_score'] = $this->error['score'];
		} else {
			$data['error_score'] = '';
		}

		if (isset($this->request->post['fraud_maxmind_key'])) {
			$data['fraud_maxmind_key'] = $this->request->post['fraud_maxmind_key'];
		} else {
			$data['fraud_maxmind_key'] = '';
		}

		if (isset($this->request->post['fraud_maxmind_score'])) {
			$data['fraud_maxmind_score'] = $this->request->post['fraud_maxmind_score'];
		} else {
			$data['fraud_maxmind_score'] = '80';
		}

		if (isset($this->request->post['maxmind_order_status_id'])) {
			$data['maxmind_order_status_id'] = $this->request->post['maxmind_order_status_id'];
		} else {
			$data['maxmind_order_status_id'] = '';
		}
		
		$data['order_statuses'] = $this->model_3rd_party_maxmind->getOrderStatuses();

		$data['back'] = $this->url->link('install/step_4');

		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('3rd_party/maxmind', $data));
	}

	private function validate() {
		if (!$this->request->post['fraud_maxmind_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['fraud_maxmind_score'] || (int)$this->request->post['fraud_maxmind_score'] > 100 || (int)$this->request->post['fraud_maxmind_score'] < 0) {
			$this->error['score'] = $this->language->get('error_score');
		}

		return !$this->error;
	}
}
