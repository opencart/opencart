<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery\Common;

use Aura\SqlQuery\QueryInterface;

/**
 *
 * An interface for INSERT queries.
 *
 * @package Aura.SqlQuery
 *
 */
interface InsertInterface extends QueryInterface, ValuesInterface
{
    /**
     *
     * Sets the table to insert into.
     *
     * @param string $into The table to insert into.
     *
     * @return $this
     *
     */
    public function into($into);

    /**
     *
     * Sets the map of fully-qualified `table.column` names to last-insert-id
     * names. Generally useful only for extended tables in Postgres.
     *
     * @param array $last_insert_id_names The list of ID names.
     *
     */
    public function setLastInsertIdNames(array $last_insert_id_names);

    /**
     *
     * Returns the proper name for passing to `PDO::lastInsertId()`.
     *
     * @param string $col The last insert ID column.
     *
     * @return mixed Normally null, since most drivers do not need a name;
     * alternatively, a string from `$last_insert_id_names`.
     *
     */
    public function getLastInsertIdName($col);

    /**
     *
     * Adds multiple rows for bulk insert.
     *
     * @param array $rows An array of rows, where each element is an array of
     * column key-value pairs. The values are bound to placeholders.
     *
     * @return $this
     *
     */
    public function addRows(array $rows);

    /**
     *
     * Add one row for bulk insert; increments the row counter and optionally
     * adds columns to the new row.
     *
     * When adding the first row, the counter is not incremented.
     *
     * After calling `addRow()`, you can further call `col()`, `cols()`, and
     * `set()` to work with the newly-added row. Calling `addRow()` again will
     * finish off the current row and start a new one.
     *
     * @param array $cols An array of column key-value pairs; the values are
     * bound to placeholders.
     *
     * @return $this
     *
     */
    public function addRow(array $cols = array());
}
