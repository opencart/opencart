<?php
namespace Opencart\System\Library\DB;
/**
 * Class PgSQL
 *
 * @package Opencart\System\Library\DB
 */
class PgSQL {
	/**
	 * @var \PgSql\Connection|null
	 */
	private ?\PgSql\Connection $connection = null;
	/**
	 * @var int
	 */
	private int $affected = 0;

	/**
	 * Constructor
	 *
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param string $port
	 */
	public function __construct(string $hostname, string $username, string $password, string $database, string $port = '') {
		if (!$port) {
			$port = '5432';
		}

		$pg = @pg_connect('host=' . $hostname . ' port=' . $port . ' user=' . $username . ' password=' . $password . ' dbname=' . $database . ' options=\'--client_encoding=UTF8\' ');

		if (!$pg) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}

		$this->connection = $pg;

		pg_query($this->connection, "SET CLIENT_ENCODING TO 'UTF8'");

		// Sync PHP and DB time zones
		pg_query($this->connection, "SET TIMEZONE = '" . $this->escape(date('P')) . "'");
	}

	/**
	 * Query
	 *
	 * @param string $sql
	 *
	 * @return \stdClass
	 */
	public function query(string $sql): \stdClass {
		$resource = pg_query($this->connection, $sql);

		if ($resource === false) {
			throw new \Exception('Error: ' . pg_last_error($this->connection) . '<br/>' . $sql);
		}

		$this->affected = (int)pg_affected_rows($resource);

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
		return pg_escape_string($this->connection, $value);
	}

	/**
	 * Count Affected
	 *
	 * @return int
	 */
	public function countAffected(): int {
		return $this->affected;
	}

	/**
	 * Get Last Id
	 *
	 * @return int
	 */
	public function getLastId(): int {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return (int)($query->row['id'] ?? 0);
	}

	/**
	 * Is Connected
	 *
	 * @return bool
	 */
	public function isConnected(): bool {
		return $this->connection && pg_connection_status($this->connection) === PGSQL_CONNECTION_OK;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 */
	public function __destruct() {
		if ($this->connection) {
			pg_close($this->connection);

			$this->connection = null;
		}
	}
}
