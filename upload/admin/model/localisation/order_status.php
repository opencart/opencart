<?php
namespace Opencart\Admin\Model\Localisation;
class OrderStatus extends \Opencart\System\Engine\Model {
	public function addOrderStatus(array $data): int {
		foreach ($data['order_status'] as $language_id => $value) {
			if (isset($order_status_id)) {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
			} else {
				$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");

				$order_status_id = $this->db->getLastId();
			}
		}

		$this->cache->delete('order_status');

		return $order_status_id;
	}

	public function editOrderStatus(int $order_status_id, array $data): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		foreach ($data['order_status'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "order_status` SET `order_status_id` = '" . (int)$order_status_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		$this->cache->delete('order_status');
	}

	public function deleteOrderStatus(int $order_status_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		$this->cache->delete('order_status');
	}

	public function getOrderStatus(int $order_status_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	public function getOrderStatuses(array $data = []): array {
		if ($data) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'";

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
			$order_status_data = $this->cache->get('order_status.' . (int)$this->config->get('config_language_id'));

			if (!$order_status_data) {
				$query = $this->db->query("SELECT `order_status_id`, `name` FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' ORDER BY `name`");

				$order_status_data = $query->rows;

				$this->cache->set('order_status.' . (int)$this->config->get('config_language_id'), $order_status_data);
			}

			return $order_status_data;
		}
	}

	public function getDescriptions(int $order_status_id): array {
		$order_status_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_status` WHERE `order_status_id` = '" . (int)$order_status_id . "'");

		foreach ($query->rows as $result) {
			$order_status_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $order_status_data;
	}

	public function getTotalOrderStatuses(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "order_status` WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return (int)$query->row['total'];
	}
}
