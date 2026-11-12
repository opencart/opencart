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
 * An object for PgSQL INSERT queries.
 *
 * @package Aura.SqlQuery
 *
 */
class Insert extends Common\Insert implements ReturningInterface
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

    /**
     *
     * Returns the proper name for passing to `PDO::lastInsertId()`.
     *
     * @param string $col The last insert ID column.
     *
     * @return string The sequence name "{$into_table}_{$col}_seq", or the
     * value from `$last_insert_id_names`.
     *
     */
    public function getLastInsertIdName($col)
    {
        $name = parent::getLastInsertIdName($col);
        if (! $name) {
            $name = "{$this->into_raw}_{$col}_seq";
        }
        return $name;
    }
}
