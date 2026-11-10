<?php

namespace Opencart\System\Library\Database\Query;

/**
 * Stores extension methods for query objects.
 */
final class QueryMacroRegistry {
    /** @var array<string, array<string, \Closure>> */
    private array $macros = [];

    public function register(string $queryType, string $name, \Closure $macro, bool $replace = false): void {
        $queryType = $this->normalizeQueryType($queryType);
        $name = $this->normalizeMethodName($name);

        if (isset($this->macros[$queryType][$name]) && !$replace) {
            throw new \LogicException(
                sprintf('Macro "%s" is already registered for "%s" queries', $name, $queryType)
            );
        }

        $this->macros[$queryType][$name] = $macro;
    }

    public function has(string $queryType, string $name): bool {
        $queryType = $this->normalizeQueryType($queryType);
        $name = $this->normalizeMethodName($name);

        return isset($this->macros[$queryType][$name]);
    }

    public function call(object $query, string $queryType, string $name, array $arguments): mixed {
        $queryType = $this->normalizeQueryType($queryType);
        $name = $this->normalizeMethodName($name);

        if (!isset($this->macros[$queryType][$name])) {
            throw new \BadMethodCallException(
                sprintf('Undefined method "%s" for "%s" query', $name, $queryType)
            );
        }

        $macro = $this->macros[$queryType][$name];

        // Static closures cannot be rebound to the query object.
        if ((new \ReflectionFunction($macro))->isStatic()) {
            return $macro(...$arguments);
        }

        return $macro->call($query, ...$arguments);
    }

    private function normalizeQueryType(string $queryType): string {
        $queryType = strtolower($queryType);

        if (!in_array($queryType, ['select', 'insert', 'update', 'delete'], true)) {
            throw new \InvalidArgumentException(
                'Unsupported query type: ' . $queryType
            );
        }

        return $queryType;
    }

    private function normalizeMethodName(string $name): string {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/D', $name)) {
            throw new \InvalidArgumentException(
                'Invalid query macro name: ' . $name
            );
        }

        return strtolower($name);
    }
}
