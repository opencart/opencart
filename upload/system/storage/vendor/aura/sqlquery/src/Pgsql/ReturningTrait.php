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
trait ReturningTrait
{
    /**
     *
     * The columns to be returned.
     *
     * @var array
     *
     */
    protected $returning = [];

    /**
     *
     * Adds returning columns to the query.
     *
     * Multiple calls to returning() will append to the list of columns, not
     * overwrite the previous columns.
     *
     * @param array $cols The column(s) to add to the query.
     *
     * @return $this
     *
     */
    public function returning(array $cols)
    {
        foreach ($cols as $col) {
            $this->returning[] = $this->quoter->quoteNamesIn($col);
        }
        return $this;
    }
}
