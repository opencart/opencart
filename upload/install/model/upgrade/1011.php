<?php
class ModelUpgrade1011 extends Model {
	public function upgrade() {
		$file = DIR_APPLICATION . '../install/db_schema.php';

		include($file);

		foreach ($tables as $table) {
			$table_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "'");

			if (!$table_query->num_rows) {
				$sql = "CREATE TABLE `" . DB_PREFIX . $table['name'] . "` (" . "\n";

				foreach ($table['field'] as $field) {
					$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . addslashes($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
				}

				if (isset($table['primary'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
				}

				if (isset($table['index'])) {
					$index_data = array();

					foreach ($table['index'] as $index) {
						$key_data = array();

						foreach ($index['key'] as $key) {
							$key_data[] = "`" . $key . "`";
						}

						$sql .= "  KEY `" . $index['name'] . "` (" . implode(",", $key_data) . "),\n";
					}
				}

				$sql = rtrim($sql, ",\n") . "\n";
				$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";
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
					} else {
						$sql .= "";
					}

					if (isset($table['field'][$i]['default'])) {
						$sql .= " DEFAULT '" . $table['field'][$i]['default'] . "'";
					}

					if (isset($table['field'][$i]['auto_increment'])) {
						$sql .= " AUTO_INCREMENT";
					}

					if (!isset($table['field'][$i - 1])) {
						$sql .= " FIRST";
					} else {
						$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
					}

					$this->db->query($sql);
				}

				if (isset($table['primary'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP PRIMARY KEY, ADD PRIMARY KEY(" . implode(",", $primary_data) . ")");
				}

				if (isset($table['index'])) {
					$primary_data = array();

					foreach ($table['primary'] as $primary) {
						$primary_data[] = "`" . $primary . "`";
					}

					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP PRIMARY KEY, ADD PRIMARY KEY(" . implode(",", $primary_data) . ")");
				}
			}
		}
	}
}