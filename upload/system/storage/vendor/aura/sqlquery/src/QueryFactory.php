<?php
/**
 *
 * This file is part of Aura for PHP.
 *
 * @license http://opensource.org/licenses/mit-license.php MIT
 *
 */
namespace Aura\SqlQuery;

/**
 *
 * Creates query statement objects.
 *
 * @package Aura.SqlQuery
 *
 */
class QueryFactory
{
    /**
     * Use the 'common' driver instead of a database-specific one.
     */
    const COMMON = 'common';

    /**
     *
     * What database are we building for?
     *
     * @param string
     *
     */
    protected $db;

    /**
     *
     * Build "common" query objects regardless of database type?
     *
     * @param bool
     *
     */
    protected $common = false;

    /**
     *
     * A map of `table.col` names to last-insert-id names.
     *
     * @var array
     *
     */
    protected $last_insert_id_names = array();

    /**
     *
     * A Quoter for identifiers.
     *
     * @param QuoterInterface
     *
     */
    protected $quoter;

    /**
     *
     * Constructor.
     *
     * @param string $db The database type.
     *
     * @param string $common Pass the constant self::COMMON to force common
     * query objects instead of db-specific ones.
     *
     */
    public function __construct($db, $common = null)
    {
        $this->db = ucfirst(strtolower($db));
        $this->common = ($common === self::COMMON);
    }

    /**
     *
     * Sets the last-insert-id names to be used for Insert queries..
     *
     * @param array $last_insert_id_names A map of `table.col` names to
     * last-insert-id names.
     *
     * @return null
     *
     */
    public function setLastInsertIdNames(array $last_insert_id_names)
    {
        $this->last_insert_id_names = $last_insert_id_names;
    }

    /**
     *
     * Returns a new SELECT object.
     *
     * @return Common\SelectInterface
     *
     */
    public function newSelect()
    {
        return $this->newInstance('Select');
    }

    /**
     *
     * Returns a new INSERT object.
     *
     * @return Common\InsertInterface
     *
     */
    public function newInsert()
    {
        $insert = $this->newInstance('Insert');
        $insert->setLastInsertIdNames($this->last_insert_id_names);
        return $insert;
    }

    /**
     *
     * Returns a new UPDATE object.
     *
     * @return Common\UpdateInterface
     *
     */
    public function newUpdate()
    {
        return $this->newInstance('Update');
    }

    /**
     *
     * Returns a new DELETE object.
     *
     * @return Common\DeleteInterface
     *
     */
    public function newDelete()
    {
        return $this->newInstance('Delete');
    }

    /**
     *
     * Returns a new query object.
     *
     * @param string $query The query object type.
     *
     * @return Common\SelectInterface|Common\InsertInterface|Common\UpdateInterface|Common\DeleteInterface
     *
     */
    protected function newInstance($query)
    {
        $queryClass = "Aura\SqlQuery\\{$this->db}\\{$query}";
        if ($this->common) {
            $queryClass = "Aura\SqlQuery\Common\\{$query}";
        }

        $builderClass = "Aura\SqlQuery\\{$this->db}\\{$query}Builder";
        if ($this->common || ! class_exists($builderClass)) {
            $builderClass = "Aura\SqlQuery\Common\\{$query}Builder";
        }

        return new $queryClass(
            $this->getQuoter(),
            $this->newBuilder($query)
        );
    }

    /**
     *
     * Returns a new Builder for the database driver.
     *
     * @param string $query The query type.
     *
     * @return Common\AbstractBuilder
     *
     */
    protected function newBuilder($query)
    {
        $builderClass = "Aura\SqlQuery\\{$this->db}\\{$query}Builder";
        if ($this->common || ! class_exists($builderClass)) {
            $builderClass = "Aura\SqlQuery\Common\\{$query}Builder";
        }
        return new $builderClass();
    }

    /**
     *
     * Returns the Quoter object for queries; creates one if needed.
     *
     * @return Common\Quoter
     *
     */
    protected function getQuoter()
    {
        if (! $this->quoter) {
            $this->quoter = $this->newQuoter();
        }
        return $this->quoter;
    }

    /**
     *
     * Returns a new Quoter for the database driver.
     *
     * @return Common\QuoterInterface
     *
     */
    protected function newQuoter()
    {
        $quoterClass = "Aura\SqlQuery\\{$this->db}\Quoter";
        if (! class_exists($quoterClass)) {
            $quoterClass = "Aura\SqlQuery\Common\Quoter";
        }
        return new $quoterClass();
    }
}
