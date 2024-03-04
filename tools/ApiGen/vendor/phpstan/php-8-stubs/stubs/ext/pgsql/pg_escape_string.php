<?php 

/** @param resource|string $connection */
#[\Until('8.1')]
function pg_escape_string($connection, string $string = UNKNOWN) : string
{
}
/**
 * @param PgSql\Connection|string $connection
 * @refcount 1
 */
#[\Since('8.1')]
function pg_escape_string($connection, string $string = UNKNOWN) : string
{
}