<?php
namespace Opencart\Catalog\Controller\Extension\Opencart\Currency;
class ECB extends \Opencart\System\Engine\Controller {
	public function currency(string $default = ''): void {
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

			if ($response) {
				$dom = new \DOMDocument('1.0', 'UTF-8');
				$dom->loadXml($response);

				$cube = $dom->getElementsByTagName('Cube')->item(0);

				$currencies = [];

				$currencies['EUR'] = 1.0000;

				foreach ($cube->getElementsByTagName('Cube') as $currency) {
					if ($currency->getAttribute('currency')) {
						$currencies[$currency->getAttribute('currency')] = $currency->getAttribute('rate');
					}
				}

				if ($currencies) {
					$this->load->model('localisation/currency');

					$results = $this->model_localisation_currency->getCurrencies();

					foreach ($results as $result) {
						if (isset($currencies[$result['code']])) {
							$from = $currencies['EUR'];

							$to = $currencies[$result['code']];

							$this->model_localisation_currency->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
						}
					}
				}

				$this->model_localisation_currency->editValueByCode($default, '1.00000');

				$this->cache->delete('currency');
			}
		}
	}
}