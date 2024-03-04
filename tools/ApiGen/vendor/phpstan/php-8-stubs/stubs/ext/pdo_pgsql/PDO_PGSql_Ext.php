<?php 

/** @generate-function-entries */
// These are extension methods for PDO. This is not a real class.
class PDO_PGSql_Ext
{
    /**
     * @tentative-return-type
     * @return bool
     */
    public function pgsqlCopyFromArray(string $tableName, array $rows, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function pgsqlCopyFromFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function pgsqlCopyToArray(string $tableName, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function pgsqlCopyToFile(string $tableName, string $filename, string $separator = "\t", string $nullAs = "\\\\N", ?string $fields = null)
    {
    }
    /**
     * @tentative-return-type
     * @return (string | false)
     */
    public function pgsqlLOBCreate()
    {
    }
    /** @return resource|false */
    public function pgsqlLOBOpen(string $oid, string $mode = "rb")
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function pgsqlLOBUnlink(string $oid)
    {
    }
    /**
     * @tentative-return-type
     * @return (array | false)
     */
    public function pgsqlGetNotify(int $fetchMode = PDO::FETCH_USE_DEFAULT, int $timeoutMilliseconds = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function pgsqlGetPid()
    {
    }
}