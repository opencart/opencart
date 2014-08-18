<?php
/**
 * Proxy PDO Class connecting to a mysql database.
 * @author	Richard Parnaby-King
 * @link		http://richard.parnaby-king.co.uk
 */
final class DBPDOMySQL extends PDO {
	/**
	 * @var	int 
	 */
	protected $_affectedCount;

	/**
	 * Proxy to PDO constructor with connection string
	 * @param string  hostname, e.g. localhost
	 * @param string  username
	 * @param string  password
	 * @param string  database database name
	 */
	public function __construct($hostname, $username, $password, $database) {
		parent::__construct('mysql:host='.$hostname.';dbname='.$database, $username, $password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	}

	/**
	 * Prepare and execute query. Format results into OpenCart resultset
	 * @param	string SQL query to be executed
	 * @return	stdClass OpenCart result set
	 */
	public function query($sql) {
    $stmt = $this->prepare($sql);
    $stmt->execute();
		$this->_affectedCount = $stmt->rowCount();
		$data = array();
		while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $result;
		}
		$query = new stdClass();
		$query->row = isset($data[0]) ? $data[0] : array();
		$query->rows = $data;
		$query->num_rows = count($data);
		return $query;
	}

	/**
	 * Proxy to PDO:quote(). Return the escaped query string
	 * @param	string
	 * @return	string
	 */
	public function escape($value) {
		return $this->quote($value);
	}

	/**
	 * Return the number of rows affected by last query
	 * @return	int
	 */
	public function countAffected() {
		return $this->_affectedCount;
	}

	/**
	 * Return the id of the last row inserted
	 * @return	int
	 */
	public function getLastId() {
		return $this->lastInsertId();
	}
}
?>
