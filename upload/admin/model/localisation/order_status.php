<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Order Status
 *
 * @package Opencart\Admin\Model\Localisation
 */
class OrderStatus extends \Opencart\System\Engine\Model {
	/**
	 * Add Order Status
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return ?int
	 */
	public function addOrderStatus(array $data): ?int {
		$order_status_id = 0;

		foreach ($data['order_status'] as $language_id => $order_status) {
			if (!$order_status_id) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($order_status['name']) . "'");

				$order_status_id = $this->db->getLastId();
			} else {
				$this->model_localisation_order_status->addDescription($order_status_id, $language_id, $order_status);
			}
		}

		$this->cache->delete('order_status');

		return $order_status_id;
	}

	/**
	 * Edit Order Status
	 *
	 * @param int                  $order_status_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editOrderStatus(int $order_status_id, array $data): void {
		$this->deleteOrderStatus($order_status_id);

		foreach ($data['order_status'] as $language_id => $value) {
			$this->model_localisation_order_status->addDescription($order_status_id, $language_id, $value);
		}

		$this->cache->delete('order_status');
	}

	/**
	 * Delete Order Status
	 *
	 * @param int $order_status_id
	 *
	 * @return void
	 */
	public function deleteOrderStatus(int $order_status_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		$this->cache->delete('order_status');
	}

	/**
	 * Delete Order Statuses By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return void
	 */
	public function deleteOrderStatusesByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('order_status');
	}

	/**
	 * Get Order Status
	 *
	 * @param int $order_status_id
	 *
	 * @return array<string, mixed>
	 */
	public function getOrderStatus(int $order_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Order Statuses
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getOrderStatuses(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`";

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

		$order_status_data = $this->cache->get('order_status.' . $key);

		if (!$order_status_data) {
			$query = $this->db->query($sql);

			$order_status_data = $query->rows;

			$this->cache->set('order_status.' . $key, $order_status_data);
		}

		return $order_status_data;
	}

	/**
	 * Add Description
	 *
	 * @param int                  $order_status_id
	 * @param int                  $language_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function addDescription(int $order_status_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * @param int $order_status_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $order_status_id): array {
		$order_status_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $order_status_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * @param int $language_id
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Order Statuses
	 *
	 * @return int
	 */
	public function getTotalOrderStatuses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
