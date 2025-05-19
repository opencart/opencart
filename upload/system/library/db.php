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
	public function __construct(array $option = []) {
		$required = [
			'engine',
			'hostname',
			'username',
			'database',
			'port'
		];

		foreach ($required as $key) {
			if (empty($option[$key])) {
				throw new \Exception('Error: Database ' . $key . ' required!');
				exit();
			}
		}

		$class = 'Opencart\System\Library\DB\\' . $option['engine'];

		if (class_exists($class)) {
			$this->adaptor = new $class($option);
		} else {
			throw new \Exception('Error: Could not load database adaptor ' . $option['engine'] . '!');
			exit();
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
	 * Get Last ID
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
