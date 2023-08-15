<?php
namespace Opencart\Admin\Model\Tool;
/**
 * Class Backup
 *
 * @package Opencart\Admin\Model\Tool
 */
class Backup extends \Opencart\System\Engine\Model {
	/**
	 * @return array
	 */
	public function getTables(): array {
		$table_data = [];

		$query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($query->rows as $result) {
			if (isset($result['Tables_in_' . DB_DATABASE]) && substr($result['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$table_data[] = $result['Tables_in_' . DB_DATABASE];
			}
		}

		return $table_data;
	}

	/**
	 * @param string $table
	 * @param int    $start
	 * @param int    $limit
	 *
	 * @return array
	 */
	public function getRecords(string $table, int $start = 0, int $limit = 100): array {
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
			return [];
		}
	}

	/**
	 * @param string $table
	 *
	 * @return int
	 */
	public function getTotalRecords(string $table): int {
		$query = $this->db->query("SELECT COUNT(*) AS `total` FROM `" . $table . "`");

		if ($query->num_rows) {
			return (int)$query->row['total'];
		} else {
			return 0;
		}
	}
}