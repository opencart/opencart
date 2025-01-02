<?php
/**
 * @package        OpenCart
 *
 * @author         Daniel Kerr
 * @copyright      Copyright (c) 2005 - 2022, OpenCart, Ltd. (https://www.opencart.com/)
 * @license        https://opensource.org/licenses/GPL-3.0
 *
 * @see           https://www.opencart.com
 */
namespace Opencart\System\Library;
/**
 * Class DB Adaptor
 *
 * @package Opencart\System\Library
 */
class DB {
	/**
	 * @var object
	 */
	private object $adaptor;

	/**
	 * Constructor
	 *
	 * @param string $adaptor
	 * @param string $hostname
	 * @param string $username
	 * @param string $password
	 * @param string $database
	 * @param string $port
	 * @param string $ssl_key
	 * @param string $ssl_cert
	 * @param string $ssl_ca
	 */
	public function __construct(string $adaptor, string $hostname, string $username, string $password, string $database, string $port = '', string $ssl_key = '', string $ssl_cert = '', string $ssl_ca = '') {
		$class = 'Opencart\System\Library\DB\\' . $adaptor;

		if (class_exists($class)) {
			$this->adaptor = new $class($hostname, $username, $password, $database, $port, $ssl_key, $ssl_cert, $ssl_ca);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $adaptor . '!');
		}
	}

	/**
	 * Query
	 *
	 * @param string $sql SQL statement to be executed
	 *
	 * @return mixed
	 */
	public function query(string $sql) {
		return $this->adaptor->query($sql);
	}

	/**
	 * Escape
	 *
	 * @param string $value Value to be protected against SQL injections
	 *
	 * @return string Returns escaped value
	 */
	public function escape(string $value): string {
		return $this->adaptor->escape($value);
	}

	/**
	 * Count Affected
	 *
	 * Gets the total number of affected rows from the last query
	 *
	 * @return int returns the total number of affected rows
	 */
	public function countAffected(): int {
		return $this->adaptor->countAffected();
	}

	/**
	 * Get Last Id
	 *
	 * Get the last ID gets the primary key that was returned after creating a row in a table.
	 *
	 * @return int Returns last ID
	 */
	public function getLastId(): int {
		return $this->adaptor->getLastId();
	}

	/**
	 * Is Connected
	 *
	 * Checks if a DB connection is active.
	 *
	 * @return bool
	 */
	public function isConnected(): bool {
		return $this->adaptor->isConnected();
	}
}
