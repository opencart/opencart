<?php 

/**
 * @param resource|string $connection
 * @return resource|false
 * @alias pg_query
 */
#[\Until('8.1')]
function pg_exec($connection, string $query = UNKNOWN)
{
}
/**
 * @param PgSql\Connection|string $connection
 * @alias pg_query
 */
#[\Since('8.1')]
function pg_exec($connection, string $query = UNKNOWN) : \PgSql\Result|false
{
}