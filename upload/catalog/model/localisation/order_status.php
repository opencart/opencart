<?php
namespace Opencart\Catalog\Model\Localisation;
/**
 * Class Order Status
 *
 * Can be called using $this->load->model('localisation/order_status');
 *
 * @package Opencart\Catalog\Model\Localisation
 */
class OrderStatus extends \Opencart\System\Engine\Model {
	/**
	 * Get Order Status
	 *
	 * Get the record of the order status record in the database.
	 *
	 * @param int $order_status_id primary key of the order status record
	 *
	 * @return array<string, mixed> order status record that has order status ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_status_id);
	 */
	public function getOrderStatus(int $order_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Order Statuses
	 *
	 * Get the record of the order status records in the database.
	 *
	 * @return array<int, array<string, mixed>> order status records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
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
