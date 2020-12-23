<?php
namespace Opencart\System\Library\DB;
final class PDO {
	private $connection;
	private $statement;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {

		$connection = new \PDO('mysql:host=' . $hostname . ';port=' . $port . ';dbname=' . $database, $username, $password, array(\PDO::ATTR_PERSISTENT => false));

		$this->connection = $connection;

		$this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		register_shutdown_function([$this, 'close']);


		//print_r($connection->errorCode());
/*
		if (!$connection->errorCode()) {
			$this->connection = $connection;

			// Needs to use register_shutdown_function as __destructors don't automatically trigger at the end of page load.
			register_shutdown_function([$this, 'close']);
		} else {
			throw new \Exception('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
		}
*/
		//$this->connection->exec("SET NAMES 'utf8'");
		//$this->connection->exec("SET CHARACTER SET utf8");
		//$this->connection->exec("SET CHARACTER_SET_CONNECTION=utf8");
		//$this->connection->exec("SET SQL_MODE = ''");
	}

	public function query($sql, $params = []) {
		$this->statement = $this->connection->prepare($sql);

		$result = false;

		//try {
			if ($this->statement && $this->statement->execute($params)) {
				$data = [];

				while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->row = (isset($data[0]) ? $data[0] : []);
				$result->rows = $data;
				$result->num_rows = $this->statement->rowCount();
			}
	//	} catch (\PDOException $e) {
		//	throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode() . ' <br />' . $sql);
		//}

		if ($result) {
			return $result;
		} else {
			$result = new \stdClass();
			$result->row = [];
			$result->rows = [];
			$result->num_rows = 0;

			return $result;
		}
	}

	public function execute() {
		try {
			if ($this->statement && $this->statement->execute()) {
				$data = [];

				while ($row = $this->statement->fetch(\PDO::FETCH_ASSOC)) {
					$data[] = $row;
				}

				$result = new \stdClass();
				$result->row = (isset($data[0])) ? $data[0] : [];
				$result->rows = $data;
				$result->num_rows = $this->statement->rowCount();
			}
		} catch (\PDOException $e) {
			throw new \Exception('Error: ' . $e->getMessage() . ' Error Code : ' . $e->getCode());
		}
	}

	public function prepare($sql) {
		$this->statement = $this->connection->prepare($sql);
	}

	public function bindParam($parameter, $variable, $data_type = \PDO::PARAM_STR, $length = 0) {
		if ($length) {
			$this->statement->bindParam($parameter, $variable, $data_type, $length);
		} else {
			$this->statement->bindParam($parameter, $variable, $data_type);
		}
	}

	public function escape($value) {
		return str_replace(["\\", "\0", "\n", "\r", "\x1a", "'", '"'], ["\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'], $value);
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

	public function close() {
		$this->connection = '';
	}

	public function __destruct() {
		$this->connection = '';
	}
}