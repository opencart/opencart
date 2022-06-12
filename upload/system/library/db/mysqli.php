<?php
namespace Opencart\System\Library\DB;
class MySQLi {
	private object $connection;

	public function __construct(string $hostname, string $username, string $password, string $database, string $port = '') {
		if (!$port) {
			$port = '3306';
		}

		try {
			$mysqli = @new \MySQLi($hostname, $username, $password, $database, $port);

			$this->connection = $mysqli;
			$this->connection->report_mode = MYSQLI_REPORT_ERROR;
			$this->connection->set_charset('utf8mb4');
			$this->connection->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}
	}

	public function query(string $sql): bool|object {
		try {
			$query = $this->connection->query($sql);

			if ($query instanceof \mysqli_result) {
				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = isset($data[0]) ? $data[0] : [];
				$result->rows = $data;

				$query->close();

				unset($data);

				return $result;
			} else {
				return true;
			}
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: ' . $this->connection->error  . '<br/>Error No: ' . $this->connection->errno . '<br/>' . $sql);
		}
	}

	public function escape(string $value): string {
		return $this->connection->real_escape_string($value);
	}
	
	public function countAffected(): int {
		return $this->connection->affected_rows;
	}

	public function getLastId(): int {
		return $this->connection->insert_id;
	}
	
	public function isConnected(): bool {
		if ($this->connection) {
			return $this->connection->ping();
		} else {
			return false;
		}
	}

	/**
	 * __destruct
	 *
	 * Closes the DB connection when this object is destroyed.
	 *
	 */
	public function __destruct() {
		if ($this->connection) {
			$this->connection->close();

			unset($this->connection);
		}
	}
}
