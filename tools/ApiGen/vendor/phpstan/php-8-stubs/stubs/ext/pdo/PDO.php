<?php 

/** @generate-function-entries */
class PDO
{
    #[\Until('8.1')]
    public function setAttribute(int $attribute, mixed $value)
    {
    }
    public function __construct(string $dsn, ?string $username = null, ?string $password = null, ?array $options = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function beginTransaction()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function commit()
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
     * @return (int | false)
     */
    public function exec(string $statement)
    {
    }
    /**
     * @tentative-return-type
     * @return (bool | int | string | array | null)
     */
    public function getAttribute(int $attribute)
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public static function getAvailableDrivers()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function inTransaction()
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function lastInsertId(?string $name = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (PDOStatement | false)
     */
    public function prepare(string $query, array $options = [])
    {
    }
    /**
     * @tentative-return-type
     * @return (PDOStatement | false)
     */
    public function query(string $query, ?int $fetchMode = null, mixed ...$fetchModeArgs)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function quote(string $string, int $type = PDO::PARAM_STR)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function rollBack()
    {
    }
    #[\Since('8.1')]
    public function setAttribute(int $attribute, mixed $value) : bool
    {
    }
}