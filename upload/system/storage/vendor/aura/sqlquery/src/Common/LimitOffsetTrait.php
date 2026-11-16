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
 * An interface for LIMIT...OFFSET clauses.
 *
 * @package Aura.SqlQuery
 *
 */
trait LimitOffsetTrait
{
    use LimitTrait;

    /**
     *
     * The OFFSET value.
     *
     * @var int
     *
     */
    protected $offset = 0;

    /**
     *
     * Sets a limit offset on the query.
     *
     * @param int $offset Start returning after this many rows.
     *
     * @return $this
     *
     */
    public function offset($offset)
    {
        $this->offset = (int) $offset;
        return $this;
    }

    /**
     *
     * Returns the OFFSET value.
     *
     * @return int
     *
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
