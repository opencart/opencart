<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class StockStatus
 *
 * @package Opencart\Admin\Model\Localisation
 */
class StockStatus extends \Opencart\System\Engine\Model {
	/**
	 * @param array $data
	 *
	 * @return int
	 */
	public function addStockStatus(array $data): int {
		foreach ($data['stock_status'] as $language_id => $value) {
			if (isset($stock_status_id)) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `stock_status_id` = '" . (int)$stock_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");

				$stock_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('stock_status');

		return $stock_status_id;
	}

	/**
	 * @param int   $stock_status_id
	 * @param array $data
	 *
	 * @return void
	 */
	public function editStockStatus(int $stock_status_id, array $data): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "'");

		foreach ($data['stock_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "stock_status` SET `stock_status_id` = '" . (int)$stock_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('stock_status');
	}

	/**
	 * @param int $stock_status_id
	 *
	 * @return void
	 */
	public function deleteStockStatus(int $stock_status_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "'");

		$this->cache->delete('stock_status');
	}

	/**
	 * @param int $stock_status_id
	 *
	 * @return array
	 */
	public function getStockStatus(int $stock_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @param array $data
	 *
	 * @return array
	 */
	public function getStockStatuses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

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

		$stock_status_data = $this->cache->get('stock_status.' . md5($sql));

		if (!$stock_status_data) {
			$query = $this->db->query($sql);

			$stock_status_data = $query->rows;

			$this->cache->set('stock_status.' . md5($sql), $stock_status_data);
		}

		return $stock_status_data;
	}

	/**
	 * @param int $stock_status_id
	 *
	 * @return array
	 */
	public function getDescriptions(int $stock_status_id): array {
		$stock_status_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "'");

		foreach ($query->rows as $result) {
			$stock_status_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $stock_status_data;
	}

	/**
	 * @return int
	 */
	public function getTotalStockStatuses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
