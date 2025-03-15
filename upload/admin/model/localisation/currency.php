<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Currency
 *
 * Can be loaded using $this->load->model('localisation/currency');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class Currency extends \Opencart\System\Engine\Model {
	/**
	 * Add Currency
	 *
	 * Create a new currency record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int returns the primary key of the new currency record
	 *
	 * @example
	 *
	 * $currency_data = [
	 *     'title'         => 'Currency Title',
	 *     'code'          => 'Currency Code',
	 *     'symbol_left'   => '$',
	 *     'symbol_right'  => '',
	 *     'decimal_place' => 2,
	 *     'value'         => 0.00000000,
	 *     'status'        => 0
	 * ];
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $currency_id = $this->model_localisation_currency->addCurrency($currency_data);
	 */
	public function addCurrency(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_modified` = NOW()");

		$this->cache->delete('currency');

		return $this->db->getLastId();
	}

	/**
	 * Edit Currency
	 *
	 * Edit currency record in the database.
	 *
	 * @param int                  $currency_id primary key of the currency record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $currency_data = [
	 *     'title'         => 'Currency Title',
	 *     'code'          => 'Currency Code',
	 *     'symbol_left'   => '$',
	 *     'symbol_right'  => '',
	 *     'decimal_place' => 2,
	 *     'value'         => 0.00000000,
	 *     'status'        => 1
	 * ];
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $this->model_localisation_currency->editCurrency($currency_id, $currency_data);
	 */
	public function editCurrency(int $currency_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "currency` SET `title` = '" . $this->db->escape((string)$data['title']) . "', `code` = '" . $this->db->escape((string)$data['code']) . "', `symbol_left` = '" . $this->db->escape((string)$data['symbol_left']) . "', `symbol_right` = '" . $this->db->escape((string)$data['symbol_right']) . "', `decimal_place` = '" . (int)$data['decimal_place'] . "', `value` = '" . (float)$data['value'] . "', `status` = '" . (bool)($data['status'] ?? 0) . "', `date_modified` = NOW() WHERE `currency_id` = '" . (int)$currency_id . "'");

		$this->cache->delete('currency');
	}

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
	 * Delete Currency
	 *
	 * Delete currency record in the database.
	 *
	 * @param int $currency_id primary key of the currency record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $this->model_localisation_currency->deleteCurrency($currency_id);
	 */
	public function deleteCurrency(int $currency_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "currency` WHERE `currency_id` = '" . (int)$currency_id . "'");

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
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "currency` WHERE `code` = '" . $this->db->escape($currency) . "'");

		return $query->row;
	}

	/**
	 * Get Currencies
	 *
	 * Get the record of the currency records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<string, array<string, mixed>> currency records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'title',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $results = $this->model_localisation_currency->getCurrencies($filter_data);
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

		$results = $this->cache->get('currency.' . md5($sql));

		if (!$results) {
			$query = $this->db->query($sql);

			$results = $query->rows;

			$this->cache->set('currency.' . md5($sql), $results);
		}

		$currency_data = [];

		foreach ($results as $result) {
			$currency_data[$result['code']] = $result;
		}

		return $currency_data;
	}

	/**
	 * Get Total Currencies
	 *
	 * Get the total number of currency records in the database.
	 *
	 * @return int total number of currency records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'title',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/currency');
	 *
	 * $currency_total = $this->model_localisation_currency->getTotalCurrencies($filter_data);
	 */
	public function getTotalCurrencies(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "currency`");

		return (int)$query->row['total'];
	}
}
