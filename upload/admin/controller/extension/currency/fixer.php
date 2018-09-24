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

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency'));
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/currency/fixer', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/currency/fixer', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency');

		if (isset($this->request->post['currency_fixer_api'])) {
			$data['currency_fixer_api'] = $this->request->post['currency_fixer_api'];
		} else {
			$data['currency_fixer_api'] = $this->config->get('currency_fixer_api');
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
		}

		if (!$this->request->post['currency_fixer_api']) {
			$this->error['api'] = $this->language->get('error_api');
		}

		return !$this->error;
	}

	public function currency($default = '') {
		if ($this->config->get('currency_fixer_status')) {
			$currencies = array();

			$this->load->model('localisation/currency');

			$results = $this->model_localisation_currency->getCurrencies();

			foreach ($results as $result) {
				if (($result['code'] != $default)) {
					$currencies[] = $result;
				}
			}

			if ($currencies) {
				$curl = curl_init();

				curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key=' . $this->config->get('currency_fixer_api'));
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
				curl_setopt($curl, CURLOPT_TIMEOUT, 30);

				$response = curl_exec($curl);

				$this->log->write($response);

				curl_close($curl);

				$response_info = json_decode($response, true);

				if (isset($response_info['rates'])) {
					foreach ($currencies as $currency) {
						if (isset($response_info['rates'][$currency['code']])) {
							$this->model_localisation_currency->editValueByCode($currency['code'], ($response_info['rates'][$default] / $response_info['rates'][$currency['code']]));
						}
					}
				}
			}

			$this->model_localisation_currency->editValueByCode($default, '1.00000');

			$this->cache->delete('currency');
		}
	}
}