<?php
class ModelDesignSeoRegex extends Model {
	public function addSeoRegex($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_regex` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `regex` = '" . $this->db->escape(html_entity_decode((string)$data['regex'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "'");
	}

	public function editSeoRegex($seo_regex_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_regex` SET `name` = '" . $this->db->escape((string)$data['name']) . "', `regex` = '" . $this->db->escape(html_entity_decode((string)$data['regex'], ENT_QUOTES, 'UTF-8')) . "', `sort_order` = '" . (int)$data['sort_order'] . "' WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");
	}

	public function deleteSeoRegex($seo_regex_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_regex` WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");
	}
	
	public function getSeoRegex($seo_regex_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_regex` WHERE `seo_regex_id` = '" . (int)$seo_regex_id . "'");

		return $query->row;
	}

	public function getSeoRegexes($data = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "seo_regex`";

		$sort_data = array(
			'name',
			'regex',
			'sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
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

	public function getTotalSeoRegexes($data = array()) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "seo_regex`");

		return $query->row['total'];
	}
}