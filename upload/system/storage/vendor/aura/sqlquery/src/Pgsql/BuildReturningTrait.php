<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Pgsql;

/**
 *
 * Common code for RETURNING clauses.
 *
 * @package Aura.SqlQuery
 *
 */
trait BuildReturningTrait
{
    /**
     *
     * Builds the `RETURNING` clause of the statement.
     *
     * @param array $returning Return these columns.
     *
     * @return string
     *
     */
    public function buildReturning(array $returning)
    {
        if (empty($returning)) {
            return ''; // not applicable
        }

        return PHP_EOL . 'RETURNING' . $this->indentCsv($returning);
    }
}
