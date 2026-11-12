<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Common;

use Aura\SqlQuery\Exception;

/**
 *
 * Base builder for all query objects.
 *
 * @package Aura.SqlQuery
 *
 */
abstract class AbstractBuilder
{
    /**
     *
     * Builds the flags as a space-separated string.
     *
     * @param array $flags The flags to build.
     *
     * @return string
     *
     */
    public function buildFlags(array $flags)
    {
        if (empty($flags)) {
            return ''; // not applicable
        }

        return ' ' . implode(' ', array_keys($flags));
    }

    /**
     *
     * Builds the `WHERE` clause of the statement.
     *
     * @param array $where The WHERE elements.
     *
     * @return string
     *
     */
    public function buildWhere(array $where)
    {
        if (empty($where)) {
            return ''; // not applicable
        }

        return PHP_EOL . 'WHERE' . $this->indent($where);
    }

    /**
     *
     * Builds the `ORDER BY ...` clause of the statement.
     *
     * @param array $order_by The ORDER BY elements.
     *
     * @return string
     *
     */
    public function buildOrderBy(array $order_by)
    {
        if (empty($order_by)) {
            return ''; // not applicable
        }

        return PHP_EOL . 'ORDER BY' . $this->indentCsv($order_by);
    }

    /**
     *
     * Builds the `LIMIT` clause of the statement.
     *
     * @param int $limit The LIMIT element.
     *
     * @return string
     *
     */
    public function buildLimit($limit)
    {
        if (empty($limit)) {
            return '';
        }
        return PHP_EOL . "LIMIT {$limit}";
    }

    /**
     *
     * Builds the `LIMIT ... OFFSET` clause of the statement.
     *
     * @param int $limit The LIMIT element.
     *
     * @param int $offset The OFFSET element.
     *
     * @return string
     *
     */
    public function buildLimitOffset($limit, $offset)
    {
        $clause = '';

        if (!empty($limit)) {
            $clause .= "LIMIT {$limit}";
        }

        if (!empty($offset)) {
            $clause .= " OFFSET {$offset}";
        }

        if (!empty($clause)) {
            $clause = PHP_EOL . trim($clause);
        }

        return $clause;
    }

    /**
     *
     * Returns an array as an indented comma-separated values string.
     *
     * @param array $list The values to convert.
     *
     * @return string
     *
     */
    public function indentCsv(array $list)
    {
        return PHP_EOL . '    '
             . implode(',' . PHP_EOL . '    ', $list);
    }

    /**
     *
     * Returns an array as an indented string.
     *
     * @param array $list The values to convert.
     *
     * @return string
     *
     */
    public function indent(array $list)
    {
        return PHP_EOL . '    '
             . implode(PHP_EOL . '    ', $list);
    }
}
