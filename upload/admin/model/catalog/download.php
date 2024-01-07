<?php
namespace Opencart\Admin\Model\Catalog;
/**
 * Class Download
 *
 * @package Opencart\Admin\Model\Catalog
 */
class Download extends \Opencart\System\Engine\Model {
	/**
	 * Add Download
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addDownload(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "download` SET `filename` = '" . $this->db->escape((string)$data['filename']) . "', `mask` = '" . $this->db->escape((string)$data['mask']) . "', `date_added` = NOW()");

		$download_id = $this->db->getLastId();

		foreach ($data['download_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "download_description` SET `download_id` = '" . (int)$download_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}

		return $download_id;
	}

	/**
	 * Edit Download
	 *
	 * @param int                  $download_id
	 * @param array<string, mixed> $data
	 *
	 * @return void
	 */
	public function editDownload(int $download_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "download` SET `filename` = '" . $this->db->escape((string)$data['filename']) . "', `mask` = '" . $this->db->escape((string)$data['mask']) . "' WHERE `download_id` = '" . (int)$download_id . "'");

		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_description` WHERE `download_id` = '" . (int)$download_id . "'");

		foreach ($data['download_description'] as $language_id => $value) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "download_description` SET `download_id` = '" . (int)$download_id . "', `language_id` = '" . (int)$language_id . "', `name` = '" . $this->db->escape($value['name']) . "'");
		}
	}

	/**
	 * Delete Download
	 *
	 * @param int $download_id
	 *
	 * @return void
	 */
	public function deleteDownload(int $download_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download` WHERE `download_id` = '" . (int)$download_id . "'");
		$this->db->query("DELETE FROM `" . DB_PREFIX . "download_description` WHERE `download_id` = '" . (int)$download_id . "'");
	}

	/**
	 * Get Download
	 *
	 * @param int $download_id
	 *
	 * @return array<string, mixed>
	 */
	public function getDownload(int $download_id): array {
		$query = $this->db->query("SELECT DISTINCT * FROM `" . DB_PREFIX . "download` `d` LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `d`.`download_id` = '" . (int)$download_id . "' AND `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->row;
	}

	/**
	 * Get Downloads
	 *
	 * @param array<string, mixed> $data
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getDownloads(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "download` `d` LEFT JOIN `" . DB_PREFIX . "download_description` `dd` ON (`d`.`download_id` = `dd`.`download_id`) WHERE `dd`.`language_id` = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND LCASE(`dd`.`name`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_name']) . '%') . "'";
		}

		$sort_data = [
			'dd.name',
			'd.date_added'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY `dd`.`name`";
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
	 * Get Descriptions
	 *
	 * @param int $download_id
	 *
	 * @return array<int, array<string, string>>
	 */
	public function getDescriptions(int $download_id): array {
		$download_description_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "download_description` WHERE `download_id` = '" . (int)$download_id . "'");

		foreach ($query->rows as $result) {
			$download_description_data[$result['language_id']] = ['name' => $result['name']];
		}

		return $download_description_data;
	}

	/**
	 * Get Total Downloads
	 *
	 * @return int
	 */
	public function getTotalDownloads(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "download`");

		return (int)$query->row['total'];
	}

	/**
	 * Get Reports
	 *
	 * @param int $download_id
	 * @param int $start
	 * @param int $limit
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getReports(int $download_id, int $start = 0, int $limit = 10): array {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT `ip`, `store_id`, `country`, `date_added` FROM `" . DB_PREFIX . "download_report` WHERE `download_id` = '" . (int)$download_id . "' ORDER BY `date_added` ASC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	/**
	 * Get Total Reports
	 *
	 * @param int $download_id
	 *
	 * @return int
	 */
	public function getTotalReports(int $download_id): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "download_report` WHERE `download_id` = '" . (int)$download_id . "'");

		return (int)$query->row['total'];
	}
}
