<?php 

/**
 * @param resource $connection
 * @param string|int $oid
 */
#[\Until('8.1')]
function pg_lo_unlink($connection, $oid = UNKNOWN) : bool
{
}
/**
 * @param PgSql\Connection $connection
 * @param string|int $oid
 */
#[\Since('8.1')]
function pg_lo_unlink($connection, $oid = UNKNOWN) : bool
{
}