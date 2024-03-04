<?php 

/**
 * @param resource $connection
 * @return resource|false
 */
#[\Until('8.1')]
function pg_socket($connection)
{
}
/**
 * @return resource|false
 * @refcount 1
 */
#[\Since('8.1')]
function pg_socket(\PgSql\Connection $connection)
{
}