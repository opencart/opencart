<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_host($connection = null) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_host(?\PgSql\Connection $connection = null) : string
{
}