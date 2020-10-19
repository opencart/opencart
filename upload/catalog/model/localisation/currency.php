<?php
namespace Opencart\Application\Model\Localisation;
class Currency extends \Opencart\System\Engine\Model {
	public function getCurrencyByCode($currency) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	public function getCurrencies() {
		$currency_data = $this->cache->get('currency.' . (int)$this->config->get('config_language_id'));

		if (!$currency_data) {
			$currency_data = [];

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` c INNER JOIN `" . DB_PREFIX . "currency_description` cd ON (cd.`currency_id` = c.`currency_id`) INNER JOIN `" . DB_PREFIX . "currency_to_country` c2c ON (cd.`currency_id` = c2c.`currency_id`) WHERE c.`status` = '1' AND cd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' AND c2c.`country_id` = '" . (int)$this->config->get('config_country_id') . "' ORDER BY cd.`title` ASC");

			foreach ($query->rows as $result) {
				$currency_data[$result['code']] = [
					'currency_id'   => $result['currency_id'],
					'title'         => $result['title'],
					'code'          => $result['code'],
					'symbol_left'   => $result['symbol_left'],
					'symbol_right'  => $result['symbol_right'],
					'decimal_place' => $result['decimal_place'],
					'value'         => $result['value'],
					'status'        => $result['status'],
					'date_modified' => $result['date_modified']
				];
			}

			$this->cache->set('currency.' . (int)$this->config->get('config_language_id'), $currency_data);
		}

		return $currency_data;
	}
}
