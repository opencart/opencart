<?php
namespace Opencart\Admin\Model\Setting;
/**
 * Class Startup
 *
 * @package Opencart\Admin\Model\Setting
 */
class Startup extends \Opencart\System\Engine\Model {
	/**
	 * Add Startup
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addStartup(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "startup` SET `code` = '" . $this->db->escape($data['code']) . "', `description` = '" . $this->db->escape($data['description']) . "', `action` = '" . $this->db->escape($data['action']) . "', `status` = '" . (bool)$data['status'] . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * Delete Startup
	 *
	 * @param int $startup_id
	 *
	 * @return void
	 */
	public function deleteStartup(int $startup_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	/**
	 * Delete Startup By Code
	 *
	 * @param string $code
	 *
	 * @return void
	 */
	public function deleteStartupByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	/**
	 * Edit Status
	 *
	 * @param int  $startup_id
	 * @param bool $status
	 *
	 * @return void
	 */
	public function editStatus(int $startup_id, bool $status): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "startup` SET `status` = '" . (bool)$status . "' WHERE `startup_id` = '" . (int)$startup_id . "'");
	}

	/**
	 * Get Startup
	 *
	 * @param int $startup_id
	 *
	 * @return array<string, mixed>
	 */
	public function getStartup(int $startup_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "startup` WHERE `startup_id` = '" . (int)$startup_id . "'");

		return $query->row;
	}

	/**
	 * Get Startup By Code
	 *
	 * @param string $code
	 *
	 * @return array<string, mixed>
	 */
	public function getStartupByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "startup` WHERE `code` = '" . $this->db->escape($code) . "' LIMIT 1");

		return $query->row;
	}

	/**
	 * Get Startups
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
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
			$sql .= " ORDER BY " . $data['sort'];
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

	/**
	 * Get Total Startyps
	 *
	 * @return int
	 */
	public function getTotalStartups(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "startup`");

		return (int)$query->row['total'];
	}
}
