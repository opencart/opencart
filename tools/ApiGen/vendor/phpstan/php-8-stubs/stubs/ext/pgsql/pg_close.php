<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_close($connection = null) : bool
{
}
#[\Since('8.1')]
function pg_close(?\PgSql\Connection $connection = null) : bool
{
}