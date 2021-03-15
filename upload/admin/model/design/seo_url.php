<?php
class ModelDesignSeoUrl extends Model {
	public function addSeoUrl($data) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
	}

	public function editSeoUrl($seo_url_id, $data) {
		$this->db->query("UPDATE `" . DB_PREFIX . "seo_url` SET store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', query = '" . $this->db->escape($data['query']) . "', keyword = '" . $this->db->escape($data['keyword']) . "' WHERE seo_url_id = '" . (int)$seo_url_id . "'");
	}

	public function deleteSeoUrl($seo_url_id) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "seo_url` WHERE seo_url_id = '" . (int)$seo_url_id . "'");
	}
	
	public function getSeoUrl($seo_url_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE seo_url_id = '" . (int)$seo_url_id . "'");

		return $query->row;
	}

	public function getSeoUrls($data = array()) {
		$sql = "SELECT *, (SELECT `name` FROM `" . DB_PREFIX . "store` s WHERE s.store_id = su.store_id) AS store, (SELECT `name` FROM `" . DB_PREFIX . "language` l WHERE l.language_id = su.language_id) AS language FROM `" . DB_PREFIX . "seo_url` su";

		$implode = array();

		if (!empty($data['filter_query'])) {
			$implode[] = "`query` LIKE '" . $this->db->escape($data['filter_query']) . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "`keyword` LIKE '" . $this->db->escape($data['filter_keyword']) . "'";
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
		
		$sort_data = array(
			'query',
			'keyword',
			'language_id',
			'store_id'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY query";
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

		if (!empty($data['filter_query'])) {
			$implode[] = "query LIKE '" . $this->db->escape($data['filter_query']) . "'";
		}
		
		if (!empty($data['filter_keyword'])) {
			$implode[] = "keyword LIKE '" . $this->db->escape($data['filter_keyword']) . "'";
		}
		
		if (!empty($data['filter_store_id']) && $data['filter_store_id'] !== '') {
			$implode[] = "store_id = '" . (int)$data['filter_store_id'] . "'";
		}
				
		if (!empty($data['filter_language_id']) && $data['filter_language_id'] !== '') {
			$implode[] = "language_id = '" . (int)$data['filter_language_id'] . "'";
		}
		
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}		
		
		$query = $this->db->query($sql);

		return $query->row['total'];
	}
	
	public function getSeoUrlsByKeyword($keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE keyword = '" . $this->db->escape($keyword) . "'");

		return $query->rows;
	}	
	
	public function getSeoUrlsByQuery($query) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = '" . $this->db->escape($query) . "'");

		return $query->rows;
	}
	
	public function getSeoUrlsByQueryId($seo_url_id, $query) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE query = '" . $this->db->escape($query) . "' AND seo_url_id != '" . (int)$seo_url_id . "'");

		return $query->rows;
	}	

	public function getSeoUrlsByKeywordId($seo_url_id, $keyword) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "seo_url` WHERE keyword = '" . $this->db->escape($keyword) . "' AND seo_url_id != '" . (int)$seo_url_id . "'");

		return $query->rows;
	}	
}