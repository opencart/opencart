<?php
namespace Opencart\Install\Model\Install;
/**
 * Class Install
 *
 * @example $install_model = $this->model_install_install;
 *
 * Can be called from $this->load->model('install/install');
 *
 * @package Opencart\Install\Model\Install
 */
class Install extends \Opencart\System\Engine\Model {
	/**
	 * Database
	 *
	 * @param array<string, mixed> $data
	 *
	 * @throws \Exception
	 *
	 * @return void
	 */
	public function database(array $data): void {
		// Structure
		$this->load->helper('db_schema');

		$tables = oc_db_schema();

		// Clear any old db foreign key constraints
		/*
		foreach ($tables as $table) {
			$foreign_query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '" . html_entity_decode($data['db_database'], ENT_QUOTES, 'UTF-8') . "' AND TABLE_NAME = '" . $data['db_prefix'] . $table['name'] . "' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

			foreach ($foreign_query->rows as $foreign) {
				$this->db->query("ALTER TABLE `" . $data['db_prefix'] . $table['name'] . "` DROP FOREIGN KEY `" . $foreign['CONSTRAINT_NAME'] . "`");
			}
		}
		*/

		// CLear old DB
		foreach ($tables as $table) {
			$this->db->query("DROP TABLE IF EXISTS `" . $data['db_prefix'] . $table['name'] . "`");
		}

		// Need to sort the creation of tables on foreign keys
		foreach ($tables as $table) {
			$sql = "CREATE TABLE `" . $data['db_prefix'] . $table['name'] . "` (" . "\n";

			foreach ($table['field'] as $field) {
				$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $this->db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
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
			$this->db->query($sql);
		}

		// Setup foreign keys
		/*
		foreach ($tables as $table) {
			if (isset($table['foreign'])) {
				foreach ($table['foreign'] as $foreign) {
					$this->db->query("ALTER TABLE `" . $data['db_prefix'] . $table['name'] . "` ADD FOREIGN KEY (`" . $foreign['key'] . "`) REFERENCES `" . $data['db_prefix'] . $foreign['table'] . "` (`" . $foreign['field'] . "`)");
				}
			}
		}
		*/
		// Data
		$lines = file(DIR_APPLICATION . 'opencart-' . $this->config->get('language_code') . '.sql', FILE_IGNORE_NEW_LINES);

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
					$this->db->query(str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql));

					$start = false;
				}
			}
		}

		$this->db->query("SET CHARACTER SET utf8mb4");

		$this->db->query("SET @@session.sql_mode = ''");

		$this->db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE `user_id` = '1'");
		$this->db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET `user_id` = '1', `user_group_id` = '1', `username` = '" . $this->db->escape($data['username']) . "', `password` = '" . $this->db->escape(password_hash(html_entity_decode($data['password'], ENT_QUOTES, 'UTF-8'), PASSWORD_DEFAULT)) . "', `firstname` = 'John', `lastname` = 'Doe', `email` = '" . $this->db->escape($data['email']) . "', `status` = '1', `date_added` = NOW()");

		$this->db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_language_catalog', `value` = '" . $this->db->escape($this->config->get('language_code')) . "' WHERE `key` = 'config_language_catalog'");
		$this->db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_language_admin', `value` = '" . $this->db->escape($this->config->get('language_code')) . "' WHERE `key` = 'config_language_admin'");

		$this->db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_email', `value` = '" . $this->db->escape($data['email']) . "' WHERE `key` = 'config_email'");

		$this->db->query("INSERT INTO `" . $data['db_prefix'] . "api` SET `username` = 'Default', `key` = '" . $this->db->escape(oc_token(256)) . "', `status` = '1', `date_added` = NOW(), `date_modified` = NOW()");

		$api_id = $this->db->getLastId();

		$this->db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_api_id'");
		$this->db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `code` = 'config', `key` = 'config_api_id', `value` = '" . (int)$api_id . "'");

		// Set the current years prefix
		$this->db->query("UPDATE `" . $data['db_prefix'] . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
	}
}
