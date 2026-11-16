<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Pgsql;

use Aura\SqlQuery\Common;

/**
 *
 * An object for PgSQL UPDATE queries.
 *
 * @package Aura.SqlQuery
 *
 */
class Delete extends Common\Delete implements ReturningInterface
{
    use ReturningTrait;

    /**
     *
     * Builds the statement.
     *
     * @return string
     *
     */
    protected function build()
    {
        return parent::build()
            . $this->builder->buildReturning($this->returning);
    }
}
