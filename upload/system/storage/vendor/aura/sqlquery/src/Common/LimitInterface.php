<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Common;

/**
 *
 * An interface for LIMIT clauses.
 *
 * @package Aura.SqlQuery
 *
 */
interface LimitInterface
{
    /**
     *
     * Sets a limit count on the query.
     *
     * @param int $limit The number of rows to select.
     *
     * @return $this
     *
     */
    public function limit($limit);

    /**
     *
     * Returns the LIMIT value.
     *
     * @return int
     *
     */
    public function getLimit();
}
