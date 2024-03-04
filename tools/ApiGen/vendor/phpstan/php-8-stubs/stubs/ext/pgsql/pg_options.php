<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_options($connection = null) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_options(?\PgSql\Connection $connection = null) : string
{
}