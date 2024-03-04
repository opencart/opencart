<?php 

/**
 * @param resource $connection
 * @param string|int $oid
 * @return resource|false
 * @alias pg_lo_open
 * @deprecated
 */
#[\Until('8.1')]
function pg_loopen($connection, $oid = UNKNOWN, string $mode = UNKNOWN)
{
}
/**
 * @param PgSql\Connection $connection
 * @param string|int $oid
 * @alias pg_lo_open
 * @deprecated
 */
#[\Since('8.1')]
function pg_loopen($connection, $oid = UNKNOWN, string $mode = UNKNOWN) : \PgSql\Lob|false
{
}