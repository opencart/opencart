<?php
namespace Opencart\System\Library\DB;

use Opencart\System\Library\Database\Parameter\ArrayParameterType;
use Opencart\System\Library\Database\Query\QueryConnectionInterface;
use Opencart\System\Library\Database\Parameter\ParameterProcessor;
use Opencart\System\Library\Database\Parameter\ParameterType;

/**
 * Class MySQLi
 *
 * @package Opencart\System\Library\DB
 */
class MySQLi implements QueryConnectionInterface {
	/**
	 * @var ?\mysqli
	 */
	private ?\mysqli $connection;
	private ParameterProcessor $parameterProcessor;
	private int $affected = 0;

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
	public function __construct(string $hostname, string $username, string $password, string $database, int $port = 0, string $ssl_key = '', string $ssl_cert = '', string $ssl_ca = '') {
		if (!$port) {
			$port = 3306;
		}

		// MySQL SSL connection
		$temp_ssl_key_file = '';

		if ($ssl_key) {
			$temp_ssl_key_file = tempnam(sys_get_temp_dir(), 'mysqli_key_');

			$handle = fopen($temp_ssl_key_file, 'w');

			fwrite($handle, $ssl_key);

			fclose($handle);
		}

		$temp_ssl_cert_file = '';

		if ($ssl_cert) {
			$temp_ssl_cert_file = tempnam(sys_get_temp_dir(), 'mysqli_cert_');

			$handle = fopen($temp_ssl_cert_file, 'w');

			fwrite($handle, $ssl_cert);

			fclose($handle);
		}

		$temp_ssl_ca_file = '';

		if ($ssl_ca) {
			$temp_ssl_ca_file = tempnam(sys_get_temp_dir(), 'mysqli_ca_');

			$handle = fopen($temp_ssl_ca_file, 'w');

			fwrite($handle, '-----BEGIN CERTIFICATE-----' . PHP_EOL . $ssl_ca . PHP_EOL . '-----END CERTIFICATE-----');

			fclose($handle);
		}

		$this->parameterProcessor = new ParameterProcessor();

		try {
			$this->connection = mysqli_init() ?: null;

			if ($temp_ssl_key_file || $temp_ssl_cert_file || $temp_ssl_ca_file) {
				$this->connection->ssl_set($temp_ssl_key_file, $temp_ssl_cert_file, $temp_ssl_ca_file, null, null);
				$this->connection->real_connect($hostname, $username, $password, $database, $port, null, MYSQLI_CLIENT_SSL);
			} else {
				$this->connection->real_connect($hostname, $username, $password, $database, $port, null);
			}

			$this->connection->set_charset('utf8mb4');

			$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
			$this->query("SET FOREIGN_KEY_CHECKS = 0");

			// Sync PHP and DB time zones
			$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!<br/>Message: ' . $e->getMessage());
		}
	}

	/**
	 * Query
	 *
	 * @param string $sql
	 *
	 * @return mixed
	 */
	public function query(string $sql): mixed {
		try {
			$query = $this->connection->query($sql);

			$this->affected = $this->connection->affected_rows;

			if ($query instanceof \mysqli_result) {
				$data = [];

				while ($row = $query->fetch_assoc()) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->num_rows = $query->num_rows;
				$result->row = $data[0] ?? [];
				$result->rows = $data;

				$query->close();

				unset($data);

				return $result;
			} else {
				return true;
			}
		} catch (\mysqli_sql_exception $e) {
			throw new \Exception('Error: ' . $this->connection->error . '<br/>Error No: ' . $this->connection->errno . '<br/>' . $sql);
		}
	}

	public function executeQuery(string $sql, array $bindings = [], array $types = []): \stdClass {
		return $this->executePrepared($sql, $bindings, $types, true);
	}

	public function executeStatement(string $sql, array $bindings = [], array $types = []): int {
		return $this->executePrepared($sql, $bindings, $types, false);
	}

	/**
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 */
	private function executePrepared(string $sql, array $bindings, array $types, bool $fetchResult): \stdClass|int {
		$processed = $this->parameterProcessor->process($sql, $bindings, $types);

		$statement = null;

		try {
			$statement = $this->connection->prepare($processed->sql);

			if (!$statement) {
				throw new \RuntimeException('Could not prepare SQL statement: ' . $this->connection->error);
			}

			$this->bindParameters($statement, $processed->params, $processed->types);

			if (!$statement->execute()) {
				throw new \RuntimeException('Could not execute SQL statement: ' . $statement->error);
			}

			if ($fetchResult) {
				return $this->fetchResult($statement);
			}

			return $this->getAffectedRows($statement);

		} finally {
			if ($statement instanceof \mysqli_stmt) {
				$statement->close();
			}
		}
	}

	/**
	 * @param list<mixed> $values
	 * @param list<ParameterType> $types
	 */
	private function bindParameters(\mysqli_stmt $statement, array $values, array $types): void {
		if (count($values) !== count($types)) {
			throw new \LogicException('SQL parameters and types count mismatch');
		}

		if (!$values) {
			return;
		}

		$signature = '';

		foreach ($values as $index => $value) {
			$type = $types[$index];

			$signature .= match ($type) {
				ParameterType::BOOLEAN,
				ParameterType::INTEGER => 'i',

				ParameterType::FLOAT => 'd',

				ParameterType::NULL,
				ParameterType::STRING => 's'
			};

			if ($type === ParameterType::BOOLEAN) {
				$values[$index] = (int)$value;
			}
		}

		$arguments = [$signature];

		foreach (array_keys($values) as $index) {
			$arguments[] = &$values[$index];
		}

		if (!call_user_func_array([$statement, 'bind_param'], $arguments)) {
			throw new \RuntimeException('Could not bind SQL parameters: ' . $statement->error);
		}
	}

	private function fetchResult(\mysqli_stmt $statement): \stdClass {
		$metadata = $statement->result_metadata();

		if (!$metadata) {
			throw new \RuntimeException('SQL statement does not return a result set');
		}

		$fields = $metadata->fetch_fields();

		$metadata->free();

		$values = array_fill(0, count($fields), null);
		$references = [];

		foreach (array_keys($values) as $index) {
			$references[] = &$values[$index];
		}

		if ($references && !call_user_func_array(
				[$statement, 'bind_result'],
				$references
			)) {
			throw new \RuntimeException('Could not bind SQL result: ' . $statement->error);
		}

		$rows = [];

		while (($status = $statement->fetch()) === true) {
			$row = [];

			foreach ($fields as $index => $field) {
				$row[$field->name] = $values[$index];
			}

			$rows[] = $row;
		}

		if ($status === false) {
			throw new \RuntimeException('Could not fetch SQL result: ' . $statement->error);
		}

		$this->affected = 0;

		$result = new \stdClass();

		$result->row = $rows[0] ?? [];
		$result->rows = $rows;
		$result->num_rows = count($rows);

		return $result;
	}

	private function getAffectedRows(\mysqli_stmt $statement): int {
		$this->affected = (int)$statement->affected_rows;

		return $this->affected;
	}

	/**
	 * Escape
	 *
	 * @param string $value
	 *
	 * @return string
	 */
	public function escape(string $value): string {
		return $this->connection->real_escape_string($value);
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
		return $this->connection->insert_id;
	}

	/**
	 * Is Connected
	 *
	 * @return bool
	 */
	public function isConnected(): bool {
		return $this->connection !== null;
	}

	/**
	 * Destructor
	 *
	 * Closes the DB connection when this object is destroyed.
	 */
	public function __destruct() {
		if ($this->connection) {
			$this->connection->close();

			$this->connection = null;
		}
	}
}
