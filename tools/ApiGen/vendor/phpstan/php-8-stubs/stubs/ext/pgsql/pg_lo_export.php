<?php 

/**
 * @param resource|string|int $connection
 * @param string|int $oid
 * @param string|int $filename
 * @return resource|false
 */
#[\Until('8.1')]
function pg_lo_export($connection, $oid = UNKNOWN, $filename = UNKNOWN) : bool
{
}
/**
 * @param PgSql\Connection|string|int $connection
 * @param string|int $oid
 * @param string|int $filename
 */
#[\Since('8.1')]
function pg_lo_export($connection, $oid = UNKNOWN, $filename = UNKNOWN) : bool
{
}