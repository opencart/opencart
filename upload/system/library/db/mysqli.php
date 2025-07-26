<?php
namespace Opencart\System\Library\DB;
/**
 * Class MySQLi
 *
 * @package Opencart\System\Library\DB
 */
class MySQLi {
	/**
	 * @var ?\mysqli
	 */
	private ?\mysqli $db;

	/**
	 * Constructor
	 *
	 * @param array<string, mixed> $option Database connection options array with keys:
	 *                                     - 'engine' (string, required): Database engine type
	 *                                     - 'hostname' (string, required): Database server hostname
	 *                                     - 'username' (string, required): Database username
	 *                                     - 'password' (string, optional): Database password
	 *                                     - 'database' (string, required): Database name
	 *                                     - 'port' (int, required): Database port
	 *                                     - 'ssl_key' (string, optional): SSL private key
	 *                                     - 'ssl_cert' (string, optional): SSL certificate
	 *                                     - 'ssl_ca' (string, optional): SSL certificate authority
	 *
	 * @throws \Exception If required parameters missing or database connection fails
	 *
	 * @example
	 *
	 * $mysqli = new MySQLi([
	 *     'engine'   => 'mysqli',
	 *     'hostname' => 'localhost',
	 *     'username' => 'opencart',
	 *     'password' => 'password',
	 *     'database' => 'opencart_db',
	 *     'port'     => 3306
	 * ]);
	 */
	public function __construct(array $option = []) {
		$required = [
			'engine',
			'hostname',
			'username',
			'database',
			'port'
		];

		foreach ($required as $key) {
			if (empty($option[$key])) {
				throw new \Exception('Error: Database ' . $key . ' required!');
			}
		}

		// MSQL SSL connection
		$temp_ssl_key_file = '';

		if (!empty($option['ssl_key'])) {
			$temp_ssl_key_file = tempnam(sys_get_temp_dir(), 'mysqli_key_');

			$handle = fopen($temp_ssl_key_file, 'w');

			fwrite($handle, (string)$option['ssl_key']);

			fclose($handle);
		}

		$temp_ssl_cert_file = '';

		if (!empty($option['ssl_cert'])) {
			$temp_ssl_cert_file = tempnam(sys_get_temp_dir(), 'mysqli_cert_');

			$handle = fopen($temp_ssl_cert_file, 'w');

			fwrite($handle, (string)$option['ssl_cert']);

			fclose($handle);
		}

		$temp_ssl_ca_file = '';

		if (!empty($option['ssl_ca'])) {
			$temp_ssl_ca_file = tempnam(sys_get_temp_dir(), 'mysqli_ca_');

			$handle = fopen($temp_ssl_ca_file, 'w');

			fwrite($handle, '-----BEGIN CERTIFICATE-----' . PHP_EOL . (string)$option['ssl_ca'] . PHP_EOL . '-----END CERTIFICATE-----');

			fclose($handle);
		}

		$this->db = new \mysqli();

		// Check PHP version to use appropriate method
		mysqli_report(flags: MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

		if ($temp_ssl_key_file || $temp_ssl_cert_file || $temp_ssl_ca_file) {
			$this->db->ssl_set($temp_ssl_key_file, $temp_ssl_cert_file, $temp_ssl_ca_file, null, null);

			$ssl = MYSQLI_CLIENT_SSL;
		} else {
			$ssl = 0;
		}

		if (@$this->db->real_connect($option['hostname'], $option['username'], $option['password'], $option['database'], $option['port'], null, $ssl)) {
			$this->db->set_charset('utf8mb4');

			$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
			$this->query("SET FOREIGN_KEY_CHECKS = 0");

			// Sync PHP and DB time zones
			$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
		} else {
			throw new \Exception('Error: Could not connect to the database please make sure the database server, username and password is correct!');
		}
	}

	/**
	 * Query
	 *
	 * Execute SQL query and return result object or boolean
	 *
	 * @param string $sql SQL query to execute
	 *
	 * @return \stdClass|bool Query result object with row, rows, num_rows properties for SELECT queries, true for other queries
	 *
	 * @throws \Exception If query execution fails
	 */
	public function query(string $sql) {
		try {
			$query = $this->db->query($sql);

			if ($query instanceof \mysqli_result) {
				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = (int)$query->num_rows;
				$result->row = $data[0] ?? [];
				$result->rows = $data;

				$query->close();

				unset($data);

				return $result;

				return new class($query) {
					private $query;
					private $num_rows = 0;
					private $row = [];
					private $rows = [];

					public function __construct(\mysqli_result $result) {
						$this->query = $result;

						$this->num_rows = mysqli_num_rows($result);
					}

					public function fetch() {
						return $this->query->fetch_assoc();
					}

					public function fetchAll() {
						return $this->query->fetch_all();
					}

					public function __destruct() {
						$this->query->free();
					}
				};
			} else {
				return true;
			}
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: ' . $this->db->error . '<br/>Error No: ' . $this->db->errno . '<br/>' . $sql);
		}
	}

	/**
	 * Escape
	 *
	 * Escape string value for safe SQL usage
	 *
	 * @param string $value String value to escape
	 *
	 * @return string Escaped string value safe for SQL queries
	 */
	public function escape(string $value): string {
		return $this->db->real_escape_string($value);
	}

	/**
	 * Count Affected
	 *
	 * Get number of rows affected by the last INSERT, UPDATE, or DELETE query
	 *
	 * @return int Number of affected rows
	 */
	public function countAffected(): int {
		return $this->db->affected_rows;
	}

	/**
	 * Get Last Id
	 *
	 * Get the auto-increment ID generated by the last INSERT query
	 *
	 * @return int Last inserted auto-increment ID
	 */
	public function getLastId(): int {
		return $this->db->insert_id;
	}

	/**
	 * Is Connected
	 *
	 * Check if database connection is active and valid
	 *
	 * @return bool True if connected, false otherwise
	 */
	public function isConnected(): bool {
		return $this->db !== null;
	}

	/**
	 * Destructor
	 *
	 * Closes the database connection and cleans up SSL temporary files when object is destroyed
	 *
	 * @return void
	 */
	public function __destruct() {
		if ($this->db) {
			$this->db->close();

			$this->db = null;
		}
	}
}
