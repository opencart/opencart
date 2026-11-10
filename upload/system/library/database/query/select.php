<?php

namespace Opencart\System\Library\Database\Query;

use Aura\SqlQuery\Common\QuoterInterface;
use Aura\SqlQuery\Mysql\Select as AuraSelect;

class Select extends AuraSelect implements QueryEventAwareInterface {
	use QueryMacroTrait;
	use QueryEventTrait;

	protected QueryExecutor $queryExecutor;

	public function __construct(
		QuoterInterface $quoter,
		                $builder,
		QueryExecutor $executor,
		QueryMacroRegistry $macroRegistry
	) {
		parent::__construct($quoter, $builder);

		$this->queryExecutor = $executor;
		$this->initializeMacros($macroRegistry, 'select');
	}

	/**
	 * Fetch all rows.
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function all(): array {
		return $this->queryExecutor->fetchAll($this);
	}

	/**
	 * Fetch the first row without modifying the current query.
	 *
	 * @return array<string, mixed>
	 */
	public function first(): array {
		$query = clone $this;
		$query->limit(1);

		return $this->queryExecutor->fetchOne($query);
	}

	/**
	 * Fetch a value from the first row.
	 */
	public function value(string $column): mixed {
		$row = $this->first();

		return array_key_exists($column, $row) ? $row[$column] : null;
	}

	/**
	 * Count rows matching the query, ignoring top-level LIMIT and OFFSET.
	 * GROUP BY, HAVING, DISTINCT and UNION are preserved.
	 */
	public function count(): int {
		return $this->queryExecutor->fetchCount($this);
	}

	/**
	 * Determine whether the query returns at least one row, ignoring
	 * top-level LIMIT and OFFSET.
	 */
	public function exists(): bool {
		return $this->queryExecutor->fetchExists($this);
	}
}
