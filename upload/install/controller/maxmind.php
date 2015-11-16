<?php
class ControllerMaxmind extends Controller {
	private $error = array();

	public function index() {
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE, DB_PORT);

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$db->query("REPLACE INTO `" . DB_PREFIX . "setting` SET `maxmind_key` = '" . $db->escape($this->request->post['maxmind_key']) . "', `maxmind_score` = '" . (int)$this->request->post['maxmind_score'] . "', `maxmind_order_status_id` = '" . (int)$this->request->post['maxmind_order_status_id'] . "'  WHERE `store_id` = '0' AND `code` = 'maxmind'");

			$db->query("INSERT INTO `oc_extension` (`type`, `code`) VALUES ('fraud', 'maxmind')");

			$this->session->data['success'] = $this->language->get('text_maxmind_success');

			$this->response->redirect($this->url->link('step_4'));
		} else {
			$this->document->setTitle($this->language->get('heading_maxmind'));

			$data['heading_maxmind'] = $this->language->get('heading_maxmind');
			$data['heading_maxmind_small'] = $this->language->get('heading_maxmind_small');

			$data['text_maxmind_top'] = $this->language->get('text_maxmind_top');
			$data['text_maxmind_link'] = $this->language->get('text_maxmind_link');

			$data['entry_key'] = $this->language->get('entry_key');
			$data['entry_score'] = $this->language->get('entry_score');
			$data['entry_order_status'] = $this->language->get('entry_order_status');

			$data['help_score'] = $this->language->get('help_score');
			$data['help_order_status'] = $this->language->get('help_order_status');

			$data['button_continue'] = $this->language->get('button_continue');
			$data['button_back'] = $this->language->get('button_back');

			$data['action'] = $this->url->link('maxmind');

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

			if (isset($this->request->post['maxmind_key'])) {
				$data['maxmind_key'] = $this->request->post['maxmind_key'];
			} else {
				$data['maxmind_key'] = '';
			}

			if (isset($this->request->post['maxmind_score'])) {
				$data['maxmind_score'] = $this->request->post['maxmind_score'];
			} else {
				$data['maxmind_score'] = '80';
			}

			$data['order_statuses'] = $db->query("SELECT * FROM " . DB_PREFIX . "order_status WHERE language_id = '1'  ORDER BY name ASC")->rows;

			if (isset($this->request->post['maxmind_order_status_id'])) {
				$data['maxmind_order_status_id'] = $this->request->post['maxmind_order_status_id'];
			} else {
				$data['maxmind_order_status_id'] = '';
			}

			$data['back'] = $this->url->link('step_4');

			$data['footer'] = $this->load->controller('footer');
			$data['header'] = $this->load->controller('header');

			$this->response->setOutput($this->load->view('maxmind.tpl', $data));
		}
	}

	private function validate() {
		if (!$this->request->post['maxmind_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['maxmind_score'] || (int)$this->request->post['maxmind_score'] > 100 || (int)$this->request->post['maxmind_score'] < 0) {
			$this->error['score'] = $this->language->get('error_score');
		}

		return !$this->error;
	}
}
