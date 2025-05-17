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
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param string $port
	 */
	public function __construct(array $option = []) {
		if (isset($option['port'])) {
			$port = $option['port'];
		} else {
			$port = '5432';
		}

		try {
			$pg = @pg_connect('host=' . $option['hostname'] . ' port=' . $port . ' user=' . $option['username'] . ' password=' . $option['password'] . ' dbname=' . $option['database'] . ' options=\'--client_encoding=UTF8\' ');
		} catch (\Exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname);
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
	 * @param string $sql
	 *
	 * @return \stdClass
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
	 * @param string $value
	 *
	 * @return string
	 */
	public function escape(string $value): string {
		return pg_escape_string($this->db, $value);
	}

	/**
	 * Count Affected
	 *
	 * @return int
	 */
	public function countAffected(): int {
		return pg_affected_rows($this->db);
	}

	/**
	 * Get Last Id
	 *
	 * @return int
	 */
	public function getLastId(): int {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return $query->row['id'];
	}

	/**
	 * Is Connected
	 *
	 * @return bool
	 */
	public function isConnected(): bool {
		return pg_connection_status($this->db) == PGSQL_CONNECTION_OK;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 */
	public function __destruct() {
		if ($this->db) {
			pg_close($this->db);

			$this->db = null;
		}
	}
}
