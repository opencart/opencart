<?php 

/**
 * @param resource $connection
 * @param string|int $oid
 * @alias pg_lo_create
 * @deprecated
 */
#[\Until('8.1')]
function pg_locreate($connection = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}
/**
 * @param PgSql\Connection $connection
 * @param string|int $oid
 * @alias pg_lo_create
 * @deprecated
 */
#[\Since('8.1')]
function pg_locreate($connection = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}