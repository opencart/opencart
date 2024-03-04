<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_port($connection = null) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_port(?\PgSql\Connection $connection = null) : string
{
}