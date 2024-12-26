<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Anti-Spam
 *
 * @example $antispam_model = $this->model_cms_antispam;
 *
 * Can be called from $this->load->model('cms/antispam');
 *
 * @package Opencart\Admin\Model\Cms
 */
class Antispam extends \Opencart\System\Engine\Model {
	/**
	 * Add Antispam
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 */
	public function addAntispam(array $data = []): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Antispam
	 *
	 * @param int                  $antispam_id primary key of the anti-spam record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 */
	public function editAntispam(int $antispam_id, array $data = []): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "' WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * Delete Antispam
	 *
	 * @param int $antispam_id primary key of the anti-spam record
	 *
	 * @return void
	 */
	public function deleteAntispam(int $antispam_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * Get Antispam
	 *
	 * @param int $antispam_id primary key of the anti-spam record
	 *
	 * @return array<string, mixed> antispam record that has antispam ID
	 */
	public function getAntispam(int $antispam_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");

		return $query->row;
	}

	/**
	 * Get Antispam(s)
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> antispam records
	 */
	public function getAntispams(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "antispam`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = ['keyword'];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `keyword`";
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
	 * Get Total Antispam(s)
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of antispam records
	 */
	public function getTotalAntispams(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "antispam`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
