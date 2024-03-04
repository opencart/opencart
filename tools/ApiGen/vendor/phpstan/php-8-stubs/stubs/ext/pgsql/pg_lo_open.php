<?php 

/**
 * @param resource $connection
 * @param string|int $oid
 * @return resource|false
 */
#[\Until('8.1')]
function pg_lo_open($connection, $oid = UNKNOWN, string $mode = UNKNOWN)
{
}
/**
 * @param PgSql\Connection $connection
 * @param string|int $oid
 * @refcount 1
 */
#[\Since('8.1')]
function pg_lo_open($connection, $oid = UNKNOWN, string $mode = UNKNOWN) : \PgSql\Lob|false
{
}