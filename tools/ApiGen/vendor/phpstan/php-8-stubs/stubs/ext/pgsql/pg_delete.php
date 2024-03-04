<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_delete($connection, string $table_name, array $conditions, int $flags = PGSQL_DML_EXEC) : string|bool
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_delete(\PgSql\Connection $connection, string $table_name, array $conditions, int $flags = PGSQL_DML_EXEC) : string|bool
{
}