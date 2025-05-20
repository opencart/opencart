<?php
namespace Opencart\Admin\Controller\Extension\Opencart\Currency;
/**
 * Class ECB
 *
 * @package Opencart\Admin\Controller\Extension\Opencart\Currency
 */
class ECB extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('extension/opencart/currency/ecb');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/opencart/currency/ecb', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/opencart/currency/ecb.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=currency');

		$data['currency_ecb_status'] = $this->config->get('currency_ecb_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/opencart/currency/ecb', $data));
	}

	/**
	 * Save
	 *
	 * @return void
	 */
	public function save(): void {
		$this->load->language('extension/opencart/currency/ecb');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/opencart/currency/ecb')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			// Setting
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('currency_ecb', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	/**
	 * Currency
	 *
	 * @param string $default
	 *
	 * @return void
	 */
	public function currency(string $default = ''): void {
		if ($this->config->get('currency_ecb_status')) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if ($status == 200) {
				$dom = new \DOMDocument('1.0', 'UTF-8');
				$dom->loadXml($response);

				$cube = $dom->getElementsByTagName('Cube')->item(0);

				// Compile all the rates into an array
				$currencies = [];

				$currencies['EUR'] = 1.0000;

				foreach ($cube->getElementsByTagName('Cube') as $currency) {
					$currencies[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
				}

				if (isset($currencies[$default])) {
					$value = $currencies[$default];
				} else {
					$value = $currencies['EUR'];
				}

				// Currencies
				if (count($currencies) > 1) {
					$this->load->model('localisation/currency');

					$results = $this->model_localisation_currency->getCurrencies();

					foreach ($results as $result) {
						if (isset($currencies[$result['code']])) {
							$from = $currencies['EUR'];
							$to = $currencies[$result['code']];

							$this->model_localisation_currency->editValueByCode($result['code'], 1 / ($value * ($from / $to)));
						}
					}

					$this->model_localisation_currency->editValueByCode($default, 1.00000);
				}
			}
		}

		$this->cache->delete('currency');
	}
}
