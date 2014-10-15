<?php
namespace DB;
final class MSSQL {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = mssql_connect($hostname, $username, $password)) {
			exit('Error: Could not make a database connection using ' . $username . '@' . $hostname);
		}

		if (!mssql_select_db($database, $this->link)) {
			exit('Error: Could not connect to database ' . $database);
		}

		mssql_query("SET NAMES 'utf8'", $this->link);
		mssql_query("SET CHARACTER SET utf8", $this->link);
	}

	public function query($sql) {
		$resource = mssql_query($sql, $this->link);

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
			trigger_error('Error: ' . mssql_get_last_message($this->link) . '<br />' . $sql);
			exit();
		}
	}

	public function escape($value) {
		$unpacked = unpack('H*hex', $value);

		return '0x' . $unpacked['hex'];
	}

	public function countAffected() {
		return mssql_rows_affected($this->link);
	}

	public function getLastId() {
		$last_id = false;

		$resource = mssql_query("SELECT @@identity AS id", $this->link);

		if ($row = mssql_fetch_row($resource)) {
			$last_id = trim($row[0]);
		}

		mssql_free_result($resource);

		return $last_id;
	}

	public function __destruct() {
		mssql_close($this->link);
	}
}