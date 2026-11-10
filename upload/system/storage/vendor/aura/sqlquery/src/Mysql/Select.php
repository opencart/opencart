<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Mysql;

use Aura\SqlQuery\Common;

/**
 *
 * An object for MySQL SELECT queries.
 *
 * @package Aura.SqlQuery
 *
 */
class Select extends Common\Select
{
    /**
     *
     * Adds or removes SQL_CALC_FOUND_ROWS flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function calcFoundRows($enable = true)
    {
        $this->setFlag('SQL_CALC_FOUND_ROWS', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes SQL_CACHE flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function cache($enable = true)
    {
        $this->setFlag('SQL_CACHE', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes SQL_NO_CACHE flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function noCache($enable = true)
    {
        $this->setFlag('SQL_NO_CACHE', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes STRAIGHT_JOIN flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function straightJoin($enable = true)
    {
        $this->setFlag('STRAIGHT_JOIN', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes HIGH_PRIORITY flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function highPriority($enable = true)
    {
        $this->setFlag('HIGH_PRIORITY', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes SQL_SMALL_RESULT flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function smallResult($enable = true)
    {
        $this->setFlag('SQL_SMALL_RESULT', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes SQL_BIG_RESULT flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function bigResult($enable = true)
    {
        $this->setFlag('SQL_BIG_RESULT', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes SQL_BUFFER_RESULT flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function bufferResult($enable = true)
    {
        $this->setFlag('SQL_BUFFER_RESULT', $enable);
        return $this;
    }
}
