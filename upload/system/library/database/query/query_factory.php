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

	public function select(): SelectInterface {
		return $this->newSelect();
	}

	public function insert(): InsertInterface {
		return $this->newInsert();
	}

	public function update(): UpdateInterface {
		return $this->newUpdate();
	}

	public function delete(): DeleteInterface {
		return $this->newDelete();
	}

	/**
	 * Register a custom query class.
	 *
	 * The custom class must extend the current OpenCart query class for the
	 * selected query type. Registration affects subsequently created queries.
	 *
	 * @param 'select'|'insert'|'update'|'delete' $queryType
	 * @param string $class
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
			throw new \InvalidArgumentException(
				sprintf(
					'Query class for "%s" must extend %s',
					strtolower($queryType),
					$defaultClass
				)
			);
		}

		if (!$replace && $currentClass !== $defaultClass) {
			throw new \LogicException(
				sprintf('A custom class for "%s" is already registered', strtolower($queryType))
			);
		}

		/** @var class-string<AbstractQuery> $class */
		$this->queryClasses[$queryType] = $class;
	}

	/**
	 * Register a macro available on the selected query type.
	 * Macros add methods; overriding existing methods requires a custom class.
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
			throw new \LogicException(
				sprintf(
					'Cannot register macro "%s": method already exists on %s',
					$name,
					$class
				)
			);
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
	 */
	protected function newInstance($query) {
		if (!isset($this->queryClasses[$query])) {
			throw new \InvalidArgumentException(
				'Unsupported query type: ' . $query
			);
		}

		$class = $this->queryClasses[$query];

		return new $class(
			$this->getQuoter(),
			$this->newBuilder($query),
			$this->executor,
			$this->macroRegistry
		);
	}

	private function normalizeQueryType(string $queryType): string {
		$queryType = ucfirst(strtolower($queryType));

		if (!isset($this->queryClasses[$queryType])) {
			throw new \InvalidArgumentException(
				'Unsupported query type: ' . $queryType
			);
		}

		return $queryType;
	}

	private function getDefaultQueryClass(string $queryType): string {
		return match ($queryType) {
			'Select' => Select::class,
			'Insert' => Insert::class,
			'Update' => Update::class,
			'Delete' => Delete::class,
			default => throw new \InvalidArgumentException(
				'Unsupported query type: ' . $queryType
			)
		};
	}
}
