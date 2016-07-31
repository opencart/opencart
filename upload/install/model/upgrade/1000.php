<?php
class ModelUpgrade1000 extends Model {
	public function upgrade() {
		// This is a generic upgrade script.
		// It makes mass changes to the DB by creating tables that are not in the current db, changes the charset and DB engine to the SQL schema.
		// The uprade script is not coherent because of the changes over time to the upgrades so im grouping the changes into different files
		// Future version should have a upgrade file name that matches the version number being changed to

		// Load the sql file
		$file = DIR_APPLICATION . 'opencart.sql';

		if (!file_exists($file)) {
			exit('Could not load sql file: ' . $file);
		}

		$string = '';

		$lines = file($file);

		$status = false;

		// Get only the create statements
		foreach($lines as $line) {
			// Set any prefix
			$line = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . DB_PREFIX, $line);

			$line = str_replace("CREATE TABLE IF NOT EXISTS `oc_", "CREATE TABLE `" . DB_PREFIX, $line);

			$line = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . DB_PREFIX, $line);

			// If line begins with create table we want to start recording
			if (substr($line, 0, 12) == 'CREATE TABLE') {
				$status = true;
			}

			if ($status) {
				$string .= $line;
			}

			// If line contains with ; we want to stop recording
			if (preg_match('/;/', $line)) {
				$status = false;
			}
		}

		$table_new_data = array();

		// Trim any spaces
		$string = trim($string);

		// Trim any ;
		$string = trim($string, ';');

		// Start reading each create statement
		$statements = explode(';', $string);

		foreach ($statements as $sql) {
			// Get all fields
			$field_data = array();

			preg_match_all('#`(\w[\w\d]*)`\s+((tinyint|smallint|mediumint|bigint|int|tinytext|text|mediumtext|longtext|tinyblob|blob|mediumblob|longblob|varchar|char|datetime|date|float|double|decimal|timestamp|time|year|enum|set|binary|varbinary)(\((.*)\))?){1}\s*(collate (\w+)\s*)?(unsigned\s*)?((NOT\s*NULL\s*)|(NULL\s*))?(auto_increment\s*)?(default \'([^\']*)\'\s*)?#i', $sql, $match);

			foreach(array_keys($match[0]) as $key) {
				$field_data[] = array(
					'name'          => trim($match[1][$key]),
					'type'          => strtoupper(trim($match[3][$key])),
					'size'          => str_replace(array('(', ')'), '', trim($match[4][$key])),
					'sizeext'       => trim($match[6][$key]),
					'collation'     => trim($match[7][$key]),
					'unsigned'      => trim($match[8][$key]),
					'notnull'       => trim($match[9][$key]),
					'autoincrement' => trim($match[12][$key]),
					'default'       => trim($match[14][$key])
				);
			}

			// Get primary keys
			$primary_data = array();

			preg_match('#primary\s*key\s*\([^)]+\)#i', $sql, $match);

			if (isset($match[0])) {
				preg_match_all('#`(\w[\w\d]*)`#', $match[0], $match);
			} else{
				$match = array();
			}

			if ($match) {
				foreach($match[1] as $primary) {
					$primary_data[] = $primary;
				}
			}

			// Get indexes
			$index_data = array();

			$indexes = array();

			preg_match_all('#key\s*`\w[\w\d]*`\s*\(.*\)#i', $sql, $match);

			foreach($match[0] as $key) {
				preg_match_all('#`(\w[\w\d]*)`#', $key, $match);

				$indexes[] = $match;
			}

			foreach($indexes as $index) {
				$key = '';

				foreach($index[1] as $field) {
					if ($key == '') {
						$key = $field;
					} else{
						$index_data[$key][] = $field;
					}
				}
			}

			// Table options
			$option_data = array();

			preg_match_all('#(\w+)=\'?(\w+\~?\w+)\'?#', $sql, $option);

			foreach(array_keys($option[0]) as $key) {
				$option_data[$option[1][$key]] = $option[2][$key];
			}

			// Get Table Name
			preg_match_all('#create\s*table\s*`(\w[\w\d]*)`#i', $sql, $table);

			if (isset($table[1][0])) {
				$table_new_data[] = array(
					'sql'     => $sql,
					'name'    => $table[1][0],
					'field'   => $field_data,
					'primary' => $primary_data,
					'index'   => $index_data,
					'option'  => $option_data
				);
			}
		}

		// Get all current tables, fields, type, size, etc..
		$table_old_data = array();

		$table_query = $this->db->query("SHOW TABLES FROM `" . DB_DATABASE . "`");

		foreach ($table_query->rows as $table) {
			if (utf8_substr($table['Tables_in_' . DB_DATABASE], 0, strlen(DB_PREFIX)) == DB_PREFIX) {
				$field_data = array();
				$extended_field_data = array();

				$field_query = $this->db->query("SHOW COLUMNS FROM `" . $table['Tables_in_' . DB_DATABASE] . "`");

				foreach ($field_query->rows as $field) {
					$field_data[] = $field['Field'];
					$extended_field_data[] = $field;
				}

				$table_old_data[$table['Tables_in_' . DB_DATABASE]]['field_list'] = $field_data;
				$table_old_data[$table['Tables_in_' . DB_DATABASE]]['extended_field_data'] = $extended_field_data;
			}
		}

		foreach ($table_new_data as $table) {
			// If table is not found create it
			if (!isset($table_old_data[$table['name']])) {
				$this->db->query($table['sql']);
			} else {
				// DB Engine
				if (isset($table['option']['ENGINE'])) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` ENGINE = `" . $table['option']['ENGINE'] . "`");
				}

				// Charset
				if (isset($table['option']['CHARSET']) && isset($table['option']['COLLATE'])) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` DEFAULT CHARACTER SET `" . $table['option']['CHARSET'] . "` COLLATE `" . $table['option']['COLLATE'] . "`");
				}

				// Loop through all tables and adjust based on opencart.sql file
				$i = 0;

				foreach ($table['field'] as $field) {

					// If field is not found create it
					if (!in_array($field['name'], $table_old_data[$table['name']]['field_list'])) {

						$status = true;
						foreach ($table_old_data[$table['name']]['extended_field_data'] as $oldfield) {
							if ($oldfield['Extra'] == 'auto_increment' && $field['autoincrement']) {
								$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $oldfield['Field'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);
								$status = false;
								break;
							}
						}

						if ($status) {
							$sql = "ALTER TABLE `" . $table['name'] . "` ADD `" . $field['name'] . "` " . $field['type'];
						}

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['unsigned']) {
							$sql .= " " . $field['unsigned'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default'] != '') {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}

						$this->db->query($sql);
					} else {
						// Remove auto increment from all fields
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['unsigned']) {
							$sql .= " " . $field['unsigned'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default'] != '') {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if (isset($table['field'][$i - 1])) {
							$sql .= " AFTER `" . $table['field'][$i - 1]['name'] . "`";
						} else {
							$sql .= " FIRST";
						}

						$this->db->query($sql);
					}

					$i++;
				}

				$status = false;

				// Drop primary keys and indexes.
				$query = $this->db->query("SHOW INDEXES FROM `" . $table['name'] . "`");

				$last_key_name = '';
				if ($query->num_rows) {
					foreach ($query->rows as $result) {
						if ($result['Key_name'] != 'PRIMARY' && $result['Key_name'] != $last_key_name) {
							$last_key_name = $result['Key_name'];
							$this->db->query("ALTER TABLE `" . $table['name'] . "` DROP INDEX `" . $result['Key_name'] . "`");
						} else {
							$status = true;
						}
					}
				}

				if ($status) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` DROP PRIMARY KEY");
				}

				// Add a new primary key.
				$primary_data = array();

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				if ($primary_data) {
					$this->db->query("ALTER TABLE `" . $table['name'] . "` ADD PRIMARY KEY(" . implode(',', $primary_data) . ")");
				}

				// Add the new indexes
				foreach ($table['index'] as $name => $index) {
					$index_data = array();

					foreach ($index as $key) {
						$index_data[] = '`' . $key . '`';
					}

					if ($index_data) {
						$this->db->query("ALTER TABLE `" . $table['name'] . "` ADD INDEX `$name` (" . implode(',', $index_data) . ")");
					}
				}

				// Add auto increment to primary keys again
				foreach ($table['field'] as $field) {
					if ($field['autoincrement']) {
						$sql = "ALTER TABLE `" . $table['name'] . "` CHANGE `" . $field['name'] . "` `" . $field['name'] . "` " . strtoupper($field['type']);

						if ($field['size']) {
							$sql .= "(" . $field['size'] . ")";
						}

						if ($field['collation']) {
							$sql .= " " . $field['collation'];
						}

						if ($field['unsigned']) {
							$sql .= " " . $field['unsigned'];
						}

						if ($field['notnull']) {
							$sql .= " " . $field['notnull'];
						}

						if ($field['default'] != '') {
							$sql .= " DEFAULT '" . $field['default'] . "'";
						}

						if ($field['autoincrement']) {
							$sql .= " AUTO_INCREMENT";
						}

						$this->db->query($sql);
					}
				}
			}
		}
	}
}