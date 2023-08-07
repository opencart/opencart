<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class StockStatus
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class StockStatus extends \Opencart\System\Engine\Model {
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

		$stock_status_data = $this->cache->get('stock_status.'. md5($sql));

		if (!$stock_status_data) {
			$query = $this->db->query($sql);

			$stock_status_data = $query->rows;

			$this->cache->set('stock_status.'. md5($sql), $stock_status_data);
		}

		return $stock_status_data;
	}
}
