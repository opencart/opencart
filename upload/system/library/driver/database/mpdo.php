<?php
/**
 * "mPDO" name is given because the "PDO" is already used by PHP.
 */

final class DBmPDO {
	/**
	 * @var PDO
	 */
	private $pdo = null;
	
	/**
	 * @var PDOStatement
	 */
	private $statement = null;
	
	/**
	 * Initialization
	 * @param  string $hostname Host name to connect
	 * @param  string $username Username for database access
	 * @param  string $password Password for database access
	 * @param  string $database Database name
	 * @param  string $port     Port number
	 */
	public function __construct($hostname, $username, $password, $database, $port = '3306') {
		try {
			$this->pdo = new PDO("mysql:host=$hostname;port=$port;dbname=$database", $username, $password, array(PDO::ATTR_PERSISTENT => true));
			$this->pdo->query("SET NAMES 'utf8', CHARACTER SET utf8, CHARACTER_SET_CONNECTION=utf8, SQL_MODE = ''");
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e) {
			trigger_error('Error: Could not make a database link ('. $e->getMessage() . '). Error Code : ' . $e->getCode() . ' <br />');
			exit();
		}
	}
	
	/**
	 * Query database
	 * @param  string $sql    Query string
	 * @param  array  $params Additional parameters to pass into query string
	 * @return stdClass       Query result
	 */
	public function query($sql, $params = array()) {
		try {
			if ($this->statement) {
				// Close cursor to prevent HY000 error.
				$this->statement->closeCursor();
			}
			
			$this->statement = $this->pdo->prepare($sql);
			$this->statement->execute($params);
			
			$result = $this->statement->fetchAll();
			
			$query = new stdClass();
			$query->row = isset($result[0]) ? $result[0] : array();
			$query->rows = $result;
			$query->num_rows = count($result);
			
			unset($result);
			
			return $query;
		} catch (PDOException $e) {
			if ($e->getCode() != 'HY000') {
				trigger_error('Error: ' . $e->getMessage() . '<br>Error No: ' . $e->getCode() . '<br>Query: ' . $this->explain($sql, $params) . '<br>');
				exit();
			}
		}
	}
	
	/**
	 * Escape value for including in a query
	 * @param  mixed  $value Value to escape
	 * @return string Escaped string
	 */
	public function escape($value) {
		if ($this->pdo) {
			return preg_replace("/^'(.*)'$/", '$1', $this->pdo->quote($value));
		} else {
			trigger_error('Error: Escaping value is avilable only for an active database link.');
			exit();
		}
	}
	
	/**
	 * Get count of rows which were affected by the last query
	 * @return integer Rows count
	 */
	public function countAffected() {
		return $this->statement ? $this->statement->rowCount() : 0;
	}
	
	/**
	 * Get last inserted id
	 * @return integer Id
	 */
	public function getLastId() {
		if ($this->pdo) {
			return $this->pdo->lastInsertId();
		} else {
			trigger_error('Error: Could not obtain last inserted ID because of inactive database link.');
			exit();
		}
	}
	
	/**
	 * Returns query string which is prepared with parameters.
	 * @param  string $sql    Query string
	 * @param  array  $params Additional parameters
	 * @return mixed         $db.explain()
	 */
	public function explain($sql, $params = array()) {
		if ( count($params) ) {
			/*
			 * PDO SQL parser algorithm implementation.
			 * See PHP source code: /ext/pdo/pdo_sql_parser.c
			 */
			$params_keys = array_keys($params);
			
			$positional_mode = array_filter($params_keys, 'is_int') === $params_keys ? true : false;
			
			$result_sql = '';
			$sql_chars = str_split($sql);
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
							} else {
								$subst = false;
								$char = "'" . $this->escape($params[$subst_token]) . "'";	
							}
							
							if ($subst && count($sql_chars) == $key+1) {
								$subst = false;
								$char = "'" . $this->escape($params[$subst_token]) . "'";
							}
						}
						break;
				}
				
				$result_sql .= $char;
			}
		} else {
			$result_sql = $sql;
		}
		
		return $result_sql;
	}
	
	/**
	 * Destruct PDOStatement and PDO instances
	 */
	public function __destruct() {
		if ($this->statement) {
			unset($this->statement);
		}
		
		if ($this->pdo) {
			unset($this->pdo);
		}
	}
}
