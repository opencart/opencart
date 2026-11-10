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

use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\UpdateInterface;
use Opencart\System\Engine\Registry;
use Opencart\System\Library\Database\Parameter\ArrayParameterType;
use Opencart\System\Library\Database\Parameter\ParameterType;
use Opencart\System\Library\Database\Query\QueryConnectionInterface;
use Opencart\System\Library\Database\Query\QueryExecutor;
use Opencart\System\Library\Database\Query\QueryFactory;

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
	private ?Registry $registry = null;
	private ?QueryFactory $queryFactory = null;

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
	public function __construct(string $adaptor, string $hostname, string $username, string $password, string $database, string $port = '', string $ssl_key = '', string $ssl_cert = '', string $ssl_ca = '', ?Registry $registry = null) {
		$this->registry = $registry;
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
	 * Create SELECT query.
	 */
	public function select(): SelectInterface {
		return $this->getQueryFactory()->newSelect();
	}

	/**
	 * Create INSERT query.
	 */
	public function insert(): InsertInterface {
		return $this->getQueryFactory()->newInsert();
	}

	/**
	 * Create UPDATE query.
	 */
	public function update(): UpdateInterface {
		return $this->getQueryFactory()->newUpdate();
	}

	/**
	 * Create DELETE query.
	 *
	 */
	public function delete(): DeleteInterface {
		return $this->getQueryFactory()->newDelete();
	}

	/**
	 * Execute a prepared query.
	 *
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 */
	public function executeQuery(string $sql, array $bindings = [], array $types = []): \stdClass {
		if (!($this->adaptor instanceof QueryConnectionInterface)) {
			throw new \LogicException(
				'The configured database adapter does not support prepared query execution'
			);
		}

		return $this->adaptor->executeQuery($sql, $bindings, $types);
	}

	/**
	 * Execute a prepared statement.
	 *
	 * @param array<array-key, mixed> $bindings
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 */
	public function executeStatement(string $sql, array $bindings = [], array $types = []): int {
		if (!($this->adaptor instanceof QueryConnectionInterface)) {
			throw new \LogicException(
				'The configured database adapter does not support prepared query execution'
			);
		}

		return $this->adaptor->executeStatement($sql, $bindings, $types);
	}

	public function registerQueryClass(string $queryType, string $class, bool $replace = false): void {
		$this->getQueryFactory()->registerQueryClass($queryType, $class, $replace);
	}

	public function registerQueryMacro(string $queryType, string $name, \Closure $macro, bool $replace = false): void {
		$this->getQueryFactory()->registerMacro($queryType, $name, $macro, $replace);
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

	private function getQueryFactory(): QueryFactory {
		if ($this->queryFactory === null) {
			if (!($this->adaptor instanceof QueryConnectionInterface)) {
				throw new \LogicException(
					'The configured database adapter does not support prepared query execution'
				);
			}

			$executor = new QueryExecutor($this->adaptor, $this->registry);
			$this->queryFactory = new QueryFactory($executor);
		}

		return $this->queryFactory;
	}
}
