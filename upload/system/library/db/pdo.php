<?php
namespace Opencart\System\Library\DB;
class PDO {
	private $connection;
	private $statement;
	private $data;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		try {
			$pdo = new \PDO('mysql:host=' . $hostname . ';port=' . $port . ';dbname=' . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => false));
		} catch (\PDOException $e) {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}

		if ($pdo) {
			$this->connection = $pdo;
			$this->connection->query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION'");
		}
	}

	public function query($sql) {
		$this->statement = $this->connection->prepare(preg_replace('/(?:\'\:)([a-f0-9]*.)(?:\')/', ':$1', $sql));

		foreach ($this->data as $key => $value) {
			$this->statement->bindParam($key, $value, \PDO::PARAM_STR);
		}

		$this->data = [];

		try {
			if ($this->statement && $this->statement->execute()) {
				if ($this->statement->columnCount()) {
					$data = $this->statement->fetchAll(\PDO::FETCH_ASSOC);

					$result = new \stdClass();
					$result->row = (isset($data[0]) ? $data[0] : []);
					$result->rows = $data;
					$result->num_rows = count($data);

					return $result;
				}
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
		}
	}

	public function escape($value) {
		$key = ':' . token(8);

		$this->data[$key] = $value;

		return $key;
	}

	public function countAffected() {
		if ($this->statement) {
			return $this->statement->rowCount();
		} else {
			return 0;
		}
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
}