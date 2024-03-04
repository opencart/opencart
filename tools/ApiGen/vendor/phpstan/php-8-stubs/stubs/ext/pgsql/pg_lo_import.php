<?php 

/**
 * @param resource|string $connection
 * @param string|int $filename
 * @param string|int $oid
 * @return resource|false
 */
#[\Until('8.1')]
function pg_lo_import($connection, $filename = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}
/**
 * @param PgSql\Connection|string $connection
 * @param string|int $filename
 * @param string|int $oid
 * @refcount 1
 */
#[\Since('8.1')]
function pg_lo_import($connection, $filename = UNKNOWN, $oid = UNKNOWN) : string|int|false
{
}