<?php 

/**
 * @param resource|string $connection
 * @return resource|false
 */
#[\Until('8.1')]
function pg_query($connection, string $query = UNKNOWN)
{
}
/**
 * @param PgSql\Connection|string $connection
 * @refcount 1
 */
#[\Since('8.1')]
function pg_query($connection, string $query = UNKNOWN) : \PgSql\Result|false
{
}