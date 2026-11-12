<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Sqlsrv;

use Aura\SqlQuery\Common;

/**
 *
 * An object for Sqlsrv SELECT queries.
 *
 * @package Aura.SqlQuery
 *
 */
class Select extends Common\Select
{
    /**
     *
     * Builds this query object into a string.
     *
     * @return string
     *
     */
    protected function build()
    {
        return $this->builder->applyLimit(parent::build(), $this->getLimit(), $this->offset);
    }
}
