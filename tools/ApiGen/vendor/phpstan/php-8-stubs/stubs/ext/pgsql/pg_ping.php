<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_ping($connection = null) : bool
{
}
#[\Since('8.1')]
function pg_ping(?\PgSql\Connection $connection = null) : bool
{
}