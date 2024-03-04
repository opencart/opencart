<?php 

/** @param resource|string $connection */
#[\Until('8.1')]
function pg_parameter_status($connection, string $name = UNKNOWN) : string|false
{
}
/**
 * @param PgSql\Connection|string $connection
 * @refcount 1
 */
#[\Since('8.1')]
function pg_parameter_status($connection, string $name = UNKNOWN) : string|false
{
}