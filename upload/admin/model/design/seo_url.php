<?php
namespace Opencart\Admin\Model\Design;
class SeoUrl extends \Opencart\System\Engine\Model {
	public function addSeoUrl(array $data): int {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `key` = '" . $this->db->escape((string)$data['key']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `keyword` = '" . $this->db->escape((string)$data['keyword']) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");

		return $this->db->getLastId();
	}

	public function editSeoUrl(int $seo_url_id, array $data): void {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET `store_id` = '" . (int)$data['store_id'] . "', `language_id` = '" . (int)$data['language_id'] . "', `key` = '" . $this->db->escape((string)$data['key']) . "', `value` = '" . $this->db->escape((string)$data['value']) . "', `keyword` = '" . $this->db->escape((string)$data['keyword']) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	public function deleteSeoUrl(int $seo_url_id): void {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");
	}

	public function getSeoUrl(int $seo_url_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");

		return $query->row;
	}

	public function getSeoUrls(array $data = []): array {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` s WHERE s.`store_id` = su.`store_id`) AS `store`, (SELECT `name` FROM `" . DB_PREFIX . "language` l WHERE l.`language_id` = su.`language_id`) AS `language` FROM `" . DB_PREFIX . "seo_url` su";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword']) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "`key` = '" . $this->db->escape((string)$data['filter_key']) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "`value` LIKE '" . $this->db->escape((string)$data['filter_value']) . "'";
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

	public function getTotalSeoUrls(array $data = []): int {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "seo_url`";

		$implode = [];

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword']) . "'";
		}

		if (!empty($data['filter_key'])) {
			$implode[] = "`key` = '" . $this->db->escape((string)$data['filter_key']) . "'";
		}

		if (!empty($data['filter_value'])) {
			$implode[] = "`value` LIKE '" . $this->db->escape((string)$data['filter_value']) . "'";
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

	public function getSeoUrlByKeyValue(string $key, string $value, int $store_id, int $language_id): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `key` = '" . $this->db->escape($key) . "' AND `value` = '" . $this->db->escape($value) . "' AND `store_id` = '" . (int)$store_id . "' AND `language_id` = '" . (int)$language_id . "'");

		return $query->row;
	}

	public function getSeoUrlByKeyword(string $keyword, int $store_id, int $language_id = 0): array {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE (`keyword` = '" . $this->db->escape($keyword) . "' OR `keyword` LIKE '" . $this->db->escape('%/' . $keyword) . "') AND `store_id` = '" . (int)$store_id . "'";

		if ($language_id) {
			$sql .= " AND `language_id` = '" . (int)$language_id . "'";
		}

		$query = $this->db->query($sql);

		return $query->row;
	}
}
