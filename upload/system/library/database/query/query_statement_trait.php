<?php

namespace Opencart\System\Library\Database\Query;

/**
 * Adds execution support to Aura DML query objects.
 */
trait QueryStatementTrait {
    protected QueryExecutor $queryExecutor;

    protected function initializeExecutor(QueryExecutor $executor): void {
        $this->queryExecutor = $executor;
    }

    public function execute(): int {
        return $this->queryExecutor->executeStatement($this);
    }
}
