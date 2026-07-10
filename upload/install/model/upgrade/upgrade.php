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
	 * @param string               $table
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addRecord(string $table, array $data): int {
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
					$implode[] = "`" . $key . "` = '" . $this->db->escape(json_encode($value)) . "'";
					break;
				case 'object':
					$implode[] = "`" . $key . "` = '" . $this->db->escape(json_encode($value)) . "'";
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

	/**
	 * Get Field Type
	 *
	 * @param string $table
	 * @param string $field
	 *
	 * @return string
	 */
	public function getFieldType(string $table, string $field): string {
		$result = $this->db->query("DESCRIBE `" . DB_PREFIX . $table . "` `" . $field . "`;");

		return $result->row['Type'] ?? '';
	}

	/**
	 * Alter Field Type
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $type
	 * @param string $constrains
	 *
	 * @return void
	 */
	public function alterFieldType(string $table, string $field, string $type, string $constrains): void {
		if ($this->hasField($table, $field)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . $table . "` MODIFY COLUMN `" . $field . "` {$type} {$constrains};");
		}
	}

	/**
	 * Has Index
	 *
	 * @param string $table
	 * @param string $index_name
	 *
	 * @return int
	 */
	public function hasIndex(string $table, string $index_name): int {
		$query = $this->db->query("SELECT * FROM information_schema.statistics WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "' AND COLUMN_NAME = '" . $index_name . "';");

		return $query->num_rows;
	}

	/**
	 * Create Index
	 *
	 * @param string $table
	 * @param string $field
	 *
	 * @return void
	 */
	public function createIndex(string $table, string $field): void {
		$this->db->query("CREATE INDEX '" . $field . "' ON '" . DB_PREFIX . $table . "';");
	}
}
