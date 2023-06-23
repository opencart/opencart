<?php
namespace Opencart\Catalog\Model\Localisation;
class StockStatus extends \Opencart\System\Engine\Model {
	public function getStockStatus(int $stock_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `stock_status_id` = '" . (int)$stock_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getStockStatuses(array $data = []): array {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'";

			$sql .= " ORDER BY `name`";

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

			$query = $this->db->query($sql);

			return $query->rows;
		} else {
			$stock_status_data = $this->cache->get('stock_status.' . (int)$this->config->get('config_language_id'));

			if (!$stock_status_data) {
				$query = $this->db->query("SELECT `stock_status_id`, `name` FROM `" . DB_PREFIX . "stock_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`");

				$stock_status_data = $query->rows;

				$this->cache->set('stock_status.' . (int)$this->config->get('config_language_id'), $stock_status_data);
			}

			return $stock_status_data;
		}
	}
}
