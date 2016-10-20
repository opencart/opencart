<?php
namespace DB;
final class MSSQL {
	private $connection;

	public function __construct($hostname, $username, $password, $database, $port = '1433') {
		if (!$this->connection = mssql_connect($hostname. ':' . $port, $username, $password)) {
			throw new \Exception('Error: Could not make a database connection using ' . $username . '@' . $hostname);
		}

		if (!mssql_select_db($database, $this->link)) {
			throw new \Exception('Error: Could not connect to database ' . $database);
		}

		mssql_query("SET NAMES 'utf8'", $this->connection);
		mssql_query("SET CHARACTER SET utf8", $this->connection);
	}

	public function query($sql) {
		$resource = mssql_query($sql, $this->connection);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = mssql_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				mssql_free_result($resource);

				$query = new \stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;
			} else {
				return true;
			}
		} else {
			throw new \Exception('Error: ' . mssql_get_last_message($this->connection) . '<br />' . $sql);
		}
	}

	public function escape($value) {
		$unpacked = unpack('H*hex', $value);

		return '0x' . $unpacked['hex'];
	}

	public function countAffected() {
		return mssql_rows_affected($this->connection);
	}

	public function getLastId() {
		$last_id = false;

		$resource = mssql_query("SELECT @@identity AS id", $this->connection);

		if ($row = mssql_fetch_row($resource)) {
			$last_id = trim($row[0]);
		}

		mssql_free_result($resource);

		return $last_id;
	}

	public function __destruct() {
		mssql_close($this->connection);
	}
}