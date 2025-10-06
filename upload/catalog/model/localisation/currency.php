<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Currency
 *
 * Can be called using $this->load->model('localisation/currency');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Currency extends \Opencart\System\Engine\Model {
	/**
	 * Edit Value By Code
	 *
	 * @param string $code
	 * @param float  $value
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $this->model_localisation_currency->editValueByCode($code, $value);
	 */
	public function editValueByCode(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape($code) . "'");

		$this->cache->delete('currency');
	}

	/**
	 * Get Currency
	 *
	 * Get the record of the currency record in the database.
	 *
	 * @param int $currency_id primary key of the currency record
	 *
	 * @return array<string, mixed> currency record that has currency ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $currency_info = $this->model_localisation_currency->getCurrency($currency_id);
	 */
	public function getCurrency(int $currency_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		return $query->row;
	}

	/**
	 * Get Currency By Code
	 *
	 * @param string $currency primary key of the currency record
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $currency_info = $this->model_localisation_currency->getCurrencyByCode($currency);
	 */
	public function getCurrencyByCode(string $currency): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Currencies
	 *
	 * Get the record of the currency records in the database.
	 *
	 * @return array<string, array<string, mixed>> currency records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $currencies = $this->model_localisation_currency->getCurrencies();
	 */
	public function getCurrencies(): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency` WHERE `status` = '1' ORDER BY `title` ASC";

		$currency_data = $this->cache->get('currency.' . md5($sql));

		if (!$currency_data) {
			$currency_data = [];

			$query = $this->db->query($sql);

			foreach ($query->rows as $result) {
				$currency_data[$result['code']] = $result;
			}

			$this->cache->set('currency.' . md5($sql), $currency_data);
		}

		return $currency_data;
	}
}
