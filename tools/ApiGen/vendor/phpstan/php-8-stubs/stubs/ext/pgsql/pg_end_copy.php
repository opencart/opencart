<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_end_copy($connection = null) : bool
{
}
#[\Since('8.1')]
function pg_end_copy(?\PgSql\Connection $connection = null) : bool
{
}