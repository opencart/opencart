<?php 

/** @generate-function-entries */
class SQLite3
{
    /** @implementation-alias SQLite3::open */
    public function __construct(string $filename, int $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, string $encryptionKey = "")
    {
    }
    /**
     * @tentative-return-type
     * @return void
     */
    public function open(string $filename, int $flags = SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE, string $encryptionKey = "")
    {
    }
    /** @return bool */
    public function close()
    {
    }
    /**
     * @tentative-return-type
     * @return array
     */
    public static function version()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function lastInsertRowID()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function lastErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function lastExtendedErrorCode()
    {
    }
    /**
     * @tentative-return-type
     * @return string
     */
    public function lastErrorMsg()
    {
    }
    /**
     * @tentative-return-type
     * @return int
     */
    public function changes()
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function busyTimeout(int $milliseconds)
    {
    }
    #ifndef SQLITE_OMIT_LOAD_EXTENSION
    /**
     * @tentative-return-type
     * @return bool
     */
    public function loadExtension(string $name)
    {
    }
    #endif
    #if SQLITE_VERSION_NUMBER >= 3006011
    /**
     * @tentative-return-type
     * @return bool
     */
    public function backup(SQLite3 $destination, string $sourceDatabase = "main", string $destinationDatabase = "main")
    {
    }
    #endif
    /**
     * @tentative-return-type
     * @return string
     */
    public static function escapeString(string $string)
    {
    }
    /**
     * @tentative-return-type
     * @return (SQLite3Stmt | false)
     */
    public function prepare(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function exec(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @return (SQLite3Result | false)
     */
    public function query(string $query)
    {
    }
    /**
     * @tentative-return-type
     * @return mixed
     */
    public function querySingle(string $query, bool $entireRow = false)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function createFunction(string $name, callable $callback, int $argCount = -1, int $flags = 0)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function createAggregate(string $name, callable $stepCallback, callable $finalCallback, int $argCount = -1)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function createCollation(string $name, callable $callback)
    {
    }
    /** @return resource|false */
    public function openBlob(string $table, string $column, int $rowid, string $database = "main", int $flags = SQLITE3_OPEN_READONLY)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function enableExceptions(bool $enable = false)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function enableExtendedResultCodes(bool $enable = true)
    {
    }
    /**
     * @tentative-return-type
     * @return bool
     */
    public function setAuthorizer(?callable $callback)
    {
    }
}