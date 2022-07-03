<?php
namespace Opencart\System\Library\DB;
class PgSQL {
	private object $connection;

	public function __construct(string $hostname, string $username, string $password, string $database, string $port = '') {
		if (!$port) {
			$port = '5432';
		}

		try {
			$pg = @pg_connect('host=' . $hostname . ' port=' . $port .  ' user=' . $username . ' password='	. $password . ' dbname=' . $database . ' options=\'--client_encoding=UTF8\' ');
		} catch (\Exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}

		if ($pg) {
			$this->connection = $pg;
			pg_query($this->connection, "SET CLIENT_ENCODING TO 'UTF8'");
		}
	}

	public function query(string $sql): bool|object {
		$resource = pg_query($this->connection, $sql);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = [];

				while ($result = pg_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				pg_free_result($resource);

				$query = new \stdClass();
				$query->row = isset($data[0]) ? $data[0] : [];
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;
			} else {
				return true;
			}
		} else {
			throw new \Exception('Error: ' . pg_result_error($this->connection) . '<br/>' . $sql);
		}
	}

	public function escape(string $value): string  {
		return pg_escape_string($this->connection, $value);
	}

	public function countAffected(): int {
		return pg_affected_rows($this->connection);
	}

	public function isConnected(): bool {
		if (pg_connection_status($this->connection) == PGSQL_CONNECTION_OK) {
			return true;
		} else {
			return false;
		}
	}

	public function getLastId(): int {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return $query->row['id'];
	}

	public function __destruct() {
		if ($this->connection) {
			pg_close($this->connection);

			$this->connection = '';
		}
	}
}
