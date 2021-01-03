<?php
namespace Opencart\System\Library\DB;
class PDO {
	private $connection;
	private $data = [];
	private $affected;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		try {
			$pdo = @new \PDO('mysql:host=' . $hostname . ';port=' . $port . ';dbname=' . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => false));
		} catch (\PDOException $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}

		if ($pdo) {
			$this->connection = $pdo;
			$this->connection->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION'");
		}
	}

	public function query($sql) {
		$statement = $this->connection->prepare(preg_replace('/(?:\'\:)([a-z0-9]*.)(?:\')/', ':$1', $sql));

		try {
			if ($statement && $statement->execute($this->data)) {
				$this->data = [];

				if ($statement->columnCount()) {
					$data = $statement->fetchAll(\PDO::FETCH_ASSOC);

					$result = new \stdClass();
					$result->row = (isset($data[0]) ? $data[0] : []);
					$result->rows = $data;
					$result->num_rows = count($data);
					$this->affected = 0;

					return $result;
				} else {
					$this->affected = $statement->rowCount();
				}

				$statement->closeCursor();
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
		}
	}

	public function escape($value) {
		$key = ':' . count($this->data);

		$this->data[$key] = $value;

		return $key;
	}

	public function countAffected() {
		return $this->affected;
	}

	public function getLastId() {
		return $this->connection->lastInsertId();
	}

	public function isConnected() {
		if ($this->connection) {
			return true;
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
		$this->connection = '';
	}
}