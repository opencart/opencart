<?php
class ControllerExtensionCurrencyFixer extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('extension/currency/fixer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('currency_fixer', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['api'])) {
			$data['error_api'] = $this->error['api'];
		} else {
			$data['error_api'] = '';
		}

		if (isset($this->error['ip'])) {
			$data['error_ip'] = $this->error['ip'];
		} else {
			$data['error_ip'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/currency/fixer', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/currency/fixer', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency', true);
		$data['refresh'] = $this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'], true);

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_edit'] = str_replace('%1',$this->url->link('localisation/currency', 'user_token=' . $this->session->data['user_token'], true), $data['text_edit']);
		$data['text_edit'] = str_replace('%2',$this->url->link('setting/store', 'user_token=' . $this->session->data['user_token'], true), $data['text_edit']);

		$data['currency_fixer_cron'] = 'curl -s &quot;' . HTTPS_CATALOG . 'index.php?route=extension/currency/fixer/refresh&quot;';

		if (isset($this->request->post['currency_fixer_api'])) {
			$data['currency_fixer_api'] = $this->request->post['currency_fixer_api'];
		} else {
			$data['currency_fixer_api'] = (string)$this->config->get('currency_fixer_api');
		}

		if (isset($this->request->post['currency_fixer_ip'])) {
			$data['currency_fixer_ip'] = $this->request->post['currency_fixer_ip'];
		} else {
			$data['currency_fixer_ip'] = (string)$this->config->get('currency_fixer_ip');
		}

		if (isset($this->request->post['currency_fixer_status'])) {
			$data['currency_fixer_status'] = $this->request->post['currency_fixer_status'];
		} else {
			$data['currency_fixer_status'] = $this->config->get('currency_fixer_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/currency/fixer', $data));
	}


	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/currency/fixer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			if (empty($this->request->post['currency_fixer_api'])) {
				$this->error['api'] = $this->language->get('error_api');
			}
			if (!empty($this->request->post['currency_fixer_ip'])) {
				if (!filter_var($this->request->post['currency_fixer_ip'],FILTER_VALIDATE_IP)) {
					$this->error['ip'] = $this->language->get('error_ip');
				}
			}
		}
		return !$this->error;
	}


	public function install() {
	}


	public function uninstall() {
	}


	public function currency() {
		$this->load->model('extension/currency/fixer');
		$this->model_extension_currency_fixer->refresh();
		return null;
	}
}
