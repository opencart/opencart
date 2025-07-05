<?php
namespace Opencart\System\Library\DB;
/**
 * Class PgSQL
 *
 * @package Opencart\System\Library\DB
 */
class PgSQL {
	/**
	 * @var mixed
	 */
	private $db;

	/**
	 * Constructor
	 *
	 * @param array<string, mixed> $option Database connection options array with keys:
	 *                                     - 'hostname' (string, required): Database server hostname
	 *                                     - 'username' (string, required): Database username
	 *                                     - 'password' (string, required): Database password
	 *                                     - 'database' (string, required): Database name
	 *                                     - 'port' (string, optional): Database port (default: '5432')
	 *
	 * @throws \Exception If database connection fails
	 *
	 * @example
	 * $pgsql = new PgSQL([
	 *     'hostname' => 'localhost',
	 *     'username' => 'postgres',
	 *     'password' => 'password',
	 *     'database' => 'opencart',
	 *     'port'     => '5432'
	 * ]);
	 */
	public function __construct(array $option = []) {
		$required = [
			'hostname',
			'username',
			'database'
		];

		foreach ($required as $key) {
			if (empty($option[$key])) {
				throw new \Exception('Error: Database ' . $key . ' required!');
			}
		}

		if (isset($option['port'])) {
			$port = $option['port'];
		} else {
			$port = '5432';
		}

		try {
			$pg = @pg_connect('host=' . $option['hostname'] . ' port=' . $port . ' user=' . $option['username'] . ' password=' . $option['password'] . ' dbname=' . $option['database'] . ' options=\'--client_encoding=UTF8\' ');
		} catch (\Exception $e) {
			throw new \Exception('Error: Could not connect to the database please make sure the database server, username and password is correct!');
		}

		if ($pg) {
			$this->db = $pg;
			pg_query($this->db, "SET CLIENT_ENCODING TO 'UTF8'");

			// Sync PHP and DB time zones
			pg_query($this->db, "SET TIMEZONE = '" . $this->escape(date('P')) . "'");
		}
	}

	/**
	 * Query
	 *
	 * Execute SQL query and return result object
	 *
	 * @param string $sql SQL query to execute
	 *
	 * @return \stdClass Query result with row, rows, and num_rows properties
	 *
	 * @throws \Exception If query execution fails
	 */
	public function query(string $sql): \stdClass {
		$resource = pg_query($this->db, $sql);

		if ($resource === false) {
			throw new \Exception('Error: ' . pg_last_error($this->db) . '<br/>' . $sql);
		}

		$data = [];

		while ($result = pg_fetch_assoc($resource)) {
			$data[] = $result;
		}

		pg_free_result($resource);

		$query = new \stdClass();
		$query->row = $data[0] ?? [];
		$query->rows = $data;
		$query->num_rows = count($data);

		return $query;
	}

	/**
	 * Escape
	 *
	 * Escape string value for safe SQL usage
	 *
	 * @param string $value String value to escape
	 *
	 * @return string Escaped string value
	 */
	public function escape(string $value): string {
		return pg_escape_string($this->db, $value);
	}

	/**
	 * Count Affected
	 *
	 * Get number of rows affected by the last query
	 *
	 * @return int Number of affected rows
	 */
	public function countAffected(): int {
		return pg_affected_rows($this->db);
	}

	/**
	 * Get Last Id
	 *
	 * Get the last inserted sequence value
	 *
	 * @return int Last inserted ID
	 *
	 * @throws \Exception If sequence value cannot be retrieved
	 */
	public function getLastId(): int {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return $query->row['id'];
	}

	/**
	 * Is Connected
	 *
	 * Check if database connection is active
	 *
	 * @return bool True if connected, false otherwise
	 */
	public function isConnected(): bool {
		return pg_connection_status($this->db) == PGSQL_CONNECTION_OK;
	}

	/**
	 * Destructor
	 *
	 * Closes the database connection when object is destroyed
	 *
	 * @return void
	 */
	public function __destruct() {
		if ($this->db) {
			pg_close($this->db);

			$this->db = null;
		}
	}
}
