<?php
namespace Opencart\Application\Model\Localisation;
class Currency extends \Opencart\System\Engine\Model {
	public function getCurrencyByCode($currency) {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	public function getCurrencies() {
		$currency_data = $this->cache->get('currency');

		if (!$currency_data) {
			$currency_data = [];

			$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "currency` c LEFT JOIN `" . DB_PREFIX . "currency_description` cd ON (c.`currency_id` = cd.`currency_id`) WHERE c.`status` = '1' AND cd.`country_id` = '" . (int)$this->config->get('config_country_id') . "' AND cd.`language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY cd.`title` ASC");

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

			$this->cache->set('currency', $currency_data);
		}

		return $currency_data;
	}
}
