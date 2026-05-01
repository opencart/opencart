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
	 * Sanitize a table or column identifier for safe use in raw SQL.
	 *
	 * @param string $name
	 *
	 * @return string
	 */
	private function safeIdentifier(string $name): string {
		return preg_replace('/[^a-zA-Z0-9_]/', '', $name);
	}

	/**
	 * Add Record
	 *
	 * @param string               $table
	 * @param array<string, mixed> $data
	 *
	 * @return int
	 */
	public function addRecord(string $table, array $data): int {
		$table = $this->safeIdentifier($table);

		$implode = [];

		foreach ($data as $key => $value) {
			$key = $this->safeIdentifier((string)$key);

			switch (gettype($value)) {
				case 'boolean':
					$implode[] = "`" . $key . "` = '" . (int)$value . "'";
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
				case 'object':
					$implode[] = "`" . $key . "` = '" . $this->db->escape((string)json_encode($value)) . "'";
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
		$table = $this->safeIdentifier($table);

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
		$table = $this->safeIdentifier($table);

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $this->db->escape(DB_DATABASE) . "' AND TABLE_NAME = '" . $this->db->escape(DB_PREFIX . $table) . "'");

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
		$table = $this->safeIdentifier($table);
		$field = $this->safeIdentifier($field);

		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $this->db->escape(DB_DATABASE) . "' AND TABLE_NAME = '" . $this->db->escape(DB_PREFIX . $table) . "' AND COLUMN_NAME = '" . $this->db->escape($field) . "'");

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
		$table = $this->safeIdentifier($table);

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
		$table = $this->safeIdentifier($table);
		$field = $this->safeIdentifier($field);

		if ($this->hasField($table, $field)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . $table . "` DROP `" . $field . "`");
		}
	}
}
