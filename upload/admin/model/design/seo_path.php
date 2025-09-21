<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Seo Path
 *
 * Can be loaded using $this->load->model('design/seo_path');
 *
 * @package Opencart\Admin\Model\Design
 */
class SeoPath extends \Opencart\System\Engine\Model {
	/**
	 * Add Seo Path
	 *
	 * @param string $key
	 * @param string $value
	 * @param string $keyword
	 * @param int    $store_id    primary key of the store record
	 * @param int    $language_id primary key of the language record
	 * @param int    $sort_order
	 *
	 * @return int
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $seo_path_id = $this->model_design_seo_path->addSeoPath($key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function addSeoPath(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_path` SET `query_match` = '" . $this->db->escape($data['query_match'])  . "', `query_replace` = '" . $this->db->escape($data['query_replace'])  . "', `path_match` = '" . $this->db->escape($data['path_match']) . "', `path_replace` = '" . $this->db->escape($data['path_replace']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Seo Path
	 *
	 * @param int    $seo_path_id  primary key of the Seo Url record
	 * @param string $key
	 * @param string $value
	 * @param string $keyword
	 * @param int    $store_id    primary key of the store record
	 * @param int    $language_id primary key of the language record
	 * @param int    $sort_order
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $this->model_design_seo_path->editSeoPath($seo_path_id, $key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function editSeoPath(int $seo_path_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_path` SET `query_match` = '" . $this->db->escape($data['query_match'])  . "', `query_replace` = '" . $this->db->escape($data['query_replace'])  . "', `path_match` = '" . $this->db->escape($data['path_match']) . "', `path_replace` = '" . $this->db->escape($data['path_replace']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `seo_path_id` = '" . (int)$seo_path_id . "'");
	}

	/**
	 * Delete Seo Path
	 *
	 * @param int $seo_path_id primary key of the Seo Url record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $this->model_design_seo_path->deleteSeoPath($seo_path_id);
	 */
	public function deleteSeoPath(int $seo_path_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_path` WHERE `seo_path_id` = '" . (int)$seo_path_id . "'");
	}

	/**
	 * Get Seo Url
	 *
	 * @param int $seo_path_id primary key of the Seo Url record
	 *
	 * @return array<string, mixed> seo url record that has seo url ID
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $seo_path_info = $this->model_design_seo_path->getSeoPath($seo_path_id);
	 */
	public function getSeoPath(int $seo_path_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_path` WHERE `seo_path_id` = '" . (int)$seo_path_id . "'");

		return $query->row;
	}


	/**
	 * Get Seo Paths
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $results = $this->model_design_seo_path->getSeoPaths();
	 */
	public function getSeoPaths(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_path`";

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
	 * Get Total Seo Paths
	 *
	 * @return int total number of seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_path');
	 *
	 * $seo_path_total = $this->model_design_seo_path->getTotalSeoPaths();
	 */
	public function getTotalSeoPaths(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "seo_path`");

		return (int)$query->row['total'];
	}
}
