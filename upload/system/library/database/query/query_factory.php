<?php

namespace Opencart\System\Library\Database\Query;

use Aura\SqlQuery\AbstractQuery;
use Aura\SqlQuery\Common\DeleteInterface;
use Aura\SqlQuery\Common\InsertInterface;
use Aura\SqlQuery\Common\SelectInterface;
use Aura\SqlQuery\Common\UpdateInterface;
use Aura\SqlQuery\QueryFactory as AuraQueryFactory;

/**
 * OpenCart query factory built on top of Aura.SqlQuery.
 *
 * Aura remains an implementation detail. Extensions should target these
 * OpenCart query classes and registration methods instead of Aura internals.
 */
class QueryFactory extends AuraQueryFactory {
	/**
	 * @var array<string, class-string<AbstractQuery>>
	 */
	private array $queryClasses = [
		'Select' => Select::class,
		'Insert' => Insert::class,
		'Update' => Update::class,
		'Delete' => Delete::class
	];

	private QueryExecutor $executor;
	private QueryMacroRegistry $macroRegistry;

	public function __construct(QueryExecutor $executor) {
		parent::__construct('mysql');

		$this->executor = $executor;
		$this->macroRegistry = new QueryMacroRegistry();
	}

	public function select(): Select {
		$query = $this->newSelect();

		if (!$query instanceof Select) {
			throw new \LogicException('Query factory did not create an OpenCart SELECT query');
		}

		return $query;
	}

	public function insert(): Insert {
		$query = $this->newInsert();

		if (!$query instanceof Insert) {
			throw new \LogicException('Query factory did not create an OpenCart INSERT query');
		}

		return $query;
	}

	public function update(): Update {
		$query = $this->newUpdate();

		if (!$query instanceof Update) {
			throw new \LogicException('Query factory did not create an OpenCart UPDATE query');
		}

		return $query;
	}

	public function delete(): Delete {
		$query = $this->newDelete();

		if (!$query instanceof Delete) {
			throw new \LogicException('Query factory did not create an OpenCart DELETE query');
		}

		return $query;
	}

	/**
	 * Register a custom query class.
	 *
	 * The custom class must extend the current OpenCart query class for the
	 * selected query type. Registration affects subsequently created queries.
	 *
	 * @param string $queryType
	 * @param string $class
	 * @param bool   $replace
	 */
	public function registerQueryClass(
		string $queryType,
		string $class,
		bool $replace = false
	): void {
		$queryType = $this->normalizeQueryType($queryType);
		$defaultClass = $this->getDefaultQueryClass($queryType);
		$currentClass = $this->queryClasses[$queryType];

		if (!class_exists($class) || !is_subclass_of($class, $defaultClass)) {
			throw new \InvalidArgumentException(sprintf('Query class for "%s" must extend %s', strtolower($queryType), $defaultClass));
		}

		if (!$replace && $currentClass !== $defaultClass) {
			throw new \LogicException(sprintf('A custom class for "%s" is already registered', strtolower($queryType)));
		}

		// @var class-string<AbstractQuery> $class
		$this->queryClasses[$queryType] = $class;
	}

	/**
	 * Register a macro available on the selected query type.
	 * Macros add methods; overriding existing methods requires a custom class.
	 *
	 * @param string   $queryType
	 * @param string   $name
	 * @param \Closure $macro
	 * @param bool     $replace
	 */
	public function registerMacro(
		string $queryType,
		string $name,
		\Closure $macro,
		bool $replace = false
	): void {
		$queryType = $this->normalizeQueryType($queryType);
		$class = $this->queryClasses[$queryType];

		if (method_exists($class, $name)) {
			throw new \LogicException(sprintf('Cannot register macro "%s": method already exists on %s', $name, $class));
		}

		$this->macroRegistry->register(
			strtolower($queryType),
			$name,
			$macro,
			$replace
		);
	}

	/**
	 * Override Aura's query object creation while reusing its dialect-specific
	 * Quoter and Builder implementations.
	 *
	 * @param string $query
	 *
	 * @return DeleteInterface|InsertInterface|SelectInterface|UpdateInterface
	 */
	protected function newInstance($query) {
		if (!isset($this->queryClasses[$query])) {
			throw new \InvalidArgumentException('Unsupported query type: ' . $query);
		}

		$class = $this->queryClasses[$query];
		$instance = new $class(
			$this->getQuoter(),
			$this->newBuilder($query),
			$this->executor,
			$this->macroRegistry
		);

		if (
			!($instance instanceof DeleteInterface)
			&& !($instance instanceof InsertInterface)
			&& !($instance instanceof SelectInterface)
			&& !($instance instanceof UpdateInterface)
		) {
			throw new \LogicException('Query factory created an unsupported query instance: ' . $class);
		}

		return $instance;
	}

	/**
	 * @return 'Delete'|'Insert'|'Select'|'Update'
	 */
	private function normalizeQueryType(string $queryType): string {
		return match (strtolower($queryType)) {
			'select' => 'Select',
			'insert' => 'Insert',
			'update' => 'Update',
			'delete' => 'Delete',
			default  => throw new \InvalidArgumentException('Unsupported query type: ' . $queryType)
		};
	}

	/**
	 * @return class-string<AbstractQuery>
	 */
	private function getDefaultQueryClass(string $queryType): string {
		return match ($queryType) {
			'Select' => Select::class,
			'Insert' => Insert::class,
			'Update' => Update::class,
			'Delete' => Delete::class,
			default  => throw new \InvalidArgumentException('Unsupported query type: ' . $queryType)
		};
	}
}
