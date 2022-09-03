<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Currency;
class Fixer extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/opencart/currency/fixer');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=captcha')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/currency/fixer', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/currency/fixer.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency');

		$data['currency_fixer_api'] = $this->config->get('currency_fixer_api');
		$data['currency_fixer_status'] = $this->config->get('currency_fixer_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/currency/fixer', $data));
	}

	public function save(): void {
		$this->load->language('extension/opencart/currency/fixer');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/currency/fixer')) {
			$json['error']['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['currency_fixer_api']) {
			$json['error']['api'] = $this->language->get('error_api');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('currency_fixer', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function currency(string $default = ''): void {
		if ($this->config->get('currency_fixer_status')) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key=' . $this->config->get('currency_fixer_api'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			curl_close($curl);

			$response_info = json_decode($response, true);

			if (is_array($response_info) && isset($response_info['rates'])) {
				// Compile all the rates into an array
				$currencies = [];

				$currencies['EUR'] = 1.0000;

				foreach ($response_info['rates'] as $key => $value) {
					$currencies[$key] = $value;
				}

				$this->load->model('localisation/currency');

				$results = $this->model_localisation_currency->getCurrencies();

				foreach ($results as $result) {
					if (isset($currencies[$result['code']])) {
						$from = $currencies['EUR'];

						$to = $currencies[$result['code']];

						$this->model_localisation_currency->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
					}
				}

				$this->model_localisation_currency->editValueByCode($default, 1);

				$this->cache->delete('currency');
			}
		}
	}
}