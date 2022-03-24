<?php
namespace Opencart\Admin\Model\Setting;
class Vendor extends \Opencart\System\Engine\Model {
	public function addVendor(string $code, string $description, string $trigger, string $action, bool $status = true, int $sort_order = 0): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "vendor` SET `name` = '" . $this->db->escape($code) . "', `code` = '" . $this->db->escape($code) . "', `version` = '" . $this->db->escape($description) . "', `date_added` = NOW()");

		return $this->db->getLastId();
	}

	public function deleteVendorByCode(string $code): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "vendor` WHERE `code` = '" . $this->db->escape($code) . "'");
	}

	public function getVendor(int $vendor_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "vendor` WHERE `vendor_id` = '" . (int)$vendor_id . "'");

		return $query->row;
	}

	public function getVendorByCode(string $code): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "vendor` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getVendors(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "vendor`";

		$sort_data = [
			'name',
			'code',
			'version',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `name`";
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

	public function getTotalVendors(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "vendor`");

		return (int)$query->row['total'];
	}
}
