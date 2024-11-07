<?php
class ModelExtensionCurrencyFixer extends Model {

	public function editValueByCode($code, $value) {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape((string)$code) . "'");
		$this->cache->delete('currency');
	}

	public function refresh() {

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, 'http://data.fixer.io/api/latest?access_key=' . $this->config->get('currency_fixer_api'));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
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

			$default = $this->config->get('config_currency');

			$results = $this->model_localisation_currency->getCurrencies();

			foreach ($results as $result) {
				if (isset($currencies[$result['code']])) {
					$from = $currencies['EUR'];

					$to = $currencies[$result['code']];

					$this->editValueByCode($result['code'], 1 / ($currencies[$default] * ($from / $to)));
				}
			}

			$this->editValueByCode($default, 1);

			$this->cache->delete('currency');
		}
	}
}
