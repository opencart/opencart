<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_dbname($connection = null) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_dbname(?\PgSql\Connection $connection = null) : string
{
}