<?php

namespace Opencart\System\Library\Database\Query;

use Aura\SqlQuery\AbstractQuery;
use Opencart\System\Library\Database\Parameter\ArrayParameterType;
use Opencart\System\Engine\Event;
use Opencart\System\Engine\Registry;

/**
 * Executes Aura query objects through the existing OpenCart DB adapter.
 */
final class QueryExecutor {
	public function __construct(
		private readonly QueryConnectionInterface $connection,
		private readonly ?Registry $registry = null
	) {}

	/**
	 * Fetch all rows returned by a SELECT query.
	 *
	 * @param AbstractQuery $query
	 *
	 * @return array<int, array<string, mixed>>
	 */
	public function fetchAll(AbstractQuery $query): array {
		return $this->executeQuery($query)->rows;
	}

	/**
	 * Fetch the first row returned by a SELECT query.
	 *
	 * @param AbstractQuery $query
	 *
	 * @return array<string, mixed>
	 */
	public function fetchOne(AbstractQuery $query): array {
		return $this->executeQuery($query)->row;
	}

	/**
	 * Count rows matching the query, ignoring its top-level pagination.
	 *
	 * DISTINCT, GROUP BY, HAVING and UNION are preserved by counting rows
	 * returned from the original SELECT as a derived table.
	 *
	 * @param Select $query
	 */
	public function fetchCount(Select $query): int {
		[$query, $eventName] = $this->prepareQuery($query);

		$source = $this->createAggregateSource($query);

		$countAlias = $this->quoteIdentifier($source, '__oc_count');
		$sourceAlias = $this->quoteIdentifier($source, '__oc_count_source');

		$sql = sprintf(
			'SELECT COUNT(*) AS %s FROM (%s) AS %s',
			$countAlias,
			$source->getStatement(),
			$sourceAlias
		);

		$bindings = $source->getBindValues();
		$types = $this->inferBindingTypes($bindings);

		$result = $this->connection->executeQuery($sql, $bindings, $types);

		$this->validateResult($result);

		$value = $result->row['__oc_count'] ?? null;

		if (!is_numeric($value)) {
			throw new \UnexpectedValueException('COUNT query did not return a numeric __oc_count value');
		}

		$count = (int)$value;

		if ($eventName !== null) {
			$this->triggerEvent($eventName, 'after', $query, $count);
		}

		return $count;
	}

	/**
	 * Check whether the query returns at least one row, ignoring top-level
	 * pagination. DISTINCT, GROUP BY, HAVING and UNION remain effective.
	 *
	 * @param Select $query
	 */
	public function fetchExists(Select $query): bool {
		[$query, $eventName] = $this->prepareQuery($query);

		$source = $this->createAggregateSource($query);

		$existsAlias = $this->quoteIdentifier($source, '__oc_exists');
		$sourceAlias = $this->quoteIdentifier($source, '__oc_exists_source');

		$sql = sprintf(
			'SELECT EXISTS (SELECT 1 FROM (%s) AS %s) AS %s',
			$source->getStatement(),
			$sourceAlias,
			$existsAlias
		);

		$bindings = $source->getBindValues();
		$types = $this->inferBindingTypes($bindings);

		$result = $this->connection->executeQuery($sql, $bindings, $types);

		$this->validateResult($result);

		$value = $result->row['__oc_exists'] ?? null;

		if (!is_numeric($value)) {
			throw new \UnexpectedValueException('EXISTS query did not return a numeric __oc_exists value');
		}

		$exists = (bool)(int)$value;

		if ($eventName !== null) {
			$this->triggerEvent($eventName, 'after', $query, $exists);
		}

		return $exists;
	}

	/**
	 * Execute INSERT, UPDATE or DELETE.
	 *
	 * @param AbstractQuery $query
	 */
	public function executeStatement(AbstractQuery $query): int {
		[$query, $eventName] = $this->prepareQuery($query);

		$bindings = $query->getBindValues();
		$types = $this->inferBindingTypes($bindings);

		$result = $this->connection->executeStatement(
			$query->getStatement(),
			$bindings,
			$types
		);

		if ($eventName !== null) {
			$this->triggerEvent($eventName, 'after', $query, $result);
		}

		return $result;
	}

	/**
	 * Execute SELECT and return the DB result object.
	 *
	 * @param AbstractQuery $query
	 */
	private function executeQuery(AbstractQuery $query): \stdClass {
		[$query, $eventName] = $this->prepareQuery($query);

		$bindings = $query->getBindValues();
		$types = $this->inferBindingTypes($bindings);

		$result = $this->connection->executeQuery(
			$query->getStatement(),
			$bindings,
			$types
		);

		$this->validateResult($result);

		if ($eventName !== null) {
			$this->triggerEvent($eventName, 'after', $query, $result);
			$this->validateResult($result);
		}

		return $result;
	}

