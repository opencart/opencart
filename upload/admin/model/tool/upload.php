<?php
namespace Opencart\Admin\Model\Tool;
use \Opencart\System\Helper as Helper;
class Upload extends \Opencart\System\Engine\Model {
	public function addUpload(string $name, string $filename): string {
		$code = Helper\General\token(32);

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}

	public function deleteUpload(int $upload_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "upload` WHERE `upload_id` = '" . (int)$upload_id . "'");
	}

	public function getUpload(int $upload_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE `upload_id` = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function getUploadByCode(string $code): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE `code` = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getUploads(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "upload`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "`code` LIKE '" . $this->db->escape((string)$data['filter_code'] . '%') . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'name',
			'code',
			'date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `date_added`";
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

	public function getTotalUploads(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "upload`";

		$implode = [];

		if (!empty($data['filter_name'])) {
			$implode[] = "`name` LIKE '" . $this->db->escape((string)$data['filter_name'] . '%') . "'";
		}

		if (!empty($data['filter_code'])) {
			$implode[] = "`code` LIKE '" . $this->db->escape((string)$data['filter_code'] . '%') . "'";
		}

		if (!empty($data['filter_date_from'])) {
			$implode[] = "DATE(`date_added`) >= DATE('" . $this->db->escape((string)$data['filter_date_from']) . "')";
		}

		if (!empty($data['filter_date_to'])) {
			$implode[] = "DATE(`date_added`) <= DATE('" . $this->db->escape((string)$data['filter_date_to']) . "')";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
