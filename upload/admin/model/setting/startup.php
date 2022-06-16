<?php
namespace Opencart\Admin\Model\Setting;
class Startup extends \Opencart\System\Engine\Model {
	public function addStartup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "startup` SET `code` = '" . $this->db->escape($data['code']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	public function deleteStartup(int $startup_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	public function deleteStartupByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function editStatus(int $startup_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "startup` SET `status` = '" . (bool)$status . "' WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	public function getStartup(int $startup_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");

		return $query->row;
	}

	public function getStartupByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	public function getStartups(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "startup`";

		$sort_data = [
			'code',
			'action',
			'status',
			'sort_order',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `sort_order`";
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

		$query = $this->db->query($sql);

		return $query->rows;
	}

	public function getTotalStartups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "startup`");

		return (int)$query->row['total'];
	}
}
