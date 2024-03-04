<?php 

/**
 * @param resource $connection
 * @param string|int $oid
 */
#[\Until('8.1')]
function pg_lo_create($connection = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}
/**
 * @param PgSql\Connection $connection
 * @param string|int $oid
 * @refcount 1
 */
#[\Since('8.1')]
function pg_lo_create($connection = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}