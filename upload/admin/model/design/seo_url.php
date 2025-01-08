<?php
namespace Opencart\Admin\Model\Design;
/**
 * Class Seo Url
 *
 * Can be loaded using $this->load->model('design/seo_url');
 *
 * @package Opencart\Admin\Model\Design
 */
class SeoUrl extends \Opencart\System\Engine\Model {
	/**
	 * Add Seo Url
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
	 * $this->load->model('design/seo_url');
	 *
	 * $seo_url_id = $this->model_design_seo_url->addSeoUrl($key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function addSeoUrl(string $key, string $value, string $keyword, int $store_id, int $language_id, int $sort_order = 0): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', `keyword` = '" . $this->db->escape($keyword) . "', `sort_order` = '" . (int)$sort_order . "'");

		return $this->db->getLastId();
	}

	/**
	 * Edit Seo Url
	 *
	 * @param int    $seo_url_id  primary key of the Seo Url record
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
	 * $this->load->model('design/seo_url');
	 *
	 * $this->model_design_seo_url->editSeoUrl($seo_url_id, $key, $value, $keyword, $store_id, $language_id, $sort_order);
	 */
	public function editSeoUrl(int $seo_url_id, string $key, string $value, string $keyword, int $store_id, int $language_id, int $sort_order = 0): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$store_id . "', `language_id` = '" . (int)$language_id . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', `keyword` = '" . $this->db->escape((string)$keyword) . "', `sort_order` = '" . (int)$sort_order . "' WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	/**
	 * Delete Seo Url
	 *
	 * @param int $seo_url_id primary key of the Seo Url record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $this->model_design_seo_url->deleteSeoUrl($seo_url_id);
	 */
	public function deleteSeoUrl(int $seo_url_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	/**
	 * Delete Seo Urls by Key Value pair
	 *
	 * @param string $key
	 * @param string $value
	 * @param int    $store_id    primary key of the store record
	 * @param int    $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $this->model_design_seo_url->deleteSeoUrlsByKeyValue($key, $value, $store_id, $language_id);
	 */
	public function deleteSeoUrlsByKeyValue(string $key, string $value, int $store_id = 0, int $language_id = 0): void {
		$sql = "DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` LIKE '" . $this->db->escape($value) . "'";

		if ($store_id) {
			$sql .= " AND `store_id` = '" . (int)$store_id . "'";
		}

		if ($language_id) {
			$sql .= " AND `language_id` = '" . (int)$language_id . "'";
		}

		$this->db->query($sql);
	}

	/**
	 * Delete Seo Urls By Language ID
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $this->model_design_seo_url->deleteSeoUrlsByLanguageId($language_id);
	 */
	public function deleteSeoUrlsByLanguageId(int $language_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `language_id` = '" . (int)$language_id . "'");
	}

	/**
	 * Delete Seo Urls By Store ID
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return void
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $this->model_design_seo_url->deleteSeoUrlsByStoreId($store_id);
	 */
	public function deleteSeoUrlsByStoreId(int $store_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `store_id` = '" . (int)$store_id . "'");
	}

	/**
	 * Get Seo Url
	 *
	 * @param int $seo_url_id primary key of the Seo Url record
	 *
	 * @return array<string, mixed> seo url record that has seo url ID
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $seo_url_info = $this->model_design_seo_url->getSeoUrl($seo_url_id);
	 */
	public function getSeoUrl(int $seo_url_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");

		return $query->row;
	}

	/**
	 * Get Seo Url By Key Value
	 *
	 * @param string $key
	 * @param string $value
	 * @param int    $store_id    primary key of the store record
	 * @param int    $language_id primary key of the language record
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyValue($key, $value, $store_id, $language_id);
	 */
	public function getSeoUrlByKeyValue(string $key, string $value, int $store_id = 0, int $language_id = 0): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` LIKE '" . $this->db->escape($value) . "'";

		if ($store_id) {
			$sql .= " AND `store_id` = '" . (int)$store_id . "'";
		}

		if ($language_id) {
			$sql .= " AND `language_id` = '" . (int)$language_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	/**
	 * Get Seo Url By Keyword
	 *
	 * @param string $keyword
	 * @param int    $store_id    primary key of the store record
	 * @param int    $language_id primary key of the language record
	 *
	 * @return array<string, mixed>
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $seo_url_info = $this->model_design_seo_url->getSeoUrlByKeyword($keyword, $store_id, $language_id);
	 */
	public function getSeoUrlByKeyword(string $keyword, int $store_id, int $language_id = 0): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE (`keyword` = '" . $this->db->escape($keyword) . "' OR LCASE(`keyword`) LIKE '" . $this->db->escape('%/' . oc_strtolower($keyword)) . "') AND `store_id` = '" . (int)$store_id . "'";

		if ($language_id) {
			$sql .= " AND `language_id` = '" . (int)$language_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row;
	}

	/**
	 * Get Seo Urls
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return array<int, array<string, mixed>> seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $results = $this->model_design_seo_url->getSeoUrls();
	 */
	public function getSeoUrls(array $data = []): array {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` `s` WHERE `s`.`store_id` = `su`.`store_id`) AS `store`, (SELECT `name` FROM `" . DB_PREFIX . "language` `l` WHERE `l`.`language_id` = `su`.`language_id`) AS `language` FROM `" . DB_PREFIX . "seo_url` `su`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "LCASE(`key`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_key'])) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "LCASE(`value`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_value'])) . "'";
		}

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id']) && $data['filter_language_id'] !== '') {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = [
			'keyword',
			'key',
			'value',
			'sort_order',
			'store_id',
			'language_id'
		];

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY `" . $data['sort'] . "`";
		} else {
			$sql .= " ORDER BY `key`";
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
	 * Get Seo Urls By Key Value
	 *
	 * @param string $key
	 * @param string $value
	 *
	 * @return array<int, array<int, string>>
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $results = $this->model_design_seo_url->getSeoUrlsByKeyValue($key, $value);
	 */
	public function getSeoUrlsByKeyValue(string $key, string $value): array {
		$seo_url_data = [];

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` LIKE '" . $this->db->escape($value) . "'");

		foreach ($query->rows as $result) {
			$seo_url_data[$result['store_id']][$result['language_id']] = $result['keyword'];
		}

		return $seo_url_data;
	}

	/**
	 * Get Seo Urls By Store Id
	 *
	 * @param int $store_id primary key of the store record
	 *
	 * @return array<int, array<string, mixed>> seo url records that have store ID
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $results = $this->model_design_seo_url->getSeoUrlsByStoreId($store_id);
	 */
	public function getSeoUrlsByStoreId(int $store_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `store_id` = '" . (int)$store_id . "'");

		return $query->rows;
	}

	/**
	 * Get Seo Urls By Language Id
	 *
	 * @param int $language_id primary key of the language record
	 *
	 * @return array<int, array<string, mixed>> seo url records that have language ID
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $results = $this->model_design_seo_url->getSeoUrlsByLanguageId($language_id);
	 */
	public function getSeoUrlsByLanguageId(int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `language_id` = '" . (int)$language_id . "'");

		return $query->rows;
	}

	/**
	 * Get Total Seo Urls
	 *
	 * @param array<string, mixed> $data array of filters
	 *
	 * @return int total number of seo url records
	 *
	 * @example
	 *
	 * $this->load->model('design/seo_url');
	 *
	 * $seo_url_total = $this->model_design_seo_url->getTotalSeoUrls();
	 */
	public function getTotalSeoUrls(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "seo_url`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "LCASE(`keyword`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_keyword'])) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "LCASE(`key`) = '" . $this->db->escape(oc_strtolower($data['filter_key'])) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "LCASE(`value`) LIKE '" . $this->db->escape(oc_strtolower($data['filter_value'])) . "'";
		}

		if (!empty($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id']) && $data['filter_language_id'] !== '') {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return (int)$query->row['total'];
	}
}
