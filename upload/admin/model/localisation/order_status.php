<?php
namespace Opencart\Admin\Model\Localisation;
/**
 * Class Order Status
 *
 * Can be loaded using $this->load->model('localisation/order_status');
 *
 * @package Opencart\Admin\Model\Localisation
 */
class OrderStatus extends \Opencart\System\Engine\Model {
	/**
	 * Add Order Status
	 *
	 * Create a new order status record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return ?int
	 *
	 * @example
	 *
	 * $order_status_data['order_status'][1] = [
	 *     'name'        => 'Order Status Name'
	 * ];
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $order_status_id = $this->model_localisation_order_status->addOrderStatus($order_status_data);
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
	 * Edit order status record in the database.
	 *
	 * @param int                  $order_status_id primary key of the order status record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $order_status_data['order_status'][1] = [
	 *     'name'        => 'Order Status Name'
	 * ];
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $this->model_localisation_order_status->editOrderStatus($order_status_id, $order_status_data);
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
	 * Delete order status record in the database.
	 *
	 * @param int $order_status_id primary key of the order status record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $this->model_localisation_order_status->deleteOrderStatus($order_status_id);
	 */
	public function deleteOrderStatus(int $order_status_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		$this->cache->delete('order_status');
	}

	/**
	 * Delete Order Statuses By Language ID
	 *
	 * Delete order statuses by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $this->model_localisation_order_status->deleteOrderStatusesByLanguageId($language_id);
	 */
	public function deleteOrderStatusesByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$language_id . "'");

		$this->cache->delete('order_status');
	}

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
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> order status records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'sort'  => 'name',
	 *     'order' => 'DESC',
	 *     'start' => 0,
	 *     'limit' => 10
	 * ];
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $results = $this->model_localisation_order_status->getOrderStatuses($filter_data);
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
	 * Create a new order status description record in the database.
	 *
	 * @param int                  $order_status_id primary key of the order status record
	 * @param int                  $language_id     primary key of the language record
	 * @param array<string, mixed> $data            array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $order_status_data = [
	 *     'name' => 'Order Status Name'
	 * ];
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $this->model_localisation_order_status->addDescription($order_status_id, $language_id, $order_status_data);
	 */
	public function addDescription(int $order_status_id, int $language_id, array $data): void {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($data['name']) . "'");
	}

	/**
	 * Get Descriptions
	 *
	 * Get the record of the order status description records in the database.
	 *
	 * @param int $order_status_id primary key of the order status record
	 *
	 * @return array<int, array<string, string>> description records that have order status ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $order_status = $this->model_localisation_order_status->getDescriptions($order_status_id);
	 */
	public function getDescriptions(int $order_status_id): array {
		$order_status_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = $result;
		}

		return $order_status_data;
	}

	/**
	 * Get Descriptions By Language ID
	 *
	 * Get the record of the order status descriptions by language records in the database.
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, mixed>> description records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $results = $this->model_localisation_order_status->getDescriptionsByLanguageId($language_id);
	 */
	public function getDescriptionsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Order Statuses
	 *
	 * Get the total number of order status records in the database.
	 *
	 * @return int total number of order status records
	 *
	 * @example
	 *
	 * $this->load->model('localisation/order_status');
	 *
	 * $order_status_total = $this->model_localisation_order_status->getTotalOrderStatuses();
	 */
	public function getTotalOrderStatuses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
