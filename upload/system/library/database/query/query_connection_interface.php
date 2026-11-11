<?php

namespace Opencart\System\Library\Database\Query;

interface QueryConnectionInterface {
    /**
     * Execute a prepared query and return its result rows.
     *
     * @param array<array-key, mixed> $bindings
     * @param array<array-key, mixed> $types
     */
    public function executeQuery(string $sql, array $bindings = [], array $types = []): \stdClass;

    /**
     * Execute a prepared statement and return affected rows count.
     *
     * @param array<array-key, mixed> $bindings
     * @param array<array-key, mixed> $types
     */
    public function executeStatement(string $sql, array $bindings = [], array $types = []): int;
}
