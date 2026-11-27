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
 * An object for MySQL INSERT queries.
 *
 * @package Aura.SqlQuery
 *
 */
class Insert extends Common\Insert
{
    /**
     *
     * if true, use a REPLACE sql command instead of INSERT
     *
     * @var bool
     *
     */
    protected $use_replace = false;

    /**
     *
     * Column values for ON DUPLICATE KEY UPDATE section of query; the key is
     * the column name and the value is the column value.
     *
     * @param array
     *
     */
    protected $col_on_update_values;

    /**
     *
     * Use a REPLACE statement.
     * Matches similar orReplace() function for Sqlite
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function orReplace($enable = true)
    {
        $this->use_replace = $enable;
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
     * Adds or removes LOW_PRIORITY flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function lowPriority($enable = true)
    {
        $this->setFlag('LOW_PRIORITY', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes IGNORE flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function ignore($enable = true)
    {
        $this->setFlag('IGNORE', $enable);
        return $this;
    }

    /**
     *
     * Adds or removes DELAYED flag.
     *
     * @param bool $enable Set or unset flag (default true).
     *
     * @return $this
     *
     */
    public function delayed($enable = true)
    {
        $this->setFlag('DELAYED', $enable);
        return $this;
    }

    /**
     *
     * Sets one column value placeholder in ON DUPLICATE KEY UPDATE section;
     * if an optional second parameter is passed, that value is bound to the
     * placeholder.
     *
     * @param string $col The column name.
     *
     * @param array $value Optional: a value to bind to the placeholder.
     *
     * @return $this
     *
     */
    public function onDuplicateKeyUpdateCol($col, ...$value)
    {
        $key = $this->quoter->quoteName($col);
        $bind = $col . '__on_duplicate_key';
        $this->col_on_update_values[$key] = ":$bind";
        if (count($value) > 0) {
            $this->bindValue($bind, $value[0]);
        }
        return $this;
    }

    /**
     *
     * Sets multiple column value placeholders in ON DUPLICATE KEY UPDATE
     * section. If an element is a key-value pair, the key is treated as the
     * column name and the value is bound to that column.
     *
     * @param array $cols A list of column names, optionally as key-value
     * pairs where the key is a column name and the value is a bind value for
     * that column.
     *
     * @return $this
     *
     */
    public function onDuplicateKeyUpdateCols(array $cols)
    {
        foreach ($cols as $key => $val) {
            if (is_int($key)) {
                // integer key means the value is the column name
                $this->onDuplicateKeyUpdateCol($val);
            } else {
                // the key is the column name and the value is a value to
                // be bound to that column
                $this->onDuplicateKeyUpdateCol($key, $val);
            }
        }
        return $this;
    }

    /**
     *
     * Sets a column value directly in ON DUPLICATE KEY UPDATE section; the
     * value will not be escaped, although fully-qualified identifiers in the
     * value will be quoted.
     *
     * @param string $col The column name.
     *
     * @param string $value The column value expression.
     *
     * @return $this
     *
     */
    public function onDuplicateKeyUpdate($col, $value)
    {
        if ($value === null) {
            $value = 'NULL';
        }

        $key = $this->quoter->quoteName($col);
        $value = $this->quoter->quoteNamesIn($value);
        $this->col_on_update_values[$key] = $value;
        return $this;
    }

    /**
     *
     * Builds this query object into a string.
     *
     * @return string
     *
     */
    protected function build()
    {
        $stm = parent::build();

        if ($this->use_replace) {
            // change INSERT to REPLACE
            $stm = 'REPLACE' . substr($stm, 6);
        }

        return $stm
            . $this->builder->buildValuesForUpdateOnDuplicateKey($this->col_on_update_values);
    }
}
