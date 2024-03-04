<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_select($connection, string $table_name, array $conditions, int $flags = PGSQL_DML_EXEC, int $mode = PGSQL_ASSOC) : array|string|false
{
}
/**
 * @return array<int, array>|string|false
 * @refcount 1
 */
#[\Since('8.1')]
function pg_select(\PgSql\Connection $connection, string $table_name, array $conditions, int $flags = PGSQL_DML_EXEC, int $mode = PGSQL_ASSOC) : array|string|false
{
}