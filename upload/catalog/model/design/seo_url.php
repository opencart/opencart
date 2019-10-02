<?php
class ModelDesignSeoUrl extends Model {
	public function getSeoUrl($seo_url_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `seo_url_id` = '" . (int)$seo_url_id . "'");

		return $query->row;
	}

	public function getSeoUrls($data = array()) {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` s WHERE s.`store_id` = su.`store_id`) AS `store`, (SELECT `name` FROM `" . DB_PREFIX . "language` l WHERE l.`language_id` = su.`language_id`) AS language FROM `" . DB_PREFIX . "seo_url` `su`";

		$implode = array();

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword']) . "'";
		}

		if (!empty($data['filter_query'])) {
			$implode[] = "`query` LIKE '" . $this->db->escape((string)$data['filter_query']) . "'";
		}

		if (isset($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id'])) {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'keyword',
			'query',
			'store_id',
			'language_id'
		);

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

	public function getTotalSeoUrls($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "seo_url`";

		$implode = array();

		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape((string)$data['filter_keyword']) . "'";
		}

		if (!empty($data['filter_query'])) {
			$implode[] = "`query` LIKE '" . $this->db->escape((string)$data['filter_query']) . "'";
		}

		if (!empty($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "`store_id` = '" . (int)$data['filter_store_id'] . "'";
		}

		if (!empty($data['filter_language_id'])) {
			$implode[] = "`language_id` = '" . (int)$data['filter_language_id'] . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function getSeoUrlsByKeyword($keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `keyword` = '" . $this->db->escape($keyword) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}

	public function getKeywordByQuery($query) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE `query` = '" . $this->db->escape($query) . "' AND `store_id` = '" . (int)$this->config->get('config_store_id') . "' AND `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		if ($query->row) {
			return $query->row['keyword'];
		} else {
			return '';
		}
	}
}