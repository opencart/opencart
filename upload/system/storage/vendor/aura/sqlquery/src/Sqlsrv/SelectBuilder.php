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
class SelectBuilder extends Common\SelectBuilder
{
    /**
     *
     * Override so that LIMIT equivalent will be applied by applyLimit().
     *
     * @param int $limit Ignored.
     *
     * @param int $offset Ignored.
     *
     * @see build()
     *
     * @see applyLimit()
     *
     */
    public function buildLimitOffset($limit, $offset)
    {
        return '';
    }

    /**
     *
     * Modify the statement applying limit/offset equivalent portions to it.
     *
     * @param string $stm The SQL statement.
     *
     * @param int $limit The LIMIT value.
     *
     * @param int $offset The OFFSET value.
     *
     * @return string
     *
     */
    public function applyLimit($stm, $limit, $offset)
    {
        if (! $limit && ! $offset) {
            return $stm; // no limit or offset
        }

        // limit but no offset?
        if ($limit && ! $offset) {
            // use TOP in place
            return preg_replace(
                '/^(SELECT( DISTINCT)?)/',
                "$1 TOP {$limit}",
                $stm
            );
        }

        // both limit and offset. must have an ORDER clause to work; OFFSET is
        // a sub-clause of the ORDER clause. cannot use FETCH without OFFSET.
        return $stm . PHP_EOL . "OFFSET {$offset} ROWS "
                    . "FETCH NEXT {$limit} ROWS ONLY";
    }
}
