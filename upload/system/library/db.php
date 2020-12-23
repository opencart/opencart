<?php
/**
 * @package		OpenCart
 * @author		Daniel Kerr
 * @copyright	Copyright (c) 2005 - 2017, OpenCart, Ltd. (https://www.opencart.com/)
 * @license		https://opensource.org/licenses/GPL-3.0
 * @link		https://www.opencart.com
*/

/**
* DB class
*/
namespace Opencart\System\Library;
class DB {
	private $adaptor;

	/**
	 * Constructor
	 *
	 * @param	string	$adaptor
	 * @param	string	$hostname
	 * @param	string	$username
     * @param	string	$password
	 * @param	string	$database
	 * @param	int		$port
	 *
 	*/
	public function __construct($adaptor, $hostname, $username, $password, $database, $port = '') {
		$class = 'Opencart\System\Library\DB\\' . $adaptor;

		if (class_exists($class)) {
			try {
				$this->adaptor = new $class($hostname, $username, $password, $database, $port);
			} catch (\Exception $e) {
				throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
			}
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
     * Query
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

	/**
     * Escape
     *
     * @param	string	$value
	 * 
	 * @return	string
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	/**
     * Count Affected
	 *
	 *
	 *
	 * @return	int	returns the total number of affected rows.
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	/**
     * Get Last ID
	 *
	 * Get the last ID gets the primary key that was returned after creating a row in a table.
	 *
	 * @return	int returns last ID
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	/**
     * IsConnected
	 *
	 * Checks if a DB connection is active.
	 *
	 * @return	bool
     */	
	public function isConnected() {
		return $this->adaptor->isConnected();
	}

	/**
	 * Close
	 *
	 * Closes the DB connection
	 *
	 * @return	bool
	 */
	public function close() {
		return $this->adaptor->close();
	}

	/**
	 * __destruct
	 *
	 * Closes the DB connection when this object is destroyed.
	 *
	 */
	public function __destruct() {
		$this->adaptor->close();
	}
}