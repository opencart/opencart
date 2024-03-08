<?php
namespace Opencart\System\Library\DB;

use Closure;

interface Connection
{
    /**
     * Execute a Closure within a transaction
     *
     * @param Closure $callback
     * @return mixed
     */
    public function transaction(Closure $callback);

    /**
     * Commit the active db transaction
     *
     * @return void
     */
    public function commit(): void;

    /**
     * Rollback the active db transaction
     *
     * @return void
     */
    public function rollback(): void;
}
