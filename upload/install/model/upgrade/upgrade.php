<?php
namespace Opencart\Install\Model\Upgrade;
/**
 * Class Install
 *
 * Can be called from $this->load->model('upgrade/upgrade');
 *
 * @package Opencart\Install\Model\Install
 *
 * @example
 *
 * $data = [];
 *
 * $this->load->model('upgrade/upgrade');
 *
 * $install_model = $this->model_upgrade_upgrade($table, $data);
 */
class Upgrade extends \Opencart\System\Engine\Model {
	/**
	 * Add Record
	 *
	 * @param $table
	 * @param $data
	 *
	 * @return int
	 */
	public function addRecord($table, $data): int {
		$implode = [];

		foreach ($data as $key => $value) {
			$key = $this->db->escape((string)$key);

			switch (gettype($value)) {
				case 'boolean':
					$implode[] = "`" . $key . "` = '" . (bool)$value . "'";
					break;
				case 'integer':
					$implode[] = "`" . $key . "` = '" . (int)$value . "'";
					break;
				case 'double':
					$implode[] = "`" . $key . "` = '" . (float)$value . "'";
					break;
				case 'string':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((string)$value) . "'";
					break;
				case 'array':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
				case 'object':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
			}
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . $table . "` SET " . implode(", ", $implode));

		return $this->db->getLastId();
	}

	/**
	 * Get Records
	 *
	 * @param string $table
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function getRecords(string $table): array {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . $table . "`");

		return $query->rows;
	}

	/**
	 * Has Table
	 *
	 * @param string $table
	 *
	 * @return int
	 */
	public function hasTable(string $table): int {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

		return $query->num_rows;
	}

	/**
	 * Has Field
	 *
	 * @param string $table
	 * @param string $field
	 *
	 * @return int
	 */
	public function hasField(string $table, string $field): int {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "' AND COLUMN_NAME = '" . $field . "'");

		return $query->num_rows;
	}

	/**
	 * Drop Table
	 *
	 * @param string $table
	 *
	 * @return void
	 */
	public function dropTable(string $table): void {
		if ($this->hasTable($table)) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
		}
	}

	/**
	 * Drop Field
	 *
	 * @param string $table
	 * @param string $field
	 *
	 * @return void
	 */
	public function dropField(string $table, string $field): void {
		if ($this->hasField($table, $field)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . $table . "` DROP `" . $field . "`");
		}
	}
}
