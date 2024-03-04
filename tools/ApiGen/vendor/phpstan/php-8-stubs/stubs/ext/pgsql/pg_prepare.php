<?php 

/**
 * @param resource|string $connection
 * @return resource|false
 */
#[\Until('8.1')]
function pg_prepare($connection, string $statement_name, string $query = UNKNOWN)
{
}
/**
 * @param PgSql\Connection|string $connection
 * @refcount 1
 */
#[\Since('8.1')]
function pg_prepare($connection, string $statement_name, string $query = UNKNOWN) : \PgSql\Result|false
{
}