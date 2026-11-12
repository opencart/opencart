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
 * Common code for WHERE clauses.
 *
 * @package Aura.SqlQuery
 *
 */
trait WhereTrait
{
    /**
     *
     * Adds a WHERE condition to the query by AND.
     *
     * @param string $cond The WHERE condition.
     *
     * @param array $bind Values to be bound to placeholders
     *
     * @return $this
     *
     */
    public function where($cond, array $bind = [])
    {
        $this->addClauseCondWithBind('where', 'AND', $cond, $bind);
        return $this;
    }

    /**
     *
     * Adds a WHERE condition to the query by OR. If the condition has
     * ?-placeholders, additional arguments to the method will be bound to
     * those placeholders sequentially.
     *
     * @param string $cond The WHERE condition.
     *
     * @param array $bind Values to be bound to placeholders
     *
     * @return $this
     *
     * @see where()
     *
     */
    public function orWhere($cond, array $bind = [])
    {
        $this->addClauseCondWithBind('where', 'OR', $cond, $bind);
        return $this;
    }
}
