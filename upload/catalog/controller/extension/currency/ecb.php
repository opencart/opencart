<?php
class ControllerExtensionCurrencyEcb extends Controller {

	public function refresh() {
		// This function can be called as a CRON task

		if (!$this->config->get('currency_ecb_status')) {
			return false;
		}

		$config_currency_engine = $this->config->get('config_currency_engine');
		if (!$config_currency_engine) {
			return false;
		}
		if ($config_currency_engine != 'ecb') {
			return false;
		}

		if (!empty($this->config->get('currency_ecb_ip'))) {
			if ($_SERVER['REMOTE_ADDR'] != $this->config->get('currency_ecb_ip')) {
				return false;
			}
		}

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
				$this->load->model('extension/currency/ecb');

				$default = $this->config->get('config_currency');

				$results = $this->model_localisation_currency->getCurrencies();

				foreach ($results as $result) {
					if (isset($currencies[$result['code']])) {
						$from = $currencies['EUR'];

						$to = $currencies[$result['code']];

						$this->model_extension_currency_ecb->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
					}
				}
			}

			$this->model_extension_currency_ecb->editValueByCode($default, '1.00000');

			$this->cache->delete('currency');
		}

		return true;
	}
}

