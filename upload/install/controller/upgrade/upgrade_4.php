<?php
namespace Opencart\Install\Controller\Upgrade;
/**
 * Class Upgrade4
 *
 * Modify the DB to match the new DB schema
 *
 * @package Opencart\Install\Controller\Upgrade
 */
class Upgrade4 extends \Opencart\System\Engine\Controller {
	/**
	 * Index
	 *
	 * @return void
	 */
	public function index(): void {
		$this->load->language('upgrade/upgrade');

		$json = [];

		// It makes mass changes to the DB by creating tables that are not in the current db, changes the charset and DB engine to the SQL schema.
		try {
			// Structure
			$this->load->helper('db_schema');

			$tables = oc_db_schema();

			// Clear any old db foreign key constraints
			/*
			foreach ($tables as $table) {
				$foreign_query = $this->db->query("SELECT * FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "' AND CONSTRAINT_TYPE = 'FOREIGN KEY'");

				foreach ($foreign_query->rows as $foreign) {
					$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` DROP FOREIGN KEY `" . $foreign['CONSTRAINT_NAME'] . "`");
				}
			}
			*/

			foreach ($tables as $table) {
				$table_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "'");

				if (!$table_query->num_rows) {
					$sql = "CREATE TABLE `" . DB_PREFIX . $table['name'] . "` (" . "\n";

					foreach ($table['field'] as $field) {
						$sql .= "  `" . $field['name'] . "` " . $field['type'] . (!empty($field['not_null']) ? " NOT NULL" : "") . (isset($field['default']) ? " DEFAULT '" . $this->db->escape($field['default']) . "'" : "") . (!empty($field['auto_increment']) ? " AUTO_INCREMENT" : "") . ",\n";
					}

					if (isset($table['primary'])) {
						$primary_data = [];

						foreach ($table['primary'] as $primary) {
							$primary_data[] = "`" . $primary . "`";
						}

						$sql .= " PRIMARY KEY (" . implode(",", $primary_data) . "),\n";
					}

					if (isset($table['index'])) {
						foreach ($table['index'] as $index) {
							$index_data = [];

							foreach ($index['key'] as $key) {
								$index_data[] = "`" . $key . "`";
							}

							$sql .= " KEY `" . $index['name'] . "` (" . implode(",", $index_data) . "),\n";
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

					$keys = [];

					// Remove all primary keys and indexes
					$query = $this->db->query("SHOW INDEXES FROM `" . DB_PREFIX . $table['name'] . "`");

					foreach ($query->rows as $result) {
						if ($result['Key_name'] == 'PRIMARY') {
							// We need to remove the AUTO_INCREMENT
							$field_query = $this->db->query("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = '" . DB_DATABASE . "' AND TABLE_NAME = '" . DB_PREFIX . $table['name'] . "' AND COLUMN_NAME = '" . $result['Column_name'] . "'");

							$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` MODIFY `" . $result['Column_name'] . "` " . $field_query->row['COLUMN_TYPE'] . " NOT NULL");
						}

						if (!in_array($result['Key_name'], $keys)) {
							// Remove indexes below
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
						$primary_data = [];

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
							$index_data = [];

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

			/*
			// Setup foreign keys
			foreach ($tables as $table) {
				if (isset($table['foreign'])) {
					foreach ($table['foreign'] as $foreign) {
						//$this->db->query("ALTER TABLE `" . DB_PREFIX . $table['name'] . "` ADD FOREIGN KEY (`" . $foreign['key'] . "`) REFERENCES `" . DB_PREFIX . $foreign['table'] . "` (`" . $foreign['field'] . "`)");
					}
				}
			}
			*/
		} catch (\ErrorException $exception) {
			$json['error'] = sprintf($this->language->get('error_exception'), $exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
		}

		if (!$json) {
			$json['text'] = sprintf($this->language->get('text_patch'), 4, count(glob(DIR_APPLICATION . 'controller/upgrade/upgrade_*.php')));

			$url = '';

			if (isset($this->request->get['version'])) {
				$url .= '&version=' . $this->request->get['version'];
			}

			if (isset($this->request->get['admin'])) {
				$url .= '&admin=' . $this->request->get['admin'];
			}

			$json['next'] = $this->url->link('upgrade/upgrade_5', $url, true);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
