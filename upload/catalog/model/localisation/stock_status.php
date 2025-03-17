<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Stock Status
 *
 * Can be called using $this->load->model('localisation/stock_status');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class StockStatus extends \Opencart\System\Engine\Model {
	/**
	 * Get Stock Status
	 *
	 * Get the record of the stock status record in the database.
	 *
	 * @param int $stock_status_id primary key of the stock status record
	 *
	 * @return array<string, mixed> stock status record that has stock status ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/stock_status');
	 *
	 * $stock_status_info = $this->model_localisation_stock_status->getStockStatus($stock_status_id);
	 */
	public function getStockStatus(int $stock_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Stock Statuses
	 *
	 * Get the record of the stock status records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> stock status records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/stock_status');
	 *
	 * $stock_statuses = $this->model_localisation_stock_status->getStockStatuses();
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

		$key = md5($sql);

		$stock_status_data = $this->cache->get('stock_status.' . $key);

		if (!$stock_status_data) {
			$query = $this->db->query($sql);

			$stock_status_data = $query->rows;

			$this->cache->set('stock_status.' . $key, $stock_status_data);
		}

		return $stock_status_data;
	}
}
