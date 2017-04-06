<?php
class ModelToolUpload extends Model {
	public function addUpload($name, $filename) {
		$code = sha1(uniqid(mt_rand(), true));

		$this->db->query("INSERT INTO `" . DB_PREFIX . "upload` SET `name` = '" . $this->db->escape($name) . "', `filename` = '" . $this->db->escape($filename) . "', `code` = '" . $this->db->escape($code) . "', `date_added` = NOW()");

		return $code;
	}
		
	public function deleteUpload($upload_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "upload WHERE upload_id = '" . (int)$upload_id . "'");
	}

	public function getUpload($upload_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "upload` WHERE upload_id = '" . (int)$upload_id . "'");

		return $query->row;
	}

	public function getUploadByCode($code) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "upload WHERE code = '" . $this->db->escape($code) . "'");

		return $query->row;
	}

	public function getUploads($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "upload";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_filename'])) {
			$implode[] = "filename LIKE '" . $this->db->escape($data['filter_filename']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "%'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$sort_data = array(
			'name',
			'filename',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY date_added";
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

	public function getTotalUploads() {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "upload";

		$implode = array();

		if (!empty($data['filter_name'])) {
			$implode[] = "name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		if (!empty($data['filter_filename'])) {
			$implode[] = "filename LIKE '" . $this->db->escape($data['filter_filename']) . "%'";
		}

		if (!empty($data['filter_date_added'])) {
			$implode[] = "date_added = '" . $this->db->escape($data['filter_date_added']) . "'";
		}

		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}