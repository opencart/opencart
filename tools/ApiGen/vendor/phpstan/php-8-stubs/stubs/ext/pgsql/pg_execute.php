<?php 

/**
 * @param resource|string $connection
 * @param string|array $statement_name
 * @return resource|false
 */
#[\Until('8.1')]
function pg_execute($connection, $statement_name, array $params = UNKNOWN)
{
}
/**
 * @param PgSql\Connection|string $connection
 * @param string|array $statement_name
 * @refcount 1
 */
#[\Since('8.1')]
function pg_execute($connection, $statement_name, array $params = UNKNOWN) : \PgSql\Result|false
{
}