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
 * A trait for LIMIT clauses.
 *
 * @package Aura.SqlQuery
 *
 */
trait LimitTrait
{
    /**
     *
     * The LIMIT value.
     *
     * @var int
     *
     */
    protected $limit = 0;

    /**
     *
     * Sets a limit count on the query.
     *
     * @param int $limit The number of rows to select.
     *
     * @return $this
     *
     */
    public function limit($limit)
    {
        $this->limit = (int) $limit;
        return $this;
    }

    /**
     *
     * Returns the LIMIT value.
     *
     * @return int
     *
     */
    public function getLimit()
    {
        return $this->limit;
    }
}
