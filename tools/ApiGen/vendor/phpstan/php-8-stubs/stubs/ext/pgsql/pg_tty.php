<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_tty($connection = null) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_tty(?\PgSql\Connection $connection = null) : string
{
}