<?php 

/**
 * @param resource|string $connection
 * @param string|int $filename
 * @param string|int $oid
 * @return resource|false
 * @alias pg_lo_import
 * @deprecated
 */
#[\Until('8.1')]
function pg_loimport($connection, $filename = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}
/**
 * @param PgSql\Connection|string $connection
 * @param string|int $filename
 * @param string|int $oid
 * @alias pg_lo_import
 * @deprecated
 */
#[\Since('8.1')]
function pg_loimport($connection, $filename = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}