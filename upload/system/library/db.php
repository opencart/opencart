<?php
class DB {
	/**
	 * @var Object Database extension object
	 */
	private $db;
	
	/**
	 * Initialization
	 * @param string $driver   Database driver name
	 * @param string $hostname Host name to connect
	 * @param string $username Username for database access
	 * @param string $password Password for database access
	 * @param string $database Database name
	 * @param string $port     Port number
	 */
	public function __construct($driver, $hostname, $username, $password, $database, $port = '3306') {
		$file = dirname(__FILE__) . '/driver/database/' . $driver . '.php';
		
		if (file_exists($file)) {
			require_once($file);
			
			$class = 'DB' . $driver;
			
			$this->db = new $class($hostname, $username, $password, $database, $port);
		} else {
			exit('Error: Could not load database driver ' . $driver . '!');
		}		
	}

	/**
	 * Query database. This function can also process parametrized query for 
	 * non-parametrized-type of drivers. So nothing will be broken if somebody 
	 * will write a model with a parametrized approach while the driver is not 
	 * supported this approach.
	 * 
	 * If additional parameters are set and $db can accept additional 
	 * parameters for a 'query' method, the method will be called with these 
	 * additionals parameters.
	 * 
	 * If additional parameters are set, but the method is not supporting additional 
	 * parameters, there will be an attempt to include these prameters into SQL query
	 * by calling $this->explain method.
	 * 
	 * @see $this->explain
	 * 
	 * @param  string $sql    Query string
	 * @param  array  $params Additional parameters to pass into query string
	 * @return mixed          $db.query()
	 */
	public function query($sql, $params = array()) {
		if (count($params)) {
			$reflection = new ReflectionClass($this->db);
			$method = $reflection->getMethod('query');
			
			if (count($method->getParameters()) > 1) {
				return $this->db->query($sql, $params);
			} else {
				$sql = $this->explain($sql, $params);
			}
		}
		
		return $this->db->query($sql);
	}
	
	/**
	 * Escape value for including in a query
	 * @param  mixed $value Value to escape
	 * @return string $db.escape()
	 */
	public function escape($value) {
		return $this->db->escape($value);
	}
	
	/**
	 * Get count of rows which were affected by the last query
	 * @return int $db.countAffected()
	 */
	public function countAffected() {
		return $this->db->countAffected();
	}
	
	/**
	 * Get last inserted id
	 * @return int $db.getLastId()
	 */
	public function getLastId() {
		return $this->db->getLastId();
	}
	
	/**
	 * Returns query string which is prepared with parameters.
	 * 
	 * If $this->db driver has the 'explain' method, the method will be called.
	 * Otherwise there is a PDO-like SQL parsing algorithm will be used to include 
	 * parameters into the query.
	 * 
	 * @param  string $sql    Query string
	 * @param  array  $params Additional parameters
	 * @return string         $db.explain()
	 */
	public function explain($sql, $params = array()) {
		if (method_exists($this->db, 'explain')) {
			return $this->db->explain($sql, $params);
		} else {
			/*
			 * PDO SQL parser algorithm implementation.
			 * See PHP source code: /ext/pdo/pdo_sql_parser.c
			 */
			
			$params_keys = array_keys($params);
			$positional_mode = array_filter($params_keys, 'is_int') === $params_keys ? true : false;
			
			$result_sql = '';
			
			$sql_chars = str_split($sql);
			$sql_length = count($sql_chars);
			
			$subst = $quote = $double_quote = $comment = $eol_comment = false;
			$subst_token = '';
			$position = 0;
			
			foreach ($sql_chars as $key => $char) {
				switch ($char) {
					case '"':
						if ($sql_chars[$key-1] != '\\') {
							$double_quote = $double_quote && !$comment && !$eol_comment ? false : true;
						}
						break;
					case "'":
						if ($sql_chars[$key-1] != '\\') {
							$quote = $quote && !$comment && !$eol_comment ? false : true;
						}
						break;
					case "/":
						if ($sql_chars[$key+1] == '*' && !$eol_comment) {
							$comment = true;
						}
						
						if ($sql_chars[$key-1] == '*' && !$eol_comment) {
							$comment = false;
						}
						break;
					case "-":
						if ($sql_chars[$key+1] == '-' && !$comment) {
							$eol_comment = true;
						}
						break;
					case "\n":
					case "\r":
						$eol_comment = false;
						break;
					case "?":
						if (!$double_quote && !$quote && !$comment && !$eol_comment && $positional_mode) {
							$char = "'" . $this->escape($params[$position++]) . "'";
						}
						break;
					case ":":
						if (!$double_quote && !$quote && !$comment && !$eol_comment && !$positional_mode && preg_match('/[a-zA-Z0-9_]/', $sql_chars[$key+1])) {
							$subst = true;
							$subst_token = $char;
							$char = '';
						}
						break;
					default:
						if ($subst) {
							if ( preg_match('/[a-zA-Z0-9_]/', $char) ) {
								$subst_token .= $char;
								$char = '';
								
								if ( $sql_length == $key+1 || ($sql_length > $key+1 && !preg_match('/[a-zA-Z0-9_]/', $sql_chars[$key+1])) ) {
									$subst = false;
									$char = "'" . $this->escape($params[$subst_token]) . "'";
								}
							} else {
								$subst = false;
								$char = "'" . $this->escape($params[$subst_token]) . "'";	
							}
						}
						break;
				}
				
				$result_sql .= $char;
			}
			
			return $result_sql ? $result_sql : $sql;
		}
	}
}
