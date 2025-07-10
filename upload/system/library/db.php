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
	 * Initialize database connection with provided options.
	 *
	 * @param array<string, mixed> $option database connection options
	 *                                     - engine: Database engine (required)
	 *                                     - hostname: Database server hostname (required)
	 *                                     - username: Database username (required)
	 *                                     - password: Database password (optional)
	 *                                     - database: Database name (required)
	 *                                     - port: Database port (required)
	 *                                     - ssl_key: SSL key file path (optional)
	 *                                     - ssl_cert: SSL certificate file path (optional)
	 *                                     - ssl_ca: SSL CA file path (optional)
	 *
	 * @throws \Exception when required database parameters are missing or database engine cannot be loaded
	 *
	 * @example
	 *
	 * $db_config = [
	 *     'engine'   => 'mysqli',
	 *     'hostname' => 'localhost',
	 *     'username' => 'user',
	 *     'password' => 'pass',
	 *     'database' => 'opencart',
	 *     'port'     => '3306'
	 * ];
	 *
	 * $db = new DB($db_config);
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
			}
		}

		$class = 'Opencart\System\Library\DB\\' . $option['engine'];

		if (!class_exists($class)) {
			throw new \Exception('Error: Could not load database adaptor ' . $option['engine'] . '!');
		}

		$this->adaptor = new $class($option);
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
