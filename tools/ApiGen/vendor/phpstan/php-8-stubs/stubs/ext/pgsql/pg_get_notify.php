<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_get_notify($connection, int $mode = PGSQL_ASSOC) : array|false
{
}
/**
 * @return array<int|string, int|string>
 * @refcount 1
 */
#[\Since('8.1')]
function pg_get_notify(\PgSql\Connection $connection, int $mode = PGSQL_ASSOC) : array|false
{
}