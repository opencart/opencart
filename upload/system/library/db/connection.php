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
}