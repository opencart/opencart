<?php
namespace Opencart\Admin\Model\Tool;
/**
 * Class Upload
 *
 * Can be loaded using $this->load->model('tool/upgrade');
 *
 * @package Opencart\Admin\Model\Tool
 */
class Upgrade extends \Opencart\System\Engine\Model {
	public function backupDatabase(string $backupPath, string $tableListPath): bool {
		// Call OpenCart's backup model method directly
		$this->load->model('tool/backup');

		// Get all the table names
		$tables = [];
		$query = $this->db->query("SHOW TABLES");

		foreach ($query->rows as $result) {
			$tables[] = reset($result);
		}

		// Save the table names to a separate file
		file_put_contents($tableListPath, json_encode($tables));

		$output = '';

		// Get the records for the table (page logic here)
		foreach ($tables as $table) {
			$recordCount = $this->model_tool_backup->getTotalRecords($table);
			$totalPages = ceil($recordCount / 200);

			$ignore = [
				DB_PREFIX . 'user',
				DB_PREFIX . 'user_group'
			];

			for ($i = 1; $i <= $totalPages; $i++){
				if (in_array($table, $ignore)) {
					continue;
				}

				if ($i == 1) {
					$query = $this->showTableColumns($table);
					$output .= 'DROP TABLE IF EXISTS ' . $this->db->escape($table) . ";\n";
					$output .= $query->row['Create Table'] . ";\n";
				}

				$results = $this->model_tool_backup->getRecords($table, ($i - 1) * 200, 200);

				foreach ($results as $result) {
					$fields = '';
					foreach (array_keys($result) as $key) {
						$fields .= '`' . $key . '`, ';
					}

					$values = '';
					foreach (array_values($result) as $value) {
						if ($value !== null) {
							$value = str_replace(["\x00", "\x0a", "\x0d", "\x1a"], ['\0', '\n', '\r', '\Z'], $value);
							$value = str_replace(["\n", "\r", "\t"], ['\n', '\r', '\t'], $value);
							$value = str_replace('\\', '\\\\', $value);
							$value = str_replace('\'', '\\\'', $value);
							$value = str_replace('\\\n', '\n', $value);
							$value = str_replace('\\\r', '\r', $value);
							$value = str_replace('\\\t', '\t', $value);

							$values .= '\'' . $value . '\', ';
						} else {
							$values .= 'NULL, ';
						}
					}

					$output .= 'INSERT INTO `' . $table . '` (' . preg_replace('/, $/', '', $fields) . ') VALUES (' . preg_replace('/, $/', '', $values) . ');' . "\n";
				}
			}
		}

		// Write the output to the backup file
		$handle = fopen($backupPath, 'a');
		fwrite($handle, $output);
		fclose($handle);

		return true;
	}
	
	public function rollbackDatabase(string $sqlFile): bool {
		$i = 0;
		$position = 0;
		$queryBuffer = '';
		$isCreateTable = false;

		try {
			$handle = fopen($sqlFile, 'r');

			fseek($handle, $position, SEEK_SET);

			while (!feof($handle)) {
				$position = ftell($handle);

				$line = fgets($handle, 1000000);				

				if ($i > 0 && (substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user`' || substr($line, 0, strlen('TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) == 'TRUNCATE TABLE `' . DB_PREFIX . 'user_group`')) {
					fseek($handle, $position, SEEK_SET);

					break;
				}
					
				// Check if we are starting a CREATE TABLE query
				if (substr($line, 0, 12) == 'CREATE TABLE') {
					$isCreateTable = true;
					$queryBuffer = $line;
				} elseif ($isCreateTable) {
					$queryBuffer .= ' ' . trim($line); // Remove any new lines and spaces
				}

				if ($isCreateTable && substr(trim($line), -1) == ';') {
					$this->db->query($queryBuffer);
					$isCreateTable = false;
					$queryBuffer = '';
				}

				if ((substr($line, 0, 11) == 'INSERT INTO') && substr($line, -2) == ";\n") {
					$this->db->query(substr($line, 0, strlen($line) - 2));
				}

				if ((substr($line, 0, 20) == 'DROP TABLE IF EXISTS') && substr($line, -2) == ";\n") {
					$queryBuffer .= ' ' . trim($line);

					if (substr(trim($line), -1) == ';') {
						$this->db->query($queryBuffer);
						$queryBuffer = '';
					}
				}

				$i++;
					
				$position += strlen($line);
			}

			$position = ftell($handle);

			fclose($handle);

			return true;
		} catch (\Exception $e) {
			$this->db->query("ROLLBACK");
			return false;
		}
	}
	
	public function showTableColumns(string $table) {
		return $this->db->query("SHOW CREATE TABLE `" . $table . "`");
	}

	public function dropTables(string $tableListPath) {
		// Read the list of backed-up tables
		if (!file_exists($tableListPath)) {
			return false;
		}

		$backupTables = json_decode(file_get_contents($tableListPath), true);

		if (!is_array($backupTables)) {
			return false;
		}

		$currentTables = [];
		$query = $this->db->query("SHOW TABLES");

		foreach ($query->rows as $result) {
			$currentTables[] = reset($result);
		}

		$tablesToDrop = array_diff($currentTables, $backupTables);

		$this->db->query("START TRANSACTION");

		try {
			foreach ($tablesToDrop as $table) {
				$this->db->query("DROP TABLE IF EXISTS `" . $table . "`");
			}

			$this->db->query("COMMIT");

			return true;
		} catch (\Exception $e) {
			$this->db->query("ROLLBACK");
			return false;
		}
	}
}
