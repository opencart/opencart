<?php
final class Postgre {
	private $link;

	public function __construct($hostname, $username, $password, $database) {
		if (!$this->link = pg_connect('hostname=' . $hostname . ' username=' . $username . ' password='	. $password . ' database=' . $database)) {
			trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
		}

		if (!mysql_select_db($database, $this->link)) {
			trigger_error('Error: Could not connect to database ' . $database);
		}

		pg_query($this->link, "SET CLIENT_ENCODING TO 'UTF8'");
	}

	public function query($sql) {
		$resource = pg_query($this->link, $sql);

		if ($resource) {
			if (is_resource($resource)) {
				$i = 0;

				$data = array();

				while ($result = pg_fetch_assoc($resource)) {
					$data[$i] = $result;

					$i++;
				}

				pg_free_result($resource);

				$query = new stdClass();
				$query->row = isset($data[0]) ? $data[0] : array();
				$query->rows = $data;
				$query->num_rows = $i;

				unset($data);

				return $query;	
			} else {
				return true;
			}
		} else {
			trigger_error('Error: ' . pg_result_error($this->link) . '<br />' . $sql);
			exit();
		}
	}

	public function escape($value) {
		return pg_escape_string($this->link, $value);
	}

	public function countAffected() {
		return pg_affected_rows($this->link);
	}

	public function getLastId() {
		$query = $this->query("SELECT LASTVAL() AS `id`");

		return $query->row['id'];
	}

	public function __destruct() {
		pg_close($this->link);
	}
}
?>