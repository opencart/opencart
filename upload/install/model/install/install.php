<?php
namespace Opencart\Install\Model\Install;
use Opencart\System\Library\DB;

/**
 * Class Install
 *
 * @package Opencart\Install\Model\Install
 */
class Install extends \Opencart\System\Engine\Model {
	/**
	 * @param DB $db
	 * @param array $tables
	 * @param array $triggers
	 * @param string $db_prefix
	 * @return void
	 */
	public function createDatabaseSchema(\Opencart\System\Library\DB $db, array $tables, array $triggers, string $db_prefix): void{
		foreach ($tables as $table) {
			$db->query("DROP TABLE IF EXISTS `" . $db_prefix . $table['name'] . "`");

			$sql = "CREATE TABLE `" . $db_prefix . $table['name'] . "` (" . "\n";

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
			$sql .= ") ENGINE=" . $table['engine'] . " CHARSET=" . $table['charset'] . " COLLATE=" . $table['collate'] . ";\n";

			$db->query($sql);
		}

		foreach ($triggers as $trigger){
			$table = $trigger['table'];
			foreach(['after', 'before'] as $trigger_time){
				foreach(['update', 'delete', 'insert'] as $trigger_event){
					if(isset($trigger[$trigger_time][$trigger_event]) && is_string($trigger[$trigger_time][$trigger_event])){
						$trigger_content = $trigger[$trigger_time][$trigger_event];

						$trigger_name = $db_prefix . $table . '_' .  $trigger_time . '_' .  $trigger_event;

						$db->query('DROP TRIGGER IF EXISTS ' . $trigger_name);

						$trigger_sql = '
							CREATE TRIGGER ' . $trigger_name . ' ' . oc_strtoupper($trigger_time) . ' ' . oc_strtoupper($trigger_event) . ' ON ' . $db_prefix . $table . '
							   FOR EACH ROW
							   BEGIN
								   ' . $trigger_content . '
							   END;
						';

						$db->query($trigger_sql);
					}
				}
			}
		}
	}

