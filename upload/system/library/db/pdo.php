<?php
namespace Opencart\System\Library\DB;

use Opencart\System\Library\Database\Parameter\ParameterProcessor;
use Opencart\System\Library\Database\Parameter\ParameterType;
use Opencart\System\Library\Database\Query\QueryConnectionInterface;

/**
 * Class PDO
 *
 * @package Opencart\System\Library\DB
 */
class PDO implements QueryConnectionInterface {
	/**
	 * @var \PDO|null
	 */
	private ?\PDO $connection;
	/**
	 * @var array<string, string>
	 */
	private array $data = [];
	/**
	 * @var int
	 */
	private int $affected;
	private ParameterProcessor $parameterProcessor;

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
			$port = '3306';
		}

		$this->parameterProcessor = new ParameterProcessor();

		try {
			$pdo = new \PDO('mysql:host=' . $hostname . ';port=' . $port . ';dbname=' . $database . ';charset=utf8mb4', $username, $password, [\PDO::ATTR_PERSISTENT => false, \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci']);
		} catch (\PDOException $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}

		$this->connection = $pdo;

		$this->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ENGINE_SUBSTITUTION'");
		$this->query("SET FOREIGN_KEY_CHECKS = 0");

		// Sync PHP and DB time zones
		$this->query("SET `time_zone` = '" . $this->escape(date('P')) . "'");
	}

	/**
	 * Query
	 *
	 * @param string $sql
	 *
	 * @return \stdClass|true
	 */
	public function query(string $sql) {
		$sql = preg_replace('/(?:\'\:)([a-z0-9]*.)(?:\')/', ':$1', $sql);

		$statement = $this->connection->prepare($sql);

		try {
			if ($statement && $statement->execute($this->data)) {
				$this->data = [];

				if ($statement->columnCount()) {
					$data = $statement->fetchAll(\PDO::FETCH_ASSOC);
					$statement->closeCursor();

					$result = new \stdClass();
					$result->row = $data[0] ?? [];
					$result->rows = $data;
					$result->num_rows = count($data);
					$this->affected = 0;

					return $result;
				} else {
					$this->affected = $statement->rowCount();
					$statement->closeCursor();

					return true;
				}
			} else {
				return true;
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' <br/>Error Code : ' . $e->getCode() . ' <br/>' . $sql);
		}
	}

	/**
	 * @param string                  $sql
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, mixed> $types
	 */
	public function executeQuery(string $sql, array $bindings = [], array $types = []): \stdClass {
		return $this->executePrepared($sql, $bindings, $types, true);
	}

	/**
	 * @param string                  $sql
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, mixed> $types
	 */
	public function executeStatement(string $sql, array $bindings = [], array $types = []): int {
		return $this->executePrepared($sql, $bindings, $types, false);
	}

	/**
	 * @param string                  $sql
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, mixed> $types
	 * @param bool                    $fetchResult
	 */
	private function executePrepared(string $sql, array $bindings, array $types, bool $fetchResult): int|\stdClass {
		$processed = $this->parameterProcessor->process($sql, $bindings, $types);

		$statement = null;

		try {
			$statement = $this->connection->prepare($processed->sql);

			if (!$statement) {
				$error = $this->connection->errorInfo();
				$message = $error[2] ?? 'Unknown database error';

				throw new \RuntimeException('Could not prepare SQL statement: ' . $message);
			}

			$this->bindParameters($statement, $processed->params, $processed->types);

			if (!$statement->execute()) {
				$error = $statement->errorInfo();
				$message = $error[2] ?? 'Unknown statement error';

				throw new \RuntimeException('Could not execute SQL statement: ' . $message);
			}

			if ($fetchResult) {
				return $this->fetchResult($statement);
			}

			return $this->getAffectedRows($statement);

		} finally {
			if ($statement instanceof \PDOStatement) {
				$statement->closeCursor();
			}
		}
	}

	/**
	 * @param \PDOStatement       $statement
	 * @param list<mixed>         $values
	 * @param list<ParameterType> $types
	 */
	private function bindParameters(\PDOStatement $statement, array $values, array $types): void {
		if (count($values) !== count($types)) {
			throw new \LogicException('SQL parameters and types count mismatch');
		}

		foreach ($values as $index => $value) {
			$type = match ($types[$index]) {
				ParameterType::NULL    => \PDO::PARAM_NULL,
				ParameterType::BOOLEAN => \PDO::PARAM_BOOL,
				ParameterType::INTEGER => \PDO::PARAM_INT,
				ParameterType::FLOAT,
				ParameterType::STRING => \PDO::PARAM_STR
			};

			if (!$statement->bindValue($index + 1, $value, $type)) {
				throw new \RuntimeException('Could not bind SQL parameter: ' . ($index + 1));
			}
		}
	}

	private function fetchResult(\PDOStatement $statement): \stdClass {
		if ($statement->columnCount() === 0) {
			throw new \RuntimeException('SQL statement does not return a result set');
		}

		$rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

		$this->affected = 0;

		$result = new \stdClass();

		$result->row = $rows[0] ?? [];
		$result->rows = $rows;
		$result->num_rows = count($rows);

		return $result;
	}

	private function getAffectedRows(\PDOStatement $statement): int {
		$this->affected = $statement->rowCount();

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
		$key = ':' . count($this->data);

		$this->data[$key] = $value;

		return $key;
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
	 * @return ?int
	 */
	public function getLastId(): ?int {
		$id = $this->connection->lastInsertId();

		return $id ? (int)$id : null;
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
		$this->connection = null;
	}
}
