<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Seo Regex
 *
 * Can be loaded using $this->load->model('design/seo_regex');
 *
 * @package Opencart\Admin\Model\Design
 */
class SeoRegex extends \Opencart\System\Engine\Model {
	/**
	 * Add Seo Regex
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
	 * $this->load->model('design/seo_regex');
	 *
	 * $seo_regex_id = $this->model_design_seo_regex->addSeoRegex($key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function addSeoRegex(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_regex` SET `key` = '" . $this->db->escape($data['key'])  . "', `match` = '" . $this->db->escape($data['match'])  . "', `replace` = '" . $this->db->escape($data['replace'])  . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `value` = '" . $this->db->escape($data['value']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Seo Regex
	 *
	 * @param int    $seo_regex_id  primary key of the Seo Url record
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
	 * $this->load->model('design/seo_regex');
	 *
	 * $this->model_design_seo_regex->editSeoRegex($seo_regex_id, $key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function editSeoRegex(int $seo_regex_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_regex` SET `key` = '" . $this->db->escape($data['key'])  . "', `match` = '" . $this->db->escape($data['match'])  . "', `replace` = '" . $this->db->escape($data['replace'])  . "', `keyword` = '" . $this->db->escape($data['keyword']) . "', `value` = '" . $this->db->escape($data['value']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");
	}

	/**
	 * Delete Seo Regex
	 *
	 * @param int $seo_regex_id primary key of the Seo Url record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_regex');
	 *
	 * $this->model_design_seo_regex->deleteSeoRegex($seo_regex_id);
	 */
	public function deleteSeoRegex(int $seo_regex_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_regex` WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");
	}

	/**
	 * Get Seo Url
	 *
	 * @param int $seo_regex_id primary key of the Seo Url record
	 *
	 * @return array<string, mixed> seo url record that has seo url ID
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_regex');
	 *
	 * $seo_regex_info = $this->model_design_seo_regex->getSeoRegex($seo_regex_id);
	 */
	public function getSeoRegex(int $seo_regex_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_regex` WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");

		return $query->row;
	}


	/**
	 * Get Seo Regexes
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_regex');
	 *
	 * $results = $this->model_design_seo_regex->getSeoRegexs();
	 */
	public function getSeoRegexes(array $data = []): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_regex`";

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
	 * Get Total Seo Regexes
	 *
	 * @return int total number of seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_regex');
	 *
	 * $seo_regex_total = $this->model_design_seo_regex->getTotalSeoRegexs();
	 */
	public function getTotalSeoRegexes(): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "seo_regex`");

		return (int)$query->row['total'];
	}
}
