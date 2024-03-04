<?php 

/** @generate-function-entries */
class PDOStatement implements \IteratorAggregate
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function bindColumn(string|int $column, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function bindParam(string|int $param, mixed &$var, int $type = PDO::PARAM_STR, int $maxLength = 0, mixed $driverOptions = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function bindValue(string|int $param, mixed $value, int $type = PDO::PARAM_STR)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function closeCursor()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function columnCount()
    {
    }
    /**
     * @tentative-return-type
     * @return (bool | null)
     */
    public function debugDumpParams()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | null)
     */
    public function errorCode()
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function errorInfo()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function execute(?array $params = null)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function fetch(int $mode = PDO::FETCH_DEFAULT, int $cursorOrientation = PDO::FETCH_ORI_NEXT, int $cursorOffset = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public function fetchAll(int $mode = PDO::FETCH_DEFAULT, mixed ...$args)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function fetchColumn(int $column = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return (object | false)
     */
    public function fetchObject(?string $class = "stdClass", array $constructorArgs = [])
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function getAttribute(int $name)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function getColumnMeta(int $column)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function nextRowset()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function rowCount()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value)
    {
    }
    /** @return bool */
    public function setFetchMode(int $mode, mixed ...$args)
    {
    }
    public function getIterator() : Iterator
    {
    }
}