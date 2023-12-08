<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class OrderStatus
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class OrderStatus extends \Opencart\System\Engine\Model {
	/**
	 * @param int $order_status_id
	 *
	 * @return array
	 */
	public function getOrderStatus(int $order_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * @return array
	 */
	public function getOrderStatuses(): array {
		$sql = "SELECT `order_status_id`, `name` FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

		$key = md5($sql);

		$order_status_data = $this->cache->get('order_status.' . $key);

		if (!$order_status_data) {
			$query = $this->db->query($sql);

			$order_status_data = $query->rows;

			$this->cache->set('order_status.' . $key, $order_status_data);
		}
		
		return $order_status_data;
	}
}
