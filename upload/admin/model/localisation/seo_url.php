<?php
class ModelLocalisationSeoUrl extends Model {
	public function addSeoUrl($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($data['type']) . "=" . $this->db->escape($data['route']) . "', keyword = '" . $this->db->escape($data['keyword']) . "'");

		if ($data['type'] == 'product_id') {
			$this->cache->delete('product');
		} elseif ($data['type'] == 'category_id') {
			$this->cache->delete('category');
		} elseif ($data['type'] == 'manufacturer_id') {
			$this->cache->delete('manufacturer');
		} elseif ($data['type'] == 'information_id') {
			$this->cache->delete('information');
		}
	}

	public function editSeoUrl($url_alias_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($data['type']) . "=" . $this->db->escape($data['route']) . "', keyword = '" . $this->db->escape($data['keyword']) . "' WHERE url_alias_id = '" . (int)$url_alias_id . "'");

		if ($data['type'] == 'product_id') {
			$this->cache->delete('product');
		} elseif ($data['type'] == 'category_id') {
			$this->cache->delete('category');
		} elseif ($data['type'] == 'manufacturer_id') {
			$this->cache->delete('manufacturer');
		} elseif ($data['type'] == 'information_id') {
			$this->cache->delete('information');
		}
	}

	public function deleteSeoUrl($url_alias_id) {
		$query = $this->db->query("SELECT query FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int)$url_alias_id . "'");

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int)$url_alias_id. "'");

		if ($query->num_rows) {
			$parts = explode('=', $query->row['query']);

			if ($parts[0] == 'product_id') {
				$this->cache->delete('product');
			} elseif ($parts[0] == 'category_id') {
				$this->cache->delete('category');
			} elseif ($parts[0] == 'manufacturer_id') {
				$this->cache->delete('manufacturer');
			} elseif ($parts[0] == 'information_id') {
				$this->cache->delete('information');
			}
		}
	}

	public function getSeoUrl($url_alias_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int)$url_alias_id . "'");

		return $query->row;
	}

	public function getSeoUrls($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "url_alias u";

		$where = array();

		if (!empty($data['filter_keyword'])) {
			$where[] = "u.keyword LIKE '%" . $this->db->escape($data['filter_keyword']) . "%'";
		}

		if (!empty($data['filter_type'])) {
			$where[] = "u.query LIKE '" . $this->db->escape($data['filter_type']) . "=%'";
		}

		if (!empty($where)) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$sql .= " GROUP BY u.url_alias_id";

		$sort_data = array(
			'u.keyword'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY u.keyword";
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
		$sql = "SELECT COUNT(u.url_alias_id) AS total FROM " . DB_PREFIX . "url_alias u";

		$where = array();

		if (!empty($data['filter_keyword'])) {
			$where[] = "u.keyword LIKE '" . $this->db->escape($data['filter_keyword']) . "%'";
		}

		if (!empty($data['filter_route'])) {
			$where[] = "u.query LIKE '%=" . $this->db->escape($data['filter_route']) . "%'";
		}

		if (!empty($data['filter_type'])) {
			$where[] = "u.query LIKE '" . $this->db->escape($data['filter_type']) . "=%'";
		}

		if (!empty($where)) {
			$sql .= " WHERE " . implode(' AND ', $where);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>