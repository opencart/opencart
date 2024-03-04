<?php 

/**
 * @param resource $connection
 * @return resource|false
 */
#[\Until('8.1')]
function pg_get_result($connection)
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_get_result(\PgSql\Connection $connection) : \PgSql\Result|false
{
}