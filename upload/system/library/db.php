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
	public function __construct($adaptor, $hostname, $username, $password, $database, $port = NULL) {
		$class = 'DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port);
			$query = $this->adaptor->query("SELECT value FROM " . DB_PREFIX . "setting WHERE `code` = 'config' AND `key` = 'config_timezone' ");
            if (!empty($query->row)) {
                $timezone = $query->row['value'];
                date_default_timezone_set($timezone);

                $now = new DateTime();
                $mins = $now->getOffset() / 60;
                $sgn = ($mins < 0 ? -1 : 1);
                $mins = abs($mins);
                $hrs = floor($mins / 60);
                $mins -= $hrs * 60;
                $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
                $this->adaptor->query("SET time_zone='" . $offset . "'");
            }
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
     * 
     *
     * @param	string	$sql
	 * 
	 * @return	array
     */
	public function query($sql) {
		return $this->adaptor->query($sql);
	}

	/**
     * 
     *
     * @param	string	$value
	 * 
	 * @return	string
     */
	public function escape($value) {
		return $this->adaptor->escape($value);
	}

	/**
     * 
     *
     * @param	string	$value
	 * 
	 * @return	array
     */
	public function countAffected() {
		return $this->adaptor->countAffected();
	}

	/**
     * 
	 * 
	 * @return	int
     */
	public function getLastId() {
		return $this->adaptor->getLastId();
	}
	
	/**
     * 
	 * 
	 * @return	array
     */	
	public function connected() {
		return $this->adaptor->connected();
	}
}