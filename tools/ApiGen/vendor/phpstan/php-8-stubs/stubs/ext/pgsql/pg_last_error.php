<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_last_error($connection = null) : string
{
}
#[\Since('8.1')]
function pg_last_error(?\PgSql\Connection $connection = null) : string
{
}