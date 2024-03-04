<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_update($connection, string $table_name, array $values, array $conditions, int $flags = PGSQL_DML_EXEC) : string|bool
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_update(\PgSql\Connection $connection, string $table_name, array $values, array $conditions, int $flags = PGSQL_DML_EXEC) : string|bool
{
}