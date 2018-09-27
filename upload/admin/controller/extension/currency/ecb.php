<?php
class ControllerExtensionCurrencyECB extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/currency/ecb');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('currency_ecb', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency'));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
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
			'href' => $this->url->link('extension/currency/ecb', 'user_token=' . $this->session->data['user_token'])
		);

		$data['action'] = $this->url->link('extension/currency/ecb', 'user_token=' . $this->session->data['user_token']);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency');

		if (isset($this->request->post['currency_ecb_status'])) {
			$data['currency_ecb_status'] = $this->request->post['currency_ecb_status'];
		} else {
			$data['currency_ecb_status'] = $this->config->get('currency_ecb_status');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/currency/ecb', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/currency/ecb')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function currency($default = '') {
		if ($this->config->get('currency_ecb_status')) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);

			$dom = new DOMDocument('1.0', 'UTF-8');
			$dom->loadXml($response);

			$cube = $dom->getElementsByTagName('Cube')->item(0);

			$currency_data = array();

			foreach ($cube->getElementsByTagName('Cube') as $currency) {
				if ($currency->getAttribute('currency') != $default) {
					$currency_data[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
				}
			}

			if (isset($currency_data[$default])) {
				$rate = $currency_data[$default];
			} else {
				$rate = 1.0000;
			}

			print_r($currency_data);


			echo $response;

			// 1.0000 'EUR' /

			$this->load->model('localisation/currency');

			$results = $this->model_localisation_currency->getCurrencies();

			foreach ($results as $result) {

				if (isset($currency_data[$result['code']])) {

					$value = 1.0000 * ($result['code'] / $currency_data[$result['code']]);

					echo $result['code'] . ' ' . $value . "\n";


					$this->model_localisation_currency->editValueByCode($result['code'], $value);
				}
			}

			exit();

			$this->model_localisation_currency->editValueByCode($default, '1.00000');

			$this->cache->delete('currency');
		}
	}
}