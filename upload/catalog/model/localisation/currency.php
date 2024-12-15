<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Currency
 *
 * Can be called from $this->load->model('localisation/currency');
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
	 */
	public function editValueByCode(string $code, float $value): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `value` = '" . (float)$value . "', `date_modified` = NOW() WHERE `code` = '" . $this->db->escape($code) . "'");

		$this->cache->delete('currency');
	}

	/**
	 * Get Currency
	 *
	 * @param int $currency_id
	 *
	 * @return array<string, mixed>
	 */
	public function getCurrency(int $currency_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		return $query->row;
	}

	/**
	 * Get Currency By Code
	 *
	 * @param string $currency
	 *
	 * @return array<string, mixed>
	 */
	public function getCurrencyByCode(string $currency): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * Get Currencies
	 *
	 * @return array<string, array<string, mixed>>
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