	/**
	 * Infer array parameter types for values supplied through Aura's bindValues().
	 * Scalar values are typed by ParameterExpander itself. Array values need an
	 * ArrayParameterType so they can be expanded into a list of placeholders.
	 *
	 * @param array<array-key, mixed> $bindings
	 * @return array<array-key, ArrayParameterType>
	 */
	private function inferBindingTypes(array $bindings): array {
		$types = [];

		foreach ($bindings as $name => $value) {
			if (is_array($value)) {
				$types[$name] = $this->inferArrayParameterType($value);
			}
		}

		return $types;
	}

	/**
	 * Infer a common binding type for an array. NULL values do not affect
	 * inference because the expander binds them as SQL NULL independently.
	 * Mixed numeric values are promoted to FLOAT; mixed scalar values that
	 * include strings are bound as STRING.
	 *
	 * @param array<array-key, mixed> $values
	 */
	private function inferArrayParameterType(array $values): ArrayParameterType {
		if ($values === []) {
			throw new \InvalidArgumentException(
				'Array SQL binding cannot be empty; handle empty IN/NOT IN conditions before execution'
			);
		}

		$hasString = false;
		$hasFloat = false;
		$hasInteger = false;

		foreach ($values as $value) {
			if ($value === null) {
				continue;
			}

			if (is_string($value)) {
				$hasString = true;
			} elseif (is_float($value)) {
				$hasFloat = true;
			} elseif (is_int($value)) {
				$hasInteger = true;
			} elseif (is_bool($value)) {
				continue;
			} else {
				throw new \InvalidArgumentException(
					'Unsupported array SQL binding value: ' . get_debug_type($value)
				);
			}
		}

		if ($hasString) {
			return ArrayParameterType::STRING;
		}

		if ($hasFloat) {
			return ArrayParameterType::FLOAT;
		}

		if ($hasInteger) {
			return ArrayParameterType::INTEGER;
		}

		return ArrayParameterType::BOOLEAN;
	}

	/**
	 * Dispatch the before event and return the query used for this execution.
	 *
	 * The query is cloned only when an event is configured. This prevents
	 * before-listener changes from accumulating when a builder is executed
	 * more than once, while keeping the no-event path allocation-free.
	 *
	 * @template TQuery of AbstractQuery
	 *
	 * @param TQuery $query
	 *
	 * @return array{0: TQuery, 1: string|null}
	 */
	private function prepareQuery(AbstractQuery $query): array {
		$eventName = $query instanceof QueryEventAwareInterface
			? $query->getEventName()
			: null;

		if ($eventName === null) {
			return [$query, null];
		}

		$query = clone $query;

		$this->triggerEvent($eventName, 'before', $query);

		return [$query, $eventName];
	}

	/**
	 * Create a copy suitable for aggregate queries.
	 *
	 * Aura's page(0) clears both LIMIT and OFFSET. ORDER BY and FOR UPDATE
	 * are irrelevant or invalid for this derived-table use case. The
	 * SQL_CALC_FOUND_ROWS flag is also disabled for the count/exists query.
	 *
	 * @param Select $query
	 */
	private function createAggregateSource(Select $query): Select {
		$source = clone $query;

		$source
			->page(0)
			->resetOrderBy()
			->forUpdate(false)
			->calcFoundRows(false);

		return $source;
	}

	/**
	 * Quote an internal derived-table or result alias using Aura's dialect.
	 *
	 * @param Select $query
	 * @param string $identifier
	 */
	private function quoteIdentifier(Select $query, string $identifier): string {
		return $query->getQuoteNamePrefix() . $identifier . $query->getQuoteNameSuffix();
	}

	/**
	 * Validate the result shape expected from the OpenCart DB adapter.
	 *
	 * @param \stdClass $result
	 */
	private function validateResult(\stdClass $result): void {
		if (
			!isset($result->row) || !is_array($result->row)
			|| !isset($result->rows) || !is_array($result->rows)
			|| !isset($result->num_rows)
		) {
			throw new \UnexpectedValueException('Query result must contain array row, array rows and integer num_rows properties');
		}
	}

	/**
	 * Trigger a query event using services from the OpenCart Registry.
	 *
	 * before receives: (query)
	 * after receives:  (query, result)
	 *
	 * SELECT result objects can be modified by after listeners. Scalar
	 * count/exists/statement results are provided for inspection.
	 *
	 * @param string        $name
	 * @param string        $stage
	 * @param AbstractQuery $query
	 * @param mixed|null    $result
	 */
	private function triggerEvent(
		string $name,
		string $stage,
		AbstractQuery $query,
		mixed $result = null
	): void {
		if ($this->registry === null) {
			throw new \LogicException('Registry is required for query event: ' . $name);
		}

		$event = $this->registry->get('event');

		if (!$event instanceof Event) {
			throw new \LogicException('OpenCart Event service is not available');
		}

		$trigger = sprintf(
			'query/%s/%s',
			$name,
			$stage
		);

		$arguments = [$query];

		if ($stage === 'after') {
			$arguments[] = $result;
		}

		$event->trigger($trigger, $arguments);
	}
}