	/**
	 * @param DB $db
	 * @param string $data_sql_file
	 * @param string $db_prefix
	 * @param string $admin_username
	 * @param string $admin_password
	 * @param string $admin_email
	 * @return void
	 */
	public function setupDatabaseData(\Opencart\System\Library\DB $db, string $data_sql_file, string $db_prefix, string $admin_username, string $admin_password, string $admin_email){
		$lines = file($data_sql_file, FILE_IGNORE_NEW_LINES);

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
					$db->query(str_replace("INSERT INTO `oc_", "INSERT INTO `" . $db_prefix, $sql));

					$start = false;
				}
			}

			$db->query("SET CHARACTER SET utf8");

			$db->query("SET @@session.sql_mode = ''");

			$db->query("DELETE FROM `" . $db_prefix . "user` WHERE `user_id` = '1'");

			$db->query("INSERT INTO `" . $db_prefix . "user` SET `user_id` = '1', `user_group_id` = '1', `username` = '" . $db->escape($admin_username) . "', `password` = '" . $db->escape($admin_password) . "', `firstname` = 'John', `lastname` = 'Doe', `email` = '" . $db->escape($admin_email) . "', `status` = '1', `date_added` = NOW()");

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_email'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_email', `value` = '" . $db->escape($admin_email) . "'");

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_encryption'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_encryption', `value` = '" . $db->escape(oc_token(1024)) . "'");

			$db->query("INSERT INTO `" . $db_prefix . "api` SET `username` = 'Default', `key` = '" . $db->escape(oc_token(256)) . "', `status` = 1, `date_added` = NOW(), `date_modified` = NOW()");

			$last_id = $db->getLastId();

			$db->query("DELETE FROM `" . $db_prefix . "setting` WHERE `key` = 'config_api_id'");
			$db->query("INSERT INTO `" . $db_prefix . "setting` SET `code` = 'config', `key` = 'config_api_id', `value` = '" . (int)$last_id . "'");

			// set the current years prefix
			$db->query("UPDATE `" . $db_prefix . "setting` SET `value` = 'INV-" . date('Y') . "-00' WHERE `key` = 'config_invoice_prefix'");
		}
	}

	/**
	 * @param DB $db
	 * @param array $tables
	 * @param array $triggers
	 * @param string $database_name
	 * @param string $db_prefix
	 * @return void
	 */
	public function upgradeDatabaseSchema(\Opencart\System\Library\DB $db, array $tables, array $triggers, string $database_name, string $db_prefix): void{
		$tables_to_insert = [];
		$tables_to_update = [];
		foreach ($tables as $table) {
			$table_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $database_name . "' AND TABLE_NAME = '" . $db_prefix . $table['name'] . "'");
			if($table_query->num_rows > 0){
				$tables_to_update[] = $table;
			}else{
				$tables_to_insert[] = $table;
			}
		}

		// Create tables that do not exist.
		$this->createDatabaseSchema($db, $tables_to_insert, $triggers, $db_prefix);

		// Update the existing tables if needed.
		foreach ($tables_to_update as $table) {
			for ($i = 0; $i < count($table['field']); $i++) {
				$sql = "ALTER TABLE `" . $db_prefix . $table['name'] . "`";

				$field_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $database_name . "' AND TABLE_NAME = '" . $db_prefix . $table['name'] . "' AND COLUMN_NAME = '" . $table['field'][$i]['name'] . "'");

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

				$db->query($sql);
			}

			$keys = [];

			// Remove all primary keys and indexes
			$query = $db->query("SHOW INDEXES FROM `" . $db_prefix . $table['name'] . "`");

			foreach ($query->rows as $result) {
				if ($result['Key_name'] == 'PRIMARY') {
					// We need to remove the AUTO_INCREMENT
					$field_query = $db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . $database_name . "' AND TABLE_NAME = '" . $db_prefix . $table['name'] . "' AND COLUMN_NAME = '" . $result['Column_name'] . "'");

					$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` MODIFY `" . $result['Column_name'] . "` " . $field_query->row['COLUMN_TYPE'] . " NOT NULL");
				}

				if (!in_array($result['Key_name'], $keys)) {
					// Remove indexes below
					$keys[] = $result['Key_name'];
				}
			}

			foreach ($keys as $key) {
				if ($result['Key_name'] == 'PRIMARY') {
					$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` DROP PRIMARY KEY");
				} else {
					$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` DROP INDEX `" . $key . "`");
				}
			}

			// Primary Key
			if (isset($table['primary'])) {
				$primary_data = [];

				foreach ($table['primary'] as $primary) {
					$primary_data[] = "`" . $primary . "`";
				}

				$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` ADD PRIMARY KEY(" . implode(",", $primary_data) . ")");
			}

			for ($i = 0; $i < count($table['field']); $i++) {
				if (isset($table['field'][$i]['auto_increment'])) {
					$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` MODIFY `" . $table['field'][$i]['name'] . "` " . $table['field'][$i]['type'] . " AUTO_INCREMENT");
				}
			}

			// Indexes
			if (isset($table['index'])) {
				foreach ($table['index'] as $index) {
					$index_data = [];

					foreach ($index['key'] as $key) {
						$index_data[] = "`" . $key . "`";
					}

					$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` ADD INDEX `" . $index['name'] . "` (" . implode(",", $index_data) . ")");
				}
			}

			// DB Engine
			if (isset($table['engine'])) {
				$db->query("ALTER TABLE `" . $db_prefix . $table['name'] . "` ENGINE = `" . $table['engine'] . "`");
			}

			// Charset
			if (isset($table['charset'])) {
				$sql = "ALTER TABLE `" . $db_prefix . $table['name'] . "` DEFAULT CHARACTER SET `" . $table['charset'] . "`";

				if (isset($table['collate'])) {
					$sql .= " COLLATE `" . $table['collate'] . "`";
				}

				$db->query($sql);

			}
		}
	}
}
