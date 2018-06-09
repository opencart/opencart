<?php
class ModelInstallInstall extends Model {
	public function database($data) {
		$db = new DB($data['db_driver'], htmlspecialchars_decode($data['db_hostname']), htmlspecialchars_decode($data['db_username']), htmlspecialchars_decode($data['db_password']), htmlspecialchars_decode($data['db_database']), $data['db_port']);

		// Structure
		$this->load->helper('db_schema');

		$tables = db_schema();

		foreach ($tables as $table) {
			$table_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $data['db_database'] . "' AND TABLE_NAME = '" . $data['db_prefix'] . $table['name'] . "'");

			if ($table_query->num_rows) {
				$db->query("DROP TABLE `" . $data['db_prefix'] . $table['name'] . "`");
			}

			$sql = "CREATE TABLE `" . $data['db_prefix'] . $table['name'] . "` (" . "\n";

			foreach ($table['field'] as $field) {
				$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
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

			$db->query($sql);
		}

		// Data
		$lines = file(DIR_APPLICATION . 'opencart.sql', FILE_IGNORE_NEW_LINES);

		if ($lines) {
			$sql = '';

			$start = false;

			foreach($lines as $line) {
				if (substr($line, 0, 12) == 'INSERT INTO ') {
					$sql = '';

					$start = true;
				}

				if ($start) {
					$sql .= $line;
				}

				if (substr($line, -2) == ');') {
					$db->query(str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql));

					$start = false;
				}
			}
		}

		$db->query("SET CHARACTER SET utf8");

		$db->query("SET @@session.sql_mode = 'MYSQL40'");

		$db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', salt = '', password = '" . $db->escape(password_hash($data['password'], PASSWORD_DEFAULT)) . "', firstname = 'John', lastname = 'Doe', email = '" . $db->escape($data['email']) . "', status = '1', date_added = NOW()");
		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");

		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', value = '" . $db->escape(token(1024)) . "'");

		$db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET username = 'Default', `key` = '" . $db->escape(token(256)) . "', status = 1, date_added = NOW(), date_modified = NOW()");

		$api_id = $db->getLastId();

		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', value = '" . (int)$api_id . "'");

		// set the current years prefix
		$db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
	}
}