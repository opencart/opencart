<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Currency;
/**
 * Class Fixer
 *
 * @package Opencart\Catalog\Controller\Extension\Opencart\Currency
 */
class Fixer extends \Opencart\System\Engine\Controller {
	/**
	 * Currency
	 *
	 * @param string $default
	 *
	 * @return void
	 */
	public function currency(string $default = ''): void {
		if ($this->config->get('currency_fixer_status')) {
			$curl = curl_init();

			curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key=' . $this->config->get('currency_fixer_api'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);

			$response = curl_exec($curl);

			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

			curl_close($curl);

			if ($status == 200) {
				$response_info = json_decode($response, true);
			} else {
				$response_info = [];
			}

			if (isset($response_info['rates'])) {
				// Compile all the rates into an array
				$currencies = [];

				$currencies['EUR'] = 1.0000;

				foreach ($response_info['rates'] as $key => $value) {
					$currencies[$key] = $value;
				}

				$this->load->model('localisation/currency');

				$results = $this->model_localisation_currency->getCurrencies();

				// Currencies
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
