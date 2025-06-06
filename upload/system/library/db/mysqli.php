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
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param int    $port
	 * @param string $ssl_key
	 * @param string $ssl_cert
	 * @param string $ssl_ca
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
	 * @param string $sql
	 *
	 * @return mixed
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
	 * @param string $value
	 *
	 * @return string
	 */
	public function escape(string $value): string {
		return $this->db->real_escape_string($value);
	}

	/**
	 * Count Affected
	 *
	 * @return int
	 */
	public function countAffected(): int {
		return $this->db->affected_rows;
	}

	/**
	 * Get Last Id
	 *
	 * @return int
	 */
	public function getLastId(): int {
		return $this->db->insert_id;
	}

	/**
	 * Is Connected
	 *
	 * @return bool
	 */
	public function isConnected(): bool {
		return $this->db !== null;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 */
	public function __destruct() {
		if ($this->db) {
			$this->db->close();

			$this->db = null;
		}
	}
}
