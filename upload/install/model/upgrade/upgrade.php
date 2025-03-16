<?php
namespace Opencart\Install\Model\Upgrade;
/**
 * Class Install
 *
 * @example $install_model = $this->model_install_install;
 *
 * Can be called from $this->load->model('install/install');
 *
 * @package Opencart\Install\Model\Install
 */
class Upgrade extends \Opencart\System\Engine\Model {
	public function addRecord($table, $data) {
		$implode = [];

		foreach ($data as $key => $value) {
			switch (gettype($value)) {
				case 'boolean';
					$implode[] = "`" . $this->db->escape((string)$key) . "` = '" . (boolean)$value . "'";
					break;
				case 'integer';
					$implode[] = "`" . $this->db->escape((string)$key) . "` = '" . (int)$value . "'";
					break;
				case 'double';
					$implode[] = "`" . $this->db->escape((string)$key). "` = '" . (float)$value . "'";
					break;
				case 'string';
					$implode[] = "`" . $this->db->escape((string)$key) . "` = '" . $this->db->escape((string)$value) . "'";
					break;
				case 'array';
					$implode[] = "`" . $this->db->escape((string)$key) . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
				case 'object';
					$implode[] = "`" . $this->db->escape((string)$key) . "` = '" . $this->db->escape((array)json_encode($value)) . "'";
					break;
			}
		}

		$this->db->query("INSERT INTO `" . DB_PREFIX . $table . "` SET " . implode(", ", $implode));
	}

	public function getRecords(string $table) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . $table . "'");

		return $query->rows;
	}

	public function hasTable(string $table): bool {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "'");

		return (bool)$query->num_rows;
	}

	public function hasField($table, $field): bool {
		$query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table . "' AND COLUMN_NAME = '" . $field . "'");

		return (bool)$query->num_rows;
	}

	public function dropTable($table): void {
		if ($this->hasTable($table)) {
			$this->db->query("DROP TABLE `" . DB_PREFIX . $table . "`");
		}
	}

	public function dropField($table, $field): void {
		if ($this->hasTable($table, $field)) {
			$this->db->query("ALTER TABLE `" . DB_PREFIX . $table . "` DROP `" . $field . "`");
		}
	}

}
