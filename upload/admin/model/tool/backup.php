<?php
class ModelToolBackup extends Model {
	public function getTables() {
		$table_data = array();

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($query->rows as $result) {
			$table = reset($result);
			if ($table && utf8_substr($table, 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$table_data[] = $table;
			}
		}

		return $table_data;
	}


	public function getRecords($table, $start = 0, $limit = 100) {
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 10;
		}

		$query = $this->db->query("SELECT * FROM `" . $table . "` LIMIT " . (int)$start . "," . (int)$limit);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return array();
		}
	}

	public function getTotalRecords($table) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM `" . $table . "`");

		if ($query->num_rows) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
}
