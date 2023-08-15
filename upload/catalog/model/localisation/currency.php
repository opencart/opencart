<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Currency
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class Currency extends \Opencart\System\Engine\Model {
	/**
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
	 * @param int $currency_id
	 *
	 * @return array
	 */
	public function getCurrency(int $currency_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . $this->db->escape($currency_id) . "'");

		return $query->row;
	}

	/**
	 * @param string $currency
	 *
	 * @return array
	 */
	public function getCurrencyByCode(string $currency): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "' AND `status` = '1'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getCurrencies(): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency` WHERE `status` = '1' ORDER BY `title` ASC";

		$currency_data = $this->cache->get('currency.' . md5($sql));

		if (!$currency_data) {
			$currency_data = [];

			$query = $this->db->query($sql);

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

			$this->cache->set('currency.' . md5($sql), $currency_data);
		}

		return $currency_data;
	}
}
