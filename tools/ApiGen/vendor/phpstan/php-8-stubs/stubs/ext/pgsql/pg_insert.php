<?php 

/**
 * @param resource $connection
 * @return resource|string|bool
 */
#[\Until('8.1')]
function pg_insert($connection, string $table_name, array $values, int $flags = PGSQL_DML_EXEC)
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_insert(\PgSql\Connection $connection, string $table_name, array $values, int $flags = PGSQL_DML_EXEC) : \PgSql\Result|string|bool
{
}