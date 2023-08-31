<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Currency
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Currency extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addCurrency(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_modified` = NOW()");

		$this->cache->delete('currency');

		return $this->db->getLastId();
	}

	/**
	 * @param int   $currency_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editCurrency(int $currency_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)(isset($data['status']) ? $data['status'] : 0) . "', `date_modified` = NOW() WHERE `currency_id` = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

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
	 * @return void
	 */
	public function deleteCurrency(int $currency_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

	/**
	 * @param int $currency_id
	 *
	 * @return array
	 */
	public function getCurrency(int $currency_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

		return $query->row;
	}

	/**
	 * @param string $currency
	 *
	 * @return array
	 */
	public function getCurrencyByCode(string $currency): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getCurrencies(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "currency`";

		$sort_data = [
			'title',
			'code',
			'value',
			'date_modified'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `title`";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$results = (array)$this->cache->get('currency.' . md5($sql));

		if (!$results) {
			$query = $this->db->query($sql);

			$results = $query->rows;

			$this->cache->set('currency.' . md5($sql), $results);
		}

		$currency_data = [];

		foreach ($results as $result) {
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

		return $currency_data;
	}

	/**
	 * @return int
	 */
	public function getTotalCurrencies(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "currency`");

		return (int)$query->row['total'];
	}
}
