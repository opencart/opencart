<?php
namespace Opencart\Install\Model\Install;
class Install extends \Opencart\System\Engine\Model {
	public function database(array $data): void {
		$db = new \Opencart\System\Library\DB($data['db_driver'], html_entity_decode($data['db_hostname'], ENT_QUOTES, 'UTF-8'), html_entity_decode($data['db_username'], ENT_QUOTES, 'UTF-8'), html_entity_decode($data['db_password'], ENT_QUOTES, 'UTF-8'), html_entity_decode($data['db_database'], ENT_QUOTES, 'UTF-8'), $data['db_port']);

		// Structure
		$this->load->helper('db_schema');

		$tables = oc_db_schema();

		// Clear any old db foreign key constraints
		/*
		foreach ($tables as $table) {
			$foreign_query = $db->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '" . html_entity_decode($data['db_database'], ENT_QUOTES, 'UTF-8') . "' AND TABLE_NAME = '" . $data['db_prefix'] . $table['name'] . "' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

			foreach ($foreign_query->rows as $foreign) {
				$db->query("ALTER TABLE `" . $data['db_prefix'] . $table['name'] . "` DROP FOREIGN KEY `" . $foreign['CONSTRAINT_NAME'] . "`");
			}
		}
		*/

		// CLear old DB
		foreach ($tables as $table) {
			$db->query("DROP TABLE IF EXISTS `" . $data['db_prefix'] . $table['name'] . "`");
		}

		// Need to sort the creation of tables on foreign keys
		foreach ($tables as $table) {
			$sql = "CREATE TABLE `" . $data['db_prefix'] . $table['name'] . "` (" . "\n";

			foreach ($table['field'] as $field) {
				$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
			}

			if (isset($table['primary'])) {
				$primary_data = [];

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				$sql .= "  PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
			}

			if (isset($table['index'])) {
				foreach ($table['index'] as $index) {
					$index_data = [];

					foreach ($index['key'] as $key) {
						$index_data[] = "`" . $key . "`";
					}

					$sql .= "  KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
				}
			}

			$sql = rtrim($sql, ",\n") . "\n";
			$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " ROW_FORMAT=DYNAMIC COLLATE=" . $table['collate'] . ";\n";

			// Add table into another array so that it can be sorted to avoid foreign keys from being incorrectly formed.
			$db->query($sql);
		}

		// Setup foreign keys
		/*
		foreach ($tables as $table) {
			if (isset($table['foreign'])) {
				foreach ($table['foreign'] as $foreign) {
					$db->query("ALTER TABLE `" . $data['db_prefix'] . $table['name'] . "` ADD FOREIGN KEY (`" . $foreign['key'] . "`) REFERENCES `" . $data['db_prefix'] . $foreign['table'] . "` (`" . $foreign['field'] . "`)");
				}
			}
		}
		*/
		// Data
		$lines = file(DIR_APPLICATION . 'opencart.sql', FILE_IGNORE_NEW_LINES);

		if ($lines) {
			$sql = '';

			$start = false;

			foreach ($lines as $line) {
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

		$db->query("SET CHARACTER SET utf8mb4");
		
		$db->query("SET @@session.sql_mode = ''");

		$db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE `user_id` = '1'");
		$db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET `user_id` = '1', `user_group_id` = '1', `username` = '" . $db->escape($data['username']) . "', `password` = '" . $db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `firstname` = 'John', `lastname` = 'Doe', `email` = '" . $db->escape($data['email']) . "', `status` = '1', `date_added` = NOW()");

		$db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', `value` = '" . $db->escape($data['email']) . "' WHERE `key` = 'config_email'");

		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_encryption', `value` = '" . $db->escape(oc_token(512)) . "'");

		$db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET `username` = 'Default', `key` = '" . $db->escape(oc_token(256)) . "', `status` = '1', `date_added` = NOW(), `date_modified` = NOW()");

		$api_id = $db->getLastId();

		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', `value` = '" . (int)$api_id . "'");

		// set the current years prefix
		$db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
	}
}
