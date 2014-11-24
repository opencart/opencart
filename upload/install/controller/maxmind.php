<?php
class ControllerMaxmind extends Controller {
	private $error = array();

	public function index() {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$db->query("REPLACE INTO `" . DB_PREFIX . "setting` SET `config_fraud_status_id` = '1', `config_fraud_score` = '" . (int)$this->request->post['config_fraud_score'] . "', `config_fraud_key` = '" . $db->escape($this->request->post['config_fraud_score']) . "', `config_fraud_detection` = '" . (int)$this->request->post['config_fraud_detection'] . "' WHERE `store_id` = '0' AND `code` = 'config'");

			$this->session->data['success'] = $this->language->get('text_maxmind_success');

			$this->response->redirect($this->url->link('step_4'));
		} else {
			$this->document->setTitle($this->language->get('heading_maxmind'));

			$data['heading_maxmind'] = $this->language->get('heading_maxmind');
			$data['heading_maxmind_small'] = $this->language->get('heading_maxmind_small');

			$data['text_maxmind_top'] = $this->language->get('text_maxmind_top');
			$data['text_maxmind_link'] = $this->language->get('text_maxmind_link');

			$data['entry_licence_key'] = $this->language->get('entry_licence_key');
			$data['entry_risk'] = $this->language->get('entry_risk');
			$data['entry_fraud_status'] = $this->language->get('entry_fraud_status');

			$data['help_maxmind_risk'] = $this->language->get('help_maxmind_risk');
			$data['help_maxmind_fraud'] = $this->language->get('help_maxmind_fraud');

			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_back'] = $this->language->get('button_back');

			$data['action'] = $this->url->link('maxmind');

			if (isset($this->request->post['config_fraud_detection'])) {
				$data['config_fraud_detection'] = $this->request->post['config_fraud_detection'];
			} else {
				$data['config_fraud_detection'] = '';
			}

			if (isset($this->request->post['config_fraud_key'])) {
				$data['config_fraud_key'] = $this->request->post['config_fraud_key'];
			} else {
				$data['config_fraud_key'] = '';
			}

			if (isset($this->request->post['config_fraud_score'])) {
				$data['config_fraud_score'] = $this->request->post['config_fraud_score'];
			} else {
				$data['config_fraud_score'] = '80';
			}

			$data['order_statuses'] = $db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '1'  ORDER BY name ASC")->rows;

			if (isset($this->request->post['config_fraud_status_id'])) {
				$data['config_fraud_status_id'] = $this->request->post['config_fraud_status_id'];
			} else {
				$data['config_fraud_status_id'] = '';
			}

			if (isset($this->error['fraud_key'])) {
				$data['error_fraud_key'] = $this->error['fraud_key'];
			} else {
				$data['error_fraud_key'] = '';
			}

			if (isset($this->error['fraud_score'])) {
				$data['error_fraud_score'] = $this->error['fraud_score'];
			} else {
				$data['error_fraud_score'] = '';
			}

			$data['back'] = $this->url->link('step_4');

			$data['footer'] = $this->load->controller('footer');
			$data['header'] = $this->load->controller('header');

			$this->response->setOutput($this->load->view('maxmind.tpl', $data));
		}
	}

	private function validate() {
		if (!$this->request->post['config_fraud_key']) {
			$this->error['fraud_key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['config_fraud_score'] || (int)$this->request->post['config_fraud_score'] > 100 || (int)$this->request->post['config_fraud_score'] < 0) {
			$this->error['fraud_score'] = $this->language->get('error_score');
		}

		return !$this->error;
	}
}