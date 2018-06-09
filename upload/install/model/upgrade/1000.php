<?php
class ModelUpgrade1000 extends Model {
	public function upgrade() {
		// This is a generic upgrade script.
		// It makes mass changes to the DB by creating tables that are not in the current db, changes the charset and DB engine to the SQL schema.
		// The upgrade script is not coherent because of the changes over time to the upgrades so im grouping the changes into different files
		// Future version should have a upgrade filename that matches the version number being changed to

		// Structure
		$this->load->helper('db_schema');

		$tables = db_schema();

		foreach ($tables as $table) {
			$table_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "'");

			if (!$table_query->num_rows) {
				$sql = "CREATE TABLE `" . DB_PREFIX . $table['name'] . "` (" . "\n";

				foreach ($table['field'] as $field) {
					$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $this->db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
				}

				if (isset($table['primary'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
				}

				if (isset($table['index'])) {
					foreach ($table['index'] as $index) {
						$index_data = array();

						foreach ($index['key'] as $key) {
							$index_data[] = "`" . $key . "`";
						}

						$sql .= "  KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
					}
				}

				$sql = rtrim($sql, ",\n") . "\n";
				$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";

				$this->db->query($sql);
			} else {
				for ($i = 0; $i < count($table['field']); $i++) {
					$sql = "ALTER TABLE `" . DB_PREFIX . $table['name'] . "`";

					$field_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "' AND COLUMN_NAME = '" . $table['field'][$i]['name'] . "'");

					if (!$field_query->num_rows) {
						$sql .= " ADD";
					} else {
						$sql .= " MODIFY";
					}

					$sql .= " `" . $table['field'][$i]['name'] . "` " . $table['field'][$i]['type'];

					if (!empty($table['field'][$i]['not_null'])) {
						$sql .= " NOT NULL";
					}

					if (isset($table['field'][$i]['default'])) {
						$sql .= " DEFAULT '" . $table['field'][$i]['default'] . "'";
					}

					if (!isset($table['field'][$i - 1])) {
						$sql .= " FIRST";
					} else {
						$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
					}

					$this->db->query($sql);
				}

				// Remove all primary keys and indexes
				$keys = array();

				$query = $this->db->query("SHOW INDEXES FROM `" . DB_PREFIX . $table['name'] . "`");

				foreach ($query->rows as $result) {
					if (!in_array($result['Key_name'], $keys)) {
						$keys[] = $result['Key_name'];
					}
				}

				foreach ($keys as $key) {
					if ($key == 'PRIMARY') {
						$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP PRIMARY KEY");
					} else {
						$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP INDEX `" . $key . "`");
					}
				}

				// Primary Key
				if (isset($table['primary'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` ADD PRIMARY KEY(" . implode(",", $primary_data) . ")");
				}

				for ($i = 0; $i < count($table['field']); $i++) {
					if (isset($table['field'][$i]['auto_increment'])) {
						$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` MODIFY `" . $table['field'][$i]['name'] . "` " . $table['field'][$i]['type'] . " AUTO_INCREMENT");
					}
				}

				// Indexes
				if (isset($table['index'])) {
					foreach ($table['index'] as $index) {
						$index_data = array();

						foreach ($index['key'] as $key) {
							$index_data[] = "`" . $key . "`";
						}

						$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` ADD INDEX `" . $index['name'] . "` (" . implode(",", $index_data) . ")");
					}
				}

				// DB Engine
				if (isset($table['engine'])) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` ENGINE = `" . $table['engine'] . "`");
				}

				// Charset
				if (isset($table['charset'])) {
					$sql = "ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DEFAULT CHARACTER SET `" . $table['charset'] . "`";

					if (isset($table['collate'])) {
						$sql .= " COLLATE `" . $table['collate'] . "`";
					}

					$this->db->query($sql);
				}
			}
		}
	}
}