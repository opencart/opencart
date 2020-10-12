<?php
namespace Opencart\System\Library\DB;
class MySQLi {
	private $connection;

	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		$connection = new \MySQLi($hostname, $username, $password, $database, $port);

		if (!$connection->connect_error) {
			$this->connection = $connection;

			$this->connection->report_mode = MYSQLI_REPORT_STRICT;

			$this->connection->set_charset('utf8');

			//register_shutdown_function([$this, 'close']);
		} else {
			error_log('Error: Could not make a database link using ' . $username . '@' . $hostname . '!');
			exit();
		}
	}

	public function query($sql) {
		$query = $this->connection->query($sql);

		if (!$this->connection->errno) {
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

				return $result;
			} else {
				return true;
			}
		} else {
			throw new \Exception('Error: ' . $this->connection->error  . '<br />Error No: ' . $this->connection->errno . '<br />' . $sql);
		}
	}

	public function escape($value) {
		return $this->connection->real_escape_string($value);
	}
	
	public function countAffected() {
		return $this->connection->affected_rows;
	}

	public function getLastId() {
		return $this->connection->insert_id;
	}
	
	public function isConnected() {
		return $this->connection->ping();
	}
	
	public function close() {
		if (!$this->connection) {
			$this->connection->close();
		}
	}

	public function __destruct() {
		$this->close();
	}
}
