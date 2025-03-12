<?php
namespace Opencart\Admin\Model\Cms;
/**
 * Class Anti-Spam
 *
 * Can be loaded using $this->load->model('cms/antispam');
 *
 * @package Opencart\Admin\Model\Cms
 */
class Antispam extends \Opencart\System\Engine\Model {
	/**
	 * Add Antispam
	 *
	 * Create a new antispam record in the database.
	 *
	 * @param array<string, mixed> $data array of data
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $antispam_data = [
	 *     'keyword' => 'Keyword'
	 * ];
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $antispam_id = $this->model_cms_antispam->addAntispam($antispam_data);
	 */
	public function addAntispam(array $data = []): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Antispam
	 *
	 * Edit antispam record in the database.
	 *
	 * @param int                  $antispam_id primary key of the antispam record
	 * @param array<string, mixed> $data        array of data
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $antispam_data = [
	 *     'keyword' => 'Keyword'
	 * ];
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $this->model_cms_antispam->editAntispam($antispam_id, $antispam_data);
	 */
	public function editAntispam(int $antispam_id, array $data = []): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "antispam` SET `keyword` = '" . $this->db->escape((string)$data['keyword']) . "' WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * Delete Antispam
	 *
	 * Delete antispam record in the database.
	 *
	 * @param int $antispam_id primary key of the antispam record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $this->model_cms_antispam->deleteAntispam($antispam_id);
	 */
	public function deleteAntispam(int $antispam_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");
	}

	/**
	 * Get Antispam
	 *
	 * Get the record of the antispam record in the database.
	 *
	 * @param int $antispam_id primary key of the antispam record
	 *
	 * @return array<string, mixed> antispam record that has antispam ID
	 *
	 * @example
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $antispam_info = $this->model_cms_antispam->getAntispam($antispam_id);
	 */
	public function getAntispam(int $antispam_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "antispam` WHERE `antispam_id` = '" . (int)$antispam_id . "'");

		return $query->row;
	}

	/**
	 * Get Antispam(s)
	 *
	 * Get the record of the antispam records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> antispam records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_keyword' => 'Keyword',
	 *     'sort'           => 'keyword',
	 *     'order'          => 'DESC',
	 *     'start'          => 0,
	 *     'limit'          => 10
	 * ];
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $results = $this->model_cms_antispam->getAntispams($filter_data);
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
	 * Get the total number of antispam records in the database.
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of antispam records
	 *
	 * @example
	 *
	 * $filter_data = [
	 *     'filter_keyword' => 'Keyword',
	 *     'sort'           => 'keyword',
	 *     'order'          => 'DESC',
	 *     'start'          => 0,
	 *     'limit'          => 10
	 * ];
	 *
	 * $this->load->model('cms/antispam');
	 *
	 * $antispam_total = $this->model_cms_antispam->getTotalAntispams($filter_data);
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
