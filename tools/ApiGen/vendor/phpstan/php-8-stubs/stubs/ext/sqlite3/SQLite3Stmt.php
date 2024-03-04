<?php 

class SQLite3Stmt
{
    private function __construct(SQLite3 $sqlite3, string $query)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function bindParam(string|int $param, mixed &$var, int $type = SQLITE3_TEXT)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function bindValue(string|int $param, mixed $value, int $type = SQLITE3_TEXT)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function clear()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @return (SQLite3Result | false)
     */
    public function execute()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function getSQL(bool $expand = false)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function paramCount()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function readOnly()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function reset()
    {
    }
}